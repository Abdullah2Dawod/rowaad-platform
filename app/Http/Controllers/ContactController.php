<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:150'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        // Reuse ServiceRequest as a generic inbound message store
        try {
            ServiceRequest::create([
                'user_id'       => $request->user()?->id,
                'service_slug'  => 'contact',
                'service_title' => 'رسالة تواصل: ' . ($data['subject'] ?? ''),
                'company_name'  => '—',
                'contact_name'  => $data['name'],
                'contact_email' => $data['email'],
                'contact_phone' => $data['phone'] ?? '—',
                'project_brief' => $data['message'],
                'status'        => 'new',
            ]);
        } catch (\Throwable $e) {
            \Log::warning('[Contact] save failed: ' . $e->getMessage());
        }

        return back()->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
    }
}
