<?php

namespace App\Http\Controllers;

use App\Models\FeasibilityStudy;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return back()->with('success', 'تم إزالة العنصر من السلة.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();
        return back()->with('success', 'تم إفراغ السلة.');
    }
}
