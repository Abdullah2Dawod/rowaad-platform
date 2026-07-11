<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /** Printable invoice for a paid booking — admin-only. */
    public function show(Booking $booking, Request $request): View
    {
        abort_unless($request->user()?->role === 'admin', 403);
        abort_unless(in_array($booking->status, [
            Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED,
        ]), 404);

        $booking->load(['user', 'consultant.user']);

        $base = (float) ($booking->consultant_share + $booking->platform_share);

        return view('invoices.show', [
            'b'            => $booking,
            'base'         => $base,
            'zakat'        => (float) $booking->zakat_amount,
            'total'        => (float) $booking->amount,
            'siteName'     => SiteSetting::get('site.name_ar', 'رواد بلا حدود'),
            'siteAddress'  => SiteSetting::get('site.contact_address'),
            'siteEmail'    => SiteSetting::get('site.contact_email'),
            'sitePhone'    => SiteSetting::get('site.contact_phone'),
            'logo'         => SiteSetting::get('site.logo'),
        ]);
    }
}
