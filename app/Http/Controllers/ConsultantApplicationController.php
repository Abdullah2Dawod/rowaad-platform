<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\Specialization;
use App\Models\User;
use App\Notifications\NewConsultantApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Multi-step consultant application wizard (guest-friendly).
 *
 * Design decisions:
 *  • No prior registration required — step-1 creates the user account.
 *  • The account is a placeholder (no password) until the admin approves.
 *  • On approval, a random password is generated and emailed.
 */
class ConsultantApplicationController extends Controller
{
    /* ─────────────── Entry ─────────────── */

    public function start(Request $request): RedirectResponse
    {
        // Authenticated → resume their application state
        if ($request->user() && $c = $this->findApplicationFor($request->user())) {
            return match ($c->status) {
                Consultant::STATUS_SUBMITTED => redirect()->route('consultant.apply.pending'),
                Consultant::STATUS_APPROVED  => redirect()->route('consultants.show', $c),
                Consultant::STATUS_REJECTED  => redirect()->route('consultant.apply.rejected'),
                default => redirect()->route('consultant.apply.step', ['step' => min(3, max(1, $c->completed_step + 1))]),
            };
        }

        // Guest or resuming via session token → jump to appropriate step
        $c = $this->trackedApplication($request);
        $step = $c ? min(3, max(1, $c->completed_step + 1)) : 1;

        return redirect()->route('consultant.apply.step', ['step' => $step]);
    }

    /* ─────────────── Render step ─────────────── */

    public function step(Request $request, int $step): Response|RedirectResponse
    {
        abort_unless(in_array($step, [1, 2, 3], true), 404);

        $c = $this->trackedApplication($request);

        // Steps 2 & 3 require step-1 to be done first
        if ($step > 1 && ! $c) {
            return redirect()->route('consultant.apply.step', ['step' => 1]);
        }
        if ($c && $step > $c->completed_step + 1) {
            return redirect()->route('consultant.apply.step', ['step' => $c->completed_step + 1]);
        }

        $shared = [
            'application' => [
                'id'             => $c?->id,
                'status'         => $c?->status ?? Consultant::STATUS_DRAFT,
                'completed_step' => $c?->completed_step ?? 0,
                'current_step'   => $step,
            ],
            'existing' => $c ? $this->existingDataForStep($c, $step) : $this->emptyDataForStep($step),
        ];

        return match ($step) {
            1 => Inertia::render('Consultant/ApplyStep1', $shared),
            2 => Inertia::render('Consultant/ApplyStep2', $shared),
            3 => Inertia::render('Consultant/ApplyStep3', $shared + [
                'specializations' => Specialization::active()->orderBy('sort_order')
                    ->get(['id', 'slug', 'name_ar', 'name_en', 'icon']),
            ]),
        };
    }

    /* ─────────────── STEP 1 — Personal + create account ─────────────── */

    public function saveStep1(Request $request): RedirectResponse
    {
        $existing = $this->trackedApplication($request);
        $userId   = $existing?->user_id;

        $data = $request->validate([
            'full_name_ar'       => ['required', 'string', 'max:120'],
            'full_name_en'       => ['required', 'string', 'max:120'],
            'email'              => [
                'required', 'email', 'max:150',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'national_id'        => ['required', 'string', 'max:20'],
            'nationality'        => ['required', 'string', 'max:60'],
            'birth_date'         => ['required', 'date', 'before:-18 years'],
            'gender'             => ['required', 'in:male,female'],
            'city'               => ['required', 'string', 'max:80'],
            'country'            => ['required', 'string', 'max:2'],
            'phone'              => ['required', 'string', 'max:30'],
            'professional_title' => ['required', 'string', 'max:120'],
            'bio_ar'             => ['required', 'string', 'min:80', 'max:1500'],
            'bio_en'             => ['nullable', 'string', 'max:1500'],
        ]);

        // Create or fetch the user (placeholder — no usable password yet)
        $user = $existing?->user ?? User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name'     => $data['full_name_ar'],
                'role'     => 'user',
                'password' => Hash::make(Str::random(40)), // unusable random
                'phone'    => $data['phone'],
                'locale'   => 'ar',
            ]
        );
        // Keep phone/name in sync
        $user->fill([
            'name'  => $data['full_name_ar'],
            'phone' => $data['phone'],
        ])->save();

