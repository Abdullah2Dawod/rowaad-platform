<?php

namespace App\Http\Controllers;

use App\Models\FeasibilityRequest;
use App\Models\User;
use App\Notifications\NewFeasibilityRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class FeasibilityRequestController extends Controller
{
    /**
     * Public form — clients submit a custom feasibility study request.
     */
    public function create(): Response
    {
        return Inertia::render('Feasibility/RequestCustom', [
            'sectors' => [
                'العقارات', 'الصناعة', 'التجزئة', 'الصحة', 'التعليم', 'التقنية',
                'السياحة', 'الزراعة', 'الأغذية والمشروبات', 'الطاقة المتجدّدة',
                'الترفيه', 'اللوجستيات', 'الخدمات المالية', 'أخرى',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_title'     => ['required', 'string', 'max:150'],
            'sector'            => ['required', 'string', 'max:100'],
            'sub_sector'        => ['nullable', 'string', 'max:100'],
            'city'              => ['nullable', 'string', 'max:80'],
            'country'           => ['nullable', 'string', 'max:80'],

            'idea_description'  => ['required', 'string', 'min:80', 'max:5000'],
            'goals'             => ['nullable', 'array'],
            'goals.*'           => ['string', 'in:funding,feasibility,partners,ipo,expansion'],
            'study_types'       => ['required', 'array', 'min:1'],
            'study_types.*'     => ['string', 'in:market,technical,financial,environmental,legal'],

            'estimated_budget'  => ['nullable', 'numeric', 'min:0', 'max:1000000000'],
            'funding_source'    => ['nullable', 'string', 'max:60'],

            'urgency'           => ['required', 'string', 'in:urgent,normal,flexible'],
            'needed_by'         => ['nullable', 'date', 'after:today'],

            'company_name'      => ['nullable', 'string', 'max:150'],
            'company_size'      => ['nullable', 'string', 'max:40'],
            'contact_name'      => ['required', 'string', 'max:120'],
            'contact_email'     => ['required', 'email', 'max:150'],
            'contact_phone'     => ['required', 'string', 'max:30'],

            'attachments'       => ['nullable', 'array', 'max:5'],
            'attachments.*'     => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ]);

        // Handle attachments
        $files = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $files[] = [
                    'title' => $file->getClientOriginalName(),
                    'path'  => $file->store('feasibility-requests', 'public'),
                ];
            }
        }

        $req = FeasibilityRequest::create([
            'user_id' => $request->user()?->id,
            ...$data,
            'attachments' => $files,
            'status'      => FeasibilityRequest::STATUS_NEW,
        ]);

        // Notify admins
        foreach (User::where('role', 'admin')->get() as $admin) {
            try { $admin->notify(new NewFeasibilityRequest($req)); }
            catch (\Throwable $e) { \Log::warning('[Feasibility request mail] ' . $e->getMessage()); }
        }

        return redirect()->route('feasibility.index')
            ->with('success', "تم استلام طلبك (المرجع: {$req->reference}). سيتواصل فريقنا خلال 24 ساعة عمل.");
    }
}
