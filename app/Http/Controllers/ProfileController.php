<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Profile page — works for all roles.
     *  - Regular user: full profile editing + own bookings
     *  - Admin/consultant: read-only info + test bookings they made
     *    (their real info is managed via the admin panel)
     */
    public function edit(Request $request): Response|RedirectResponse
    {
        $user = $request->user();
        $canEdit = $user->role === 'user'; // admins & consultants read-only here

        // Bookings this user made as a client
        $bookings = Booking::where('user_id', $user->id)
            ->with(['consultant:id,full_name_ar,professional_title,avatar_path'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (Booking $b) => [
                'id'             => $b->id,
                'reference'      => $b->reference,
                'preferred_date' => $b->preferred_date->format('Y-m-d'),
                'preferred_time' => $b->preferred_time,
                'duration_min'   => $b->duration_min,
                'amount'         => (float) $b->amount,
                'status'         => $b->status,
                'consultant' => [
                    'id'     => $b->consultant->id,
                    'name'   => $b->consultant->full_name_ar,
                    'title'  => $b->consultant->professional_title,
                    'avatar' => $b->consultant->avatar_url,
                ],
            ]);

        return Inertia::render('Profile/Edit', [
            'status'        => session('status'),
            'verifiedFlash' => (bool) $request->query('verified'),
            'canEdit'       => $canEdit,
            'profile' => [
                'name'              => $user->name,
                'email'             => $user->email,
                'phone'             => $user->phone,
                'role'              => $user->role,
                'avatar_url'        => $user->avatarUrl(),
                'is_verified'       => $user->isVerified(),
                'verified_at'       => $user->email_verified_at?->toIso8601String(),
                'created_at'        => $user->created_at?->format('Y-m-d'),
            ],
            'bookings' => $bookings,
            'stats'    => [
                'total_bookings' => Booking::where('user_id', $user->id)->count(),
                'completed'      => Booking::where('user_id', $user->id)->where('status', Booking::STATUS_COMPLETED)->count(),
                'upcoming'       => Booking::where('user_id', $user->id)->upcoming()->count(),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->role === 'user', 403, 'المعلومات تُدار من لوحة التحكم.');

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        // If email changed → clear verification and require re-verify
        if ($user->email !== $data['email']) {
            $user->email_verified_at = null;
        }
        $user->fill($data)->save();

        // If email changed → resend verification link
        if ($user->wasChanged('email') && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return back()->with('status', 'تم تحديث البريد الإلكتروني — تحقّق من بريدك لإعادة التوثيق.');
        }

        return back()->with('status', 'تم تحديث بياناتك.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->role === 'user', 403, 'الصورة تُدار من لوحة التحكم.');

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        // Delete old avatar if present and stored locally
        if ($user->avatar && ! Str::startsWith($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store("avatars/{$user->id}", 'public');
        $user->update(['avatar' => $path]);

        return back()->with('status', 'تم تحديث صورتك الشخصية.');
    }

    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->role === 'user', 403);
        if ($user->avatar && ! Str::startsWith($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->update(['avatar' => null]);

        return back()->with('status', 'تم حذف الصورة الشخصية.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'user', 403);

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('status', 'تم تحديث كلمة المرور.');
    }
}
