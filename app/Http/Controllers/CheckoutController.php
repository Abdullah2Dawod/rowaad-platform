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

/**
 * Secure checkout.
 *
 * Design principles:
 *  - No card data ever touches this codebase — payment_reference is a token
 *    returned by the (mock) gateway; real integrations (Moyasar/Tap/PayTabs)
 *    handoff via hosted-fields or redirect flow.
 *  - Orders start `pending`, only flip to `paid` once the gateway confirms.
 *  - Rate limiting + CSRF applied by the default web middleware group.
 *  - IP + user-agent stored on order for fraud audit.
 */
class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function show(Request $request): Response|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة.');
        }

        return Inertia::render('Checkout/Index', [
            'items'    => array_values($this->cart->all()),
            'subtotal' => $this->cart->subtotal(),
            'vatRate'  => 0.15,
            'user'     => $request->user() ? [
                'name'  => $request->user()->name,
                'email' => $request->user()->email,
                'phone' => $request->user()->phone ?? null,
            ] : null,
        ]);
    }

    public function place(Request $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة.');
        }

        $data = $request->validate([
            'contact_name'    => ['required', 'string', 'max:120'],
            'contact_email'   => ['required', 'email', 'max:150'],
            'contact_phone'   => ['required', 'string', 'max:30'],
            'company_name'    => ['nullable', 'string', 'max:150'],
            'tax_id'          => ['nullable', 'string', 'max:60'],
            'billing_address' => ['nullable', 'string', 'max:500'],
            'payment_method'  => ['required', 'string', 'in:mada,apple_pay,stc_pay,bank_transfer,credit_card'],
            'notes'           => ['nullable', 'string', 'max:500'],
        ]);

        $subtotal = $this->cart->subtotal();
        $vat      = round($subtotal * 0.15, 2);
        $total    = round($subtotal + $vat, 2);

        $order = DB::transaction(function () use ($request, $data, $subtotal, $vat, $total) {
            // Prefer reusing the user's existing cart-status order so admins don't
            // see a duplicate abandoned-cart entry hanging around after checkout.
            $order = Order::where('user_id', $request->user()?->id)
                          ->where('status', Order::STATUS_CART)
                          ->first();

            $payload = [
                'user_id'         => $request->user()?->id,
                'contact_name'    => $data['contact_name'],
                'contact_email'   => $data['contact_email'],
                'contact_phone'   => $data['contact_phone'],
                'company_name'    => $data['company_name']    ?? null,
                'tax_id'          => $data['tax_id']          ?? null,
                'billing_address' => $data['billing_address'] ?? null,
                'subtotal'        => $subtotal,
                'vat_amount'      => $vat,
                'total'           => $total,
                'currency'        => 'SAR',
                'payment_method'  => $data['payment_method'],
                'status'          => Order::STATUS_PENDING,
                'notes'           => $data['notes'] ?? null,
                'ip_address'      => $request->ip(),
                'user_agent'      => substr((string) $request->userAgent(), 0, 255),
            ];

            if ($order) {
                $order->update($payload);
                $order->items()->delete();
            } else {
                $order = Order::create($payload);
            }

            foreach ($this->cart->all() as $item) {
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

            return $order;
        });

        return redirect()->route('checkout.pay', $order);
    }

    /** Payment page — hosted-field simulation. Real integration slots in here. */
    public function pay(Order $order): Response|RedirectResponse
    {
        abort_unless($order->status === Order::STATUS_PENDING, 404);
        return Inertia::render('Checkout/Pay', [
            'order' => [
                'id'             => $order->id,
                'reference'      => $order->reference,
                'total'          => (float) $order->total,
                'subtotal'       => (float) $order->subtotal,
                'vat_amount'     => (float) $order->vat_amount,
                'currency'       => $order->currency,
                'payment_method' => $order->payment_method,
                'items'          => $order->items->map(fn ($i) => [
                    'title'      => $i->title,
                    'quantity'   => $i->quantity,
                    'subtotal'   => (float) $i->subtotal,
                ]),
            ],
        ]);
    }

    /**
     * Confirm payment (MOCK for now — replace with real Moyasar/Tap callback).
     *
     * In production this endpoint will be triggered by the payment gateway's
     * webhook signed with a shared secret. Never trust client-side signals
     * alone in a real integration.
     */
    public function confirm(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->status === Order::STATUS_PENDING, 404);

        // TODO(payment): replace this block with real gateway verification.
        // For MVP we simulate a successful transaction and store a mock ref.
        $mockRef = 'MOCK-' . strtoupper(bin2hex(random_bytes(6)));
        $order->markPaid($order->payment_method, $mockRef);

        // Deliver purchased items — grant access to buyer via the polymorphic
        // FeasibilityPurchaseRequest table so the download route can verify.
        foreach ($order->items as $item) {
            if ($item->purchasable_type === FeasibilityStudy::class) {
                \App\Models\FeasibilityPurchaseRequest::create([
                    'study_id'      => $item->purchasable_id,
                    'user_id'       => $order->user_id,
                    'contact_name'  => $order->contact_name,
                    'contact_email' => $order->contact_email,
                    'contact_phone' => $order->contact_phone,
                    'amount'        => $item->subtotal,
                    'status'        => \App\Models\FeasibilityPurchaseRequest::STATUS_PAID,
                    'paid_at'       => now(),
                    'admin_notes'   => 'Auto-approved via order ' . $order->reference,
                ]);
            }
        }

        \App\Support\AdminNotifier::ping(
            'طلب جديد مدفوع 🛒',
            $order->contact_name . ' — ' . number_format((float) $order->total, 2) . ' ر.س',
            route('filament.admin.resources.orders.edit', ['record' => $order->id]),
            'heroicon-o-shopping-bag',
            'success'
        );

        $this->cart->clear();

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order): Response
    {
        abort_unless($order->status === Order::STATUS_PAID, 404);
        abort_unless($order->user_id === auth()->id() || auth()->user()?->role === 'admin', 403);

        return Inertia::render('Checkout/Success', [
            'order' => [
                'reference'    => $order->reference,
                'total'        => (float) $order->total,
                'contact_email'=> $order->contact_email,
                'items'        => $order->items->map(fn ($i) => [
                    'title'    => $i->title,
                    'subtotal' => (float) $i->subtotal,
                    // Signed URL — expires in 24h. Anyone sharing it after that
                    // will get a 403; buyer can always regenerate from their profile.
                    'download_url' => $i->purchasable_type === FeasibilityStudy::class
                        ? \Illuminate\Support\Facades\URL::temporarySignedRoute(
                            'feasibility.download',
                            now()->addHours(24),
                            ['feasibility' => $i->purchasable_id],
                          )
                        : null,
                ]),
            ],
        ]);
    }
}
