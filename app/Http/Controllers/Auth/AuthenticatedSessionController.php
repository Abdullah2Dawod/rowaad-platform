<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle login. Admins & consultants land in the Filament panel — since that
     * panel is NOT rendered by Inertia we MUST force a hard browser navigation via
     * Inertia::location() (otherwise Inertia tries to fetch /admin as JSON and
     * the user is stuck on a blank/stale page).
     */
    public function store(LoginRequest $request): RedirectResponse|SymfonyResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();
        $target = match ($user->role) {
            'admin', 'consultant' => '/admin',
            default               => '/profile',
        };

        // /admin is Filament (Livewire) — needs a full page load, not an Inertia partial
        if ($target === '/admin') {
            return Inertia::location($target);
        }

        return redirect()->intended($target);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
