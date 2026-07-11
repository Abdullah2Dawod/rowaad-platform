<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Consultant;
use App\Models\User;
use App\Notifications\NewBookingPaid;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    /**
     * Show booking form for a specific consultant.
     */
    public function create(Consultant $consultant): Response
    {
        abort_unless($consultant->isApproved(), 404);
        $consultant->load(['user', 'specialization']);

        // Build the pricing tiers (30/45/60/90/120 min) with computed amount for each
        $hourly = (float) $consultant->hourly_rate;
        $tiers  = collect(Booking::DURATION_MULTIPLIERS)
            ->map(function ($mult, $mins) use ($hourly) {
                $base    = Booking::computeAmount($hourly, (int) $mins);
                $pricing = Booking::computePricing($base);
                return [
                    'duration_min' => (int) $mins,
                    'multiplier'   => (float) $mult,
                    'base_amount'  => $pricing['baseAmount'],
                    'zakat_amount' => $pricing['zakat'],
                    'total_amount' => $pricing['total'],
                    'amount'       => $pricing['total'],
                    'label'        => $this->durationLabel((int) $mins),
                ];
            })->values();

        return Inertia::render('Booking/Create', [
            'consultant' => [
                'id'                   => $consultant->id,
                'name'                 => $consultant->full_name_ar ?: $consultant->user->name,
                'title'                => $consultant->professional_title,
                'avatar'               => $consultant->avatar_url,
                'specialization'       => $consultant->specialization?->only(['name_ar','icon']),
                'hourly_rate'          => $hourly,
                'session_duration_min' => $consultant->session_duration_min,
                'services'             => $consultant->services ?? [],
                'languages'            => $consultant->languages ?? ['ar'],
            ],
            'pricing_tiers' => $tiers,
        ]);
    }

    private function durationLabel(int $mins): string
    {
        return match ($mins) {
            30  => '30 دقيقة',
            45  => '45 دقيقة',
            60  => 'ساعة',
            90  => 'ساعة ونصف',
            120 => 'ساعتان',
            default => "{$mins} دقيقة",
        };
    }

    /**
     * Store booking (goes to payment step).
     */
    public function store(Request $request, Consultant $consultant): RedirectResponse
    {
        abort_unless($consultant->isApproved(), 404);

        $data = $request->validate([
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'date_format:H:i'],
            'duration_min'   => ['required', 'integer', 'in:30,45,60,90,120'],
            'service_title'  => ['nullable', 'string', 'max:150'],
            'notes'          => ['nullable', 'string', 'max:1000'],
        ]);

        // Tax-on-top pricing: user pays base + 15% zakat
        $base    = Booking::computeAmount((float) $consultant->hourly_rate, (int) $data['duration_min']);
        $pricing = Booking::computePricing($base);

        $booking = Booking::create([
            'user_id'          => $request->user()->id,
            'consultant_id'    => $consultant->id,
            'preferred_date'   => $data['preferred_date'],
            'preferred_time'   => $data['preferred_time'],
            'duration_min'     => $data['duration_min'],
            'service_title'    => $data['service_title'] ?? null,
            'notes'            => $data['notes'] ?? null,
            'amount'           => $pricing['total'],           // What the user pays (345)
            'consultant_share' => $pricing['consultantShare'], // 150
            'platform_share'   => $pricing['platformShare'],   // 150
            'zakat_amount'     => $pricing['zakat'],           // 45
            'status'           => Booking::STATUS_PENDING_PAYMENT,
        ]);

        return redirect()->route('bookings.pay', $booking);
    }

    /**
     * Display payment page.
     */
    public function pay(Booking $booking, Request $request): Response
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        abort_if($booking->isPaid(), redirect()->route('bookings.show', $booking));

        $booking->load(['consultant.user', 'consultant.specialization']);

        return Inertia::render('Booking/Pay', [
            'booking' => [
                'id'               => $booking->id,
                'reference'        => $booking->reference,
                'preferred_date'   => $booking->preferred_date->format('Y-m-d'),
                'preferred_time'   => $booking->preferred_time,
                'duration_min'     => $booking->duration_min,
                'service_title'    => $booking->service_title,
                'notes'            => $booking->notes,
                'amount'           => (float) $booking->amount,
                'base_amount'      => (float) ($booking->consultant_share + $booking->platform_share),
                'consultant_share' => (float) $booking->consultant_share,
                'platform_share'   => (float) $booking->platform_share,
                'zakat_amount'     => (float) $booking->zakat_amount,
                'total_amount'     => (float) $booking->amount,
                'consultant' => [
                    'id'     => $booking->consultant->id,
                    'name'   => $booking->consultant->full_name_ar ?: $booking->consultant->user->name,
                    'title'  => $booking->consultant->professional_title,
                    'avatar' => $booking->consultant->avatar_url,
                    'specialization' => $booking->consultant->specialization?->only(['name_ar','icon']),
                ],
            ],
        ]);
    }

    /**
     * Mock payment processing.
     * In production this hooks into Moyasar/Tap/PayTabs webhook.
     */
    public function processPayment(Booking $booking, Request $request): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        abort_if($booking->isPaid(), redirect()->route('bookings.show', $booking));

        $request->validate([
            'payment_method' => ['required', 'in:mada,visa,apple_pay,mock'],
        ]);

        $booking->update([
            'status'         => Booking::STATUS_PAID,
            'payment_method' => $request->input('payment_method'),
            'payment_ref'    => 'MOCK-' . strtoupper(bin2hex(random_bytes(6))),
            'paid_at'        => now(),
        ]);

        // Notify admins + the consultant (failures never break the booking flow).
        // Mailtrap sandbox limits us to ~1 email/second, so we throttle a bit.
        $this->safeNotify(User::where('role', 'admin')->get(), $booking);
        if ($booking->consultant?->user) {
            usleep(1_100_000); // 1.1s pause — respects Mailtrap sandbox throttle
            $this->safeNotify(collect([$booking->consultant->user]), $booking);
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'تم الدفع بنجاح! سيتم تأكيد الحجز من قبل المستشار قريباً.');
    }

    /**
     * Send NewBookingPaid to a set of users without crashing the request
     * if the mail server refuses (rate limit / offline / bad creds).
     * DB channel notifications still fire — bell icon updates as expected.
     */
    private function safeNotify($recipients, Booking $booking): void
    {
        foreach ($recipients as $recipient) {
            try {
                $recipient->notify(new NewBookingPaid($booking));
            } catch (\Throwable $e) {
                Log::warning('[Booking notification] send failed: ' . $e->getMessage(), [
                    'booking_id' => $booking->id,
                    'recipient'  => $recipient->email ?? null,
                ]);
            }
        }
    }

    /**
     * Booking success/details page.
     */
    public function show(Booking $booking, Request $request): Response
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['consultant.user', 'consultant.specialization']);

        return Inertia::render('Booking/Success', [
            'booking' => [
                'id'             => $booking->id,
                'reference'      => $booking->reference,
                'preferred_date' => $booking->preferred_date->format('Y-m-d'),
                'preferred_time' => $booking->preferred_time,
                'duration_min'   => $booking->duration_min,
                'service_title'  => $booking->service_title,
                'amount'         => (float) $booking->amount,
                'status'         => $booking->status,
                'paid_at'        => $booking->paid_at?->toIso8601String(),
                'meeting_url'    => $booking->meeting_url,
                'consultant' => [
                    'id'     => $booking->consultant->id,
                    'name'   => $booking->consultant->full_name_ar ?: $booking->consultant->user->name,
                    'title'  => $booking->consultant->professional_title,
                    'avatar' => $booking->consultant->avatar_url,
                ],
            ],
        ]);
    }

    /**
     * User's booking list.
     */
    public function index(Request $request): Response
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with(['consultant.user', 'consultant.specialization'])
            ->latest()
            ->paginate(10)
            ->through(fn (Booking $b) => [
                'id'             => $b->id,
                'reference'      => $b->reference,
                'preferred_date' => $b->preferred_date->format('Y-m-d'),
                'preferred_time' => $b->preferred_time,
                'duration_min'   => $b->duration_min,
                'amount'         => (float) $b->amount,
                'status'         => $b->status,
                'consultant' => [
                    'id'     => $b->consultant->id,
                    'name'   => $b->consultant->full_name_ar ?: $b->consultant->user->name,
                    'avatar' => $b->consultant->avatar_url,
                ],
            ]);

        return Inertia::render('Booking/Index', ['bookings' => $bookings]);
    }
}
