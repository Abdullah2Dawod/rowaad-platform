<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Quick signup for regular users — invoked from the login page's "create account"
 * section or from the booking flow when guest tries to book.
 * The new user is auto-logged-in but MUST verify their email before booking.
 */
class QuickSignupController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone'    => ['nullable', 'string', 'max:30'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'user',
            'locale'   => 'ar',
        ]);

        event(new Registered($user)); // fires verification email
        Auth::login($user);

        // Redirect to verify-email notice — booking is blocked until verified.
        return redirect()->route('verification.notice');
    }
}