        $c = $existing ?? new Consultant(['user_id' => $user->id, 'country' => 'SA', 'status' => Consultant::STATUS_DRAFT]);
        $c->user_id = $user->id;
        $c->fill(collect($data)->except('email')->toArray());
        $c->completed_step = max($c->completed_step ?? 0, 1);
        $c->save();

        // Remember application id for guest resume
        $request->session()->put('consultant_application_id', $c->id);

        return redirect()->route('consultant.apply.step', ['step' => 2]);
    }

    /* ─────────────── STEP 2 — Documents ─────────────── */

    public function saveStep2(Request $request): RedirectResponse
    {
        $c = $this->trackedApplication($request);
        abort_unless($c, 302, ['Location' => route('consultant.apply.step', ['step' => 1])]);

        $request->validate([
            'avatar'                       => ['nullable', 'image', 'max:4096'],
            'cv'                           => ['nullable', 'file', 'mimes:pdf', 'max:8192'],
            'national_id_file'             => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'certificates'                 => ['nullable', 'array', 'max:10'],
            'certificates.*.title'         => ['required_with:certificates.*.file', 'string', 'max:150'],
            'certificates.*.file'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:6144'],
        ]);

        $dir = "consultants/{$c->id}";

        if ($f = $request->file('avatar')) {
            if ($c->avatar_path && ! Str::startsWith($c->avatar_path, 'http')) {
                Storage::disk('public')->delete($c->avatar_path);
            }
            $c->avatar_path = $f->store($dir, 'public');
        }
        if ($f = $request->file('cv')) {
            $c->cv_path && Storage::disk('public')->delete($c->cv_path);
            $c->cv_path = $f->store($dir, 'public');
        }
        if ($f = $request->file('national_id_file')) {
            $c->national_id_path && Storage::disk('public')->delete($c->national_id_path);
            $c->national_id_path = $f->store($dir, 'public');
        }

        $existing = $c->certificates ?? [];
        $incoming = $request->input('certificates', []);
        $merged = [];
        foreach ($incoming as $i => $cert) {
            $title = $cert['title'] ?? null;
            $file  = $request->file("certificates.$i.file");
            $existingPath = $existing[$i]['path'] ?? null;
            if ($file) {
                if ($existingPath) Storage::disk('public')->delete($existingPath);
                $path = $file->store($dir, 'public');
            } else {
                $path = $existingPath;
            }
            if ($title && $path) $merged[] = ['title' => $title, 'path' => $path];
        }
        $c->certificates    = $merged;
        $c->completed_step  = max($c->completed_step, 2);
        $c->save();

        return redirect()->route('consultant.apply.step', ['step' => 3]);
    }

    /* ─────────────── STEP 3 — Services + submit ─────────────── */

    public function saveStep3(Request $request): RedirectResponse
    {
        $c = $this->trackedApplication($request);
        abort_unless($c, 302, ['Location' => route('consultant.apply.step', ['step' => 1])]);

        $data = $request->validate([
            'specialization_id'          => ['required', 'exists:specializations,id'],
            'secondary_specializations'  => ['nullable', 'array', 'max:3'],
            'secondary_specializations.*'=> ['integer', 'exists:specializations,id'],
            'years_experience'           => ['required', 'integer', 'min:0', 'max:60'],
            'services'                   => ['required', 'array', 'min:1', 'max:8'],
            'services.*.title'           => ['required', 'string', 'max:120'],
            'services.*.description'     => ['nullable', 'string', 'max:400'],
            'services.*.duration_min'    => ['required', 'integer', 'min:15', 'max:240'],
            'hourly_rate'                => ['required', 'numeric', 'min:50', 'max:5000'],
            'session_duration_min'       => ['required', 'integer', 'in:30,45,60,90,120'],
            'languages'                  => ['required', 'array', 'min:1'],
            'languages.*'                => ['string', 'in:ar,en,fr'],
            'availability'               => ['nullable', 'array'],
        ]);

        $c->fill($data);
        $c->completed_step = 3;
        $c->status         = Consultant::STATUS_SUBMITTED;
        $c->submitted_at   = now();
        $c->save();

        // Notify all admins — never let a mail failure break submission
        foreach (User::where('role', 'admin')->get() as $admin) {
            try {
                $admin->notify(new NewConsultantApplication($c));
            } catch (\Throwable $e) {
                \Log::warning('[Consultant application notification] failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('consultant.apply.pending');
    }

    /* ─────────────── Terminal views ─────────────── */

    public function pending(Request $request): Response
    {
        $c = $this->trackedApplication($request);
        return Inertia::render('Consultant/ApplyPending', [
            'submitted_at' => $c?->submitted_at,
        ]);
    }

    public function rejected(Request $request): Response
    {
        $c = $this->trackedApplication($request);
        return Inertia::render('Consultant/ApplyRejected', [
            'reason' => $c?->rejection_reason,
        ]);
    }

    /* ─────────────── Helpers ─────────────── */

    private function findApplicationFor(User $user): ?Consultant
    {
        return Consultant::where('user_id', $user->id)->latest()->first();
    }

    /**
     * Resolve the application for the current request — supports both
     * authenticated users and guests (via session token).
     */
    private function trackedApplication(Request $request): ?Consultant
    {
        if ($request->user()) {
            return $this->findApplicationFor($request->user());
        }
        $id = $request->session()->get('consultant_application_id');
        return $id ? Consultant::find($id) : null;
    }

    private function existingDataForStep(Consultant $c, int $step): array
    {
        return match ($step) {
            1 => [
                'full_name_ar'       => $c->full_name_ar,
                'full_name_en'       => $c->full_name_en,
                'email'              => $c->user?->email,
                'national_id'        => $c->national_id,
                'nationality'        => $c->nationality,
                'birth_date'         => $c->birth_date?->format('Y-m-d'),
                'gender'             => $c->gender,
                'city'               => $c->city,
                'country'            => $c->country ?? 'SA',
                'phone'              => $c->user?->phone,
                'professional_title' => $c->professional_title,
                'bio_ar'             => $c->bio_ar,
                'bio_en'             => $c->bio_en,
            ],
            2 => [
                'avatar_url'          => $c->avatar_url,
                'cv_name'             => $c->cv_path ? basename($c->cv_path) : null,
                'national_id_name'    => $c->national_id_path ? basename($c->national_id_path) : null,
                'certificates'        => collect($c->certificates ?? [])->map(fn ($cert) => [
                    'title' => $cert['title'] ?? '',
                    'name'  => isset($cert['path']) ? basename($cert['path']) : null,
                ])->toArray(),
            ],
            3 => [
                'specialization_id'          => $c->specialization_id,
                'secondary_specializations'  => $c->secondary_specializations ?? [],
                'years_experience'           => $c->years_experience,
                'services'                   => $c->services ?? [['title' => '', 'description' => '', 'duration_min' => 60]],
                'hourly_rate'                => $c->hourly_rate ? (float) $c->hourly_rate : null,
                'session_duration_min'       => $c->session_duration_min ?: 60,
                'languages'                  => $c->languages ?? ['ar'],
                'availability'               => $c->availability ?? [],
            ],
        };
    }

    private function emptyDataForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'full_name_ar' => '', 'full_name_en' => '', 'email' => '', 'national_id' => '',
                'nationality' => 'سعودي', 'birth_date' => '', 'gender' => '', 'city' => '',
                'country' => 'SA', 'phone' => '', 'professional_title' => '',
                'bio_ar' => '', 'bio_en' => '',
            ],
            2 => ['avatar_url' => null, 'cv_name' => null, 'national_id_name' => null, 'certificates' => []],
            3 => [
                'specialization_id' => null, 'secondary_specializations' => [],
                'years_experience' => 0, 'services' => [['title' => '', 'description' => '', 'duration_min' => 60]],
                'hourly_rate' => 300, 'session_duration_min' => 60, 'languages' => ['ar'], 'availability' => [],
            ],
        };
    }
}
