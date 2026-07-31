<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Order;
use App\Services\CartService;
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

        // ─── Cross-device cart sync ───
        // If the user has an abandoned cart from another device (Order.status=cart),
        // merge its items back into this device's session cart. Ensures the badge
        // count + cart page reflect the same state everywhere they sign in.
        $this->mergeCartFromDb($user->id);

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

    /**
     * Merge any DB cart-status Order into the session cart, unioning items.
     * If both sides have the same purchasable, session wins on quantity (user
     * just added, so their intent is fresh) — no quantities are lost.
     */
    protected function mergeCartFromDb(int $userId): void
    {
        $order = Order::where('user_id', $userId)
                      ->where('status', Order::STATUS_CART)
                      ->with('items')
                      ->first();
        if (! $order || $order->items->isEmpty()) return;

        $cart = app(CartService::class);
        foreach ($order->items as $item) {
            // add() bumps quantity if the same purchasable already exists in session
            if (! $cart->has($item->purchasable_type, $item->purchasable_id)) {
                $cart->add(
                    type:      $item->purchasable_type,
                    id:        $item->purchasable_id,
                    title:     $item->title,
                    unitPrice: (float) $item->unit_price,
                    qty:       (int) $item->quantity,
                    meta:      $item->meta ?? [],
                );
            }
        }
    }
}
