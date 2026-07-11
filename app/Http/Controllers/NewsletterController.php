<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use App\Notifications\NewsletterConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsletterController extends Controller
{
    /**
     * Subscribe (or resend confirmation for existing pending).
     * Public — anyone can subscribe from footer/homepage.
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email'       => ['required', 'email', 'max:150'],
            'name'        => ['nullable', 'string', 'max:120'],
            'preferences' => ['nullable', 'array'],
            'preferences.*' => ['string', 'in:weekly,daily,offers,insights'],
            'source'      => ['nullable', 'string', 'max:40'],
        ]);

        $sub = NewsletterSubscriber::firstOrNew(['email' => $data['email']]);
        $sub->name        = $data['name']        ?? $sub->name;
        $sub->preferences = $data['preferences'] ?? $sub->preferences ?? ['weekly'];
        $sub->source      = $data['source']      ?? $sub->source ?? 'footer';
        $sub->locale      = app()->getLocale();

        if ($sub->exists && $sub->isConfirmed() && ! $sub->unsubscribed_at) {
            return back()->with('success', 'أنت مشترك بالفعل — شكراً لثقتك.');
        }

        $sub->unsubscribed_at = null;
        $sub->save();

        // Send confirmation email
        try {
            $sub->notify(new NewsletterConfirmation($sub));
        } catch (\Throwable $e) {
            \Log::warning('[Newsletter confirm mail] ' . $e->getMessage());
        }

        return back()->with('success', 'شكراً! أرسلنا رابط تأكيد لبريدك — تحقّق من صندوق الوارد.');
    }

    /**
     * Confirm subscription via emailed token.
     */
    public function confirm(string $token): Response
    {
        $sub = NewsletterSubscriber::where('confirm_token', $token)->firstOrFail();

        if (! $sub->isConfirmed()) {
            $sub->update(['confirmed_at' => now()]);
        }

        return Inertia::render('Newsletter/Confirmed', [
            'email' => $sub->email,
        ]);
    }

    /**
     * One-click unsubscribe.
     */
    public function unsubscribe(string $token): Response
    {
        $sub = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();
        $sub->update(['unsubscribed_at' => now()]);

        return Inertia::render('Newsletter/Unsubscribed', [
            'email' => $sub->email,
        ]);
    }
}
