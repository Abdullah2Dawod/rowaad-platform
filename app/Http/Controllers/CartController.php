<?php

namespace App\Http\Controllers;

use App\Models\FeasibilityStudy;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    /** Cart page — list items + subtotal + checkout link. */
    public function index(): Response
    {
        return Inertia::render('Cart/Index', [
            'items'    => array_values($this->cart->all()),
            'subtotal' => $this->cart->subtotal(),
            'count'    => $this->cart->count(),
        ]);
    }

    /** Add a feasibility study to the cart. */
    public function addStudy(Request $request, FeasibilityStudy $feasibility): RedirectResponse
    {
        abort_unless($feasibility->status === FeasibilityStudy::STATUS_APPROVED, 404);

        if ($feasibility->is_free) {
            return back()->with('error', 'الدراسة مجانية — يمكنك تحميلها مباشرة دون إضافتها للسلة.');
        }

        $this->cart->add(
            type: FeasibilityStudy::class,
            id: $feasibility->id,
            title: $feasibility->title,
            unitPrice: (float) $feasibility->price,
            qty: 1,
            meta: ['type' => 'feasibility', 'slug' => $feasibility->slug],
        );

        $this->syncCartOrder($request);

        return back()->with('success', 'تمت إضافة الدراسة إلى السلة.');
    }

    /** Remove an item. Type must be the fully-qualified class name of the purchasable. */
    public function remove(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'string'],
            'id'   => ['required', 'integer'],
        ]);
        $this->cart->remove($data['type'], (int) $data['id']);
        $this->syncCartOrder($request);
        return back()->with('success', 'تم إزالة العنصر من السلة.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $this->cart->clear();
        // Delete any open cart-status order for this user so admin sees it gone.
        Order::where('user_id', $request->user()?->id)
             ->where('status', Order::STATUS_CART)
             ->delete();
        return back()->with('success', 'تم إفراغ السلة.');
    }

    /**
     * Mirror the session cart to a DB Order (status=cart) so admins can see
     * abandoned carts in Filament. One order per authenticated user; upserts
     * on every change. Guests keep session-only carts (not visible to admin).
     */
    protected function syncCartOrder(Request $request): void
    {
        $user = $request->user();
        if (! $user) return;

        DB::transaction(function () use ($user) {
            $items = $this->cart->all();

            // Empty cart → delete any lingering cart order
            if (empty($items)) {
                Order::where('user_id', $user->id)
                     ->where('status', Order::STATUS_CART)
                     ->delete();
                return;
            }

            $subtotal = $this->cart->subtotal();
            $vat      = round($subtotal * 0.15, 2);
            $total    = round($subtotal + $vat, 2);

            $order = Order::firstOrNew([
                'user_id' => $user->id,
                'status'  => Order::STATUS_CART,
            ]);

            $order->fill([
                'contact_name'   => $user->name,
                'contact_email'  => $user->email,
                'contact_phone'  => $user->phone ?? '—',
                'subtotal'       => $subtotal,
                'vat_amount'     => $vat,
                'total'          => $total,
                'currency'       => 'SAR',
            ])->save();

            // Rebuild items — simplest, avoids stale rows if a cart item was removed
            $order->items()->delete();
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'         => $order->id,
                    'purchasable_type' => $item['purchasable_type'],
                    'purchasable_id'   => $item['purchasable_id'],
                    'title'            => $item['title'],
                    'unit_price'       => $item['unit_price'],
                    'quantity'         => $item['quantity'],
                    'subtotal'         => round(((float) $item['unit_price']) * ((int) $item['quantity']), 2),
                    'meta'             => $item['meta'] ?? null,
                ]);
            }
        });
    }
}
