<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Consultant;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

/**
 * Premium dashboard hero — role-aware, appears above all other widgets.
 * Shows: live activity feed, 30-day revenue chart, consultant performance ring,
 * next actions cards. Designed to give admins/consultants a one-glance overview.
 */
class DashboardHero extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-hero';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = -10; // Show first
    protected static ?string $pollingInterval = '20s';

    public function getViewData(): array
    {
        $user = auth()->user();

        // ─────── Consultant hero ───────
        if ($user?->role === 'consultant' && $user->consultant) {
            return $this->consultantData($user);
        }

        // ─────── Admin hero ───────
        return $this->adminData();
    }

    protected function adminData(): array
    {
        $paidStatuses = [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED];

        // 30-day revenue timeline
        $timeline = collect(range(29, 0))->map(function ($d) use ($paidStatuses) {
            $day = now()->subDays($d);
            $revenue = (float) Booking::whereIn('status', $paidStatuses)
                ->whereBetween('paid_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
                ->sum('platform_share');
            $count = Booking::whereBetween('created_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])->count();
            return [
                'date'    => $day->toDateString(),
                'label'   => $day->translatedFormat('d M'),
                'day'     => $day->translatedFormat('D'),
                'revenue' => $revenue,
                'count'   => $count,
                'isToday' => $day->isToday(),
            ];
        });

        // Today's snapshot
        $todayRevenue = (float) Booking::whereIn('status', $paidStatuses)
            ->whereBetween('paid_at', [now()->startOfDay(), now()->endOfDay()])
            ->sum('platform_share');
        $todayBookings = Booking::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count();
        $yesterdayRevenue = (float) Booking::whereIn('status', $paidStatuses)
            ->whereBetween('paid_at', [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()])
            ->sum('platform_share');
        $revTrend = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100)
            : ($todayRevenue > 0 ? 100 : 0);

        // Live activity feed (recent 8 events)
        $activity = collect();
        $activity = $activity->concat(
            Booking::with(['user', 'consultant.user'])
                ->latest('created_at')->limit(6)->get()
                ->map(fn ($b) => [
                    'type'  => 'booking',
                    'title' => "حجز جديد · {$b->reference}",
                    'sub'   => ($b->user?->name ?? '—') . ' → ' . ($b->consultant?->full_name_ar ?? '—'),
                    'time'  => $b->created_at,
                    'icon'  => 'booking',
                    'color' => 'teal',
                    'amount'=> $b->amount,
                ])
        );
        $activity = $activity->concat(
            Consultant::with('user')->latest('submitted_at')->limit(3)->get()
                ->map(fn ($c) => [
                    'type'  => 'consultant',
                    'title' => "طلب مستشار · {$c->full_name_ar}",
                    'sub'   => $c->professional_title ?? '—',
                    'time'  => $c->submitted_at ?? $c->created_at,
                    'icon'  => 'user',
                    'color' => $c->status === Consultant::STATUS_APPROVED ? 'emerald' : 'amber',
                ])
        );
        $activity = $activity->concat(
            WithdrawalRequest::with('consultant.user')->latest()->limit(3)->get()
                ->map(fn ($w) => [
                    'type'  => 'withdrawal',
                    'title' => "طلب سحب · {$w->reference}",
                    'sub'   => $w->consultant?->full_name_ar ?? '—',
                    'time'  => $w->created_at,
                    'icon'  => 'wallet',
                    'color' => match($w->status){
                        'paid'     => 'emerald',
                        'approved' => 'blue',
                        'rejected' => 'red',
                        default    => 'amber',
                    },
                    'amount' => $w->amount,
                ])
        );
        $activity = $activity->sortByDesc('time')->take(8)->values();

        // Consultant performance ring (best consultant this month)
        $bestConsultant = Booking::whereIn('status', $paidStatuses)
            ->where('paid_at', '>=', now()->startOfMonth())
            ->selectRaw('consultant_id, SUM(consultant_share) as earnings, COUNT(*) as bookings')
            ->groupBy('consultant_id')
            ->orderByDesc('earnings')
            ->with('consultant.user')
            ->first();

        // Actionables — what needs the admin's attention right now
        $needsReview   = Consultant::pending()->count();
        $pendingPay    = Booking::where('status', Booking::STATUS_PAID)->count();
        $pendingEdits  = Consultant::whereNotNull('pending_changes')->count();
        $pendingWD     = WithdrawalRequest::pending()->count();

        return [
            'role'           => 'admin',
            'greetName'      => auth()->user()->name,
            'timeline'       => $timeline,
            'timelineMax'    => max($timeline->max('revenue') ?? 1, 1),
            'todayRevenue'   => $todayRevenue,
            'todayBookings'  => $todayBookings,
            'revTrend'       => $revTrend,
            'activity'       => $activity,
            'bestConsultant' => $bestConsultant,
            'actionables'    => [
                ['label' => 'مستشارون بانتظار الاعتماد', 'count' => $needsReview,  'color' => 'amber',   'url' => '/admin/consultants'],
                ['label' => 'حجوزات بانتظار المستشار',    'count' => $pendingPay,   'color' => 'blue',    'url' => '/admin/bookings'],
                ['label' => 'تعديلات ملف معلّقة',         'count' => $pendingEdits, 'color' => 'purple',  'url' => '/admin/consultants'],
                ['label' => 'طلبات سحب قيد المراجعة',     'count' => $pendingWD,    'color' => 'emerald', 'url' => '/admin/withdrawal-requests'],
            ],
        ];
    }

    protected function consultantData($user): array
    {
        $c = $user->consultant;
        $paidStatuses = [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED];

        $timeline = collect(range(29, 0))->map(function ($d) use ($c, $paidStatuses) {
            $day = now()->subDays($d);
            $earnings = (float) Booking::where('consultant_id', $c->id)
                ->whereIn('status', $paidStatuses)
                ->whereBetween('paid_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
                ->sum('consultant_share');
            $count = Booking::where('consultant_id', $c->id)
                ->whereBetween('created_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])->count();
            return [
                'date'    => $day->toDateString(),
                'label'   => $day->translatedFormat('d M'),
                'day'     => $day->translatedFormat('D'),
                'revenue' => $earnings,
                'count'   => $count,
                'isToday' => $day->isToday(),
            ];
        });

        $todayEarnings = (float) Booking::where('consultant_id', $c->id)
            ->whereIn('status', $paidStatuses)
            ->whereBetween('paid_at', [now()->startOfDay(), now()->endOfDay()])
            ->sum('consultant_share');
        $todayBookings = Booking::where('consultant_id', $c->id)
            ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count();

        // Activity — bookings for this consultant only
        $activity = Booking::where('consultant_id', $c->id)
            ->with(['user'])
            ->latest('created_at')->limit(8)->get()
            ->map(fn ($b) => [
                'type'  => 'booking',
                'title' => "حجز · {$b->reference}",
                'sub'   => ($b->user?->name ?? '—') . ' · ' . $b->duration_min . ' د',
                'time'  => $b->created_at,
                'icon'  => 'booking',
                'color' => match($b->status){
                    'completed' => 'emerald',
                    'confirmed' => 'amber',
                    'paid'      => 'blue',
                    'cancelled' => 'red',
                    default     => 'gray',
                },
                'amount' => $b->consultant_share,
            ]);

        // Actionables for consultant
        $bookingsPending = Booking::where('consultant_id', $c->id)->where('status', Booking::STATUS_PAID)->count();
        $withdrawalsPending = WithdrawalRequest::where('consultant_id', $c->id)->pending()->count();
        $pendingChangesCount = $c->hasPendingChanges() ? count($c->pending_changes) : 0;

        return [
            'role'           => 'consultant',
            'greetName'      => $c->full_name_ar ?: $user->name,
            'timeline'       => $timeline,
            'timelineMax'    => max($timeline->max('revenue') ?? 1, 1),
            'todayRevenue'   => $todayEarnings,
            'todayBookings'  => $todayBookings,
            'revTrend'       => 0,
            'activity'       => $activity,
            'bestConsultant' => null,
            'actionables'    => [
                ['label' => 'حجوزات بانتظار تأكيدك',   'count' => $bookingsPending,     'color' => 'amber',   'url' => '/admin/bookings'],
                ['label' => 'طلبات سحب قيد المراجعة',   'count' => $withdrawalsPending,  'color' => 'blue',    'url' => '/admin/wallet'],
                ['label' => 'تعديلات ملف قيد الاعتماد', 'count' => $pendingChangesCount, 'color' => 'purple',  'url' => '/admin/consultant-profile'],
            ],
        ];
    }
}
