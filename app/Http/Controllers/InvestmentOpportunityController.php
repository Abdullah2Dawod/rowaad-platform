<?php

namespace App\Http\Controllers;

use App\Models\InvestmentApplication;
use App\Models\InvestmentOpportunity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class InvestmentOpportunityController extends Controller
{
    public function index(Request $request): Response
    {
        $query = InvestmentOpportunity::open();

        if ($sector = $request->query('sector')) {
            $query->where('sector', $sector);
        }
        if ($risk = $request->query('risk')) {
            $query->where('risk_level', $risk);
        }
        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('sector', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $sort = $request->query('sort', 'featured');
        match ($sort) {
            'roi'      => $query->orderByDesc('expected_roi'),
            'amount'   => $query->orderBy('investment_min'),
            'newest'   => $query->orderByDesc('published_at'),
            'deadline' => $query->orderBy('deadline_at'),
            default    => $query->orderByDesc('is_featured')->orderByDesc('published_at'),
        };

        return Inertia::render('Investment/Index', [
            'opportunities' => $query->paginate(9)->withQueryString()->through(fn (InvestmentOpportunity $o) => [
                'id'              => $o->id,
                'slug'            => $o->slug,
                'title'           => $o->title,
                'subtitle'        => $o->subtitle,
                'summary'         => Str::limit($o->summary, 150),
                'sector'          => $o->sector,
                'city'            => $o->city,
                'region'          => $o->region,
                'investment_min'  => (float) $o->investment_min,
                'investment_max'  => (float) $o->investment_max,
                'expected_roi'    => (float) $o->expected_roi,
                'payback_months'  => $o->payback_months,
                'duration_years'  => $o->duration_years,
                'risk_level'      => $o->risk_level,
                'cover_image'     => $o->cover_image,
                'is_featured'     => $o->is_featured,
                'source'          => $o->source,
                'source_name'     => $o->source_name,
                'views_count'     => $o->views_count,
                'applications_count' => $o->applications_count,
                'deadline_at'     => $o->deadline_at?->format('Y-m-d'),
            ]),
            'filters' => [
                'q'      => $request->query('q'),
                'sector' => $request->query('sector'),
                'risk'   => $request->query('risk'),
                'sort'   => $sort,
            ],
            'sectors' => InvestmentOpportunity::open()
                ->select('sector')->distinct()->orderBy('sector')->pluck('sector'),
            'stats'   => [
                'total_open'   => InvestmentOpportunity::open()->count(),
                'total_value'  => (float) InvestmentOpportunity::open()->sum('investment_min'),
                'sectors'      => InvestmentOpportunity::open()->distinct('sector')->count('sector'),
            ],
        ]);
    }

    public function show(InvestmentOpportunity $investment): Response
    {
        abort_unless($investment->status === InvestmentOpportunity::STATUS_OPEN, 404);
        $investment->increment('views_count');

        return Inertia::render('Investment/Show', [
            'opportunity' => [
                'id'              => $investment->id,
                'slug'            => $investment->slug,
                'title'           => $investment->title,
                'subtitle'        => $investment->subtitle,
                'summary'         => $investment->summary,
                'description'     => $investment->description,
                'sector'          => $investment->sector,
                'city'            => $investment->city,
                'region'          => $investment->region,
                'investment_min'  => (float) $investment->investment_min,
                'investment_max'  => (float) $investment->investment_max,
                'expected_roi'    => (float) $investment->expected_roi,
                'payback_months'  => $investment->payback_months,
                'duration_years'  => $investment->duration_years,
                'risk_level'      => $investment->risk_level,
                'cover_image'     => $investment->cover_image,
                'gallery'         => $investment->gallery ?? [],
                'highlights'      => $investment->highlights ?? [],
                'documents'       => $investment->documents ?? [],
                'source'          => $investment->source,
                'source_name'     => $investment->source_name,
                'source_url'      => $investment->source_url,
                'is_featured'     => $investment->is_featured,
                'views_count'     => $investment->views_count,
                'applications_count' => $investment->applications_count,
                'deadline_at'     => $investment->deadline_at?->format('Y-m-d'),
                'published_at'    => $investment->published_at?->format('Y-m-d'),
            ],
            'related' => InvestmentOpportunity::open()
                ->where('id', '!=', $investment->id)
                ->where('sector', $investment->sector)
                ->limit(3)
                ->get()
                ->map(fn ($o) => [
                    'id'    => $o->id,
                    'slug'  => $o->slug,
                    'title' => $o->title,
                    'cover_image' => $o->cover_image,
                    'expected_roi' => (float) $o->expected_roi,
                    'sector' => $o->sector,
                ]),
        ]);
    }

    public function apply(Request $request, InvestmentOpportunity $investment): RedirectResponse
    {
        abort_unless($investment->status === InvestmentOpportunity::STATUS_OPEN, 404);

        $data = $request->validate([
            'company_name'      => ['required', 'string', 'max:150'],
            'contact_name'      => ['required', 'string', 'max:120'],
            'contact_email'     => ['required', 'email', 'max:150'],
            'contact_phone'     => ['required', 'string', 'max:30'],
            'investment_amount' => ['nullable', 'numeric', 'min:0'],
            'message'           => ['nullable', 'string', 'max:2000'],
        ]);

        InvestmentApplication::create([
            'opportunity_id'    => $investment->id,
            'user_id'           => $request->user()?->id,
            'company_name'      => $data['company_name'],
            'contact_name'      => $data['contact_name'],
            'contact_email'     => $data['contact_email'],
            'contact_phone'     => $data['contact_phone'],
            'investment_amount' => $data['investment_amount'] ?? null,
            'message'           => $data['message'] ?? null,
            'status'            => 'new',
        ]);

        $investment->increment('applications_count');

        return back()->with('success', 'تم استلام طلبك بنجاح. سيتواصل معك فريق رواد خلال 48 ساعة.');
    }
}
