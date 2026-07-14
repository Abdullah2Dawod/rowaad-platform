<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConsultantController extends Controller
{
    /**
     * Public directory — /consultants
     */
    public function index(Request $request): Response
    {
        $query = Consultant::approved()
            ->with(['user:id,name', 'specialization:id,slug,name_ar,name_en,icon']);

        // Filter by specialization slug
        if ($slug = $request->query('specialization')) {
            $query->whereHas('specialization', fn ($q) => $q->where('slug', $slug));
        }

        // Search
        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name_ar', 'like', "%{$search}%")
                  ->orWhere('full_name_en', 'like', "%{$search}%")
                  ->orWhere('professional_title', 'like', "%{$search}%")
                  ->orWhere('bio_ar', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->query('sort', 'featured');
        match ($sort) {
            'rating'  => $query->orderByDesc('rating_avg')->orderByDesc('rating_count'),
            'newest'  => $query->orderByDesc('approved_at'),
            'price'   => $query->orderBy('hourly_rate'),
            default   => $query->orderByDesc('is_featured')->orderByDesc('rating_avg'),
        };

        $consultants = $query->paginate(12)->withQueryString();

        return Inertia::render('Consultants', [
            'consultants'    => $consultants->through(fn ($c) => [
                'id'                 => $c->id,
                'name'               => $c->full_name_ar ?: $c->user->name,
                'title'              => $c->professional_title,
                'city'               => $c->city,
                'avatar'             => $c->avatar_url,
                'bio'                => \Illuminate\Support\Str::limit($c->bio_ar, 140),
                'specialization'     => $c->specialization?->only(['slug','name_ar','name_en','icon']),
                'years_experience'   => $c->years_experience,
                'hourly_rate'        => (float) $c->hourly_rate,
                'rating_avg'         => (float) $c->rating_avg,
                'rating_count'       => $c->rating_count,
                'sessions_completed' => $c->sessions_completed,
                'is_featured'        => $c->is_featured,
                'languages'          => $c->languages ?? [],
            ]),
            'specializations' => Specialization::active()->orderBy('sort_order')->get(['slug','name_ar','name_en','icon']),
            'filters'         => [
                'specialization' => $request->query('specialization'),
                'q'              => $request->query('q'),
                'sort'           => $sort,
            ],
        ]);
    }

    /**
     * Public consultant profile — /consultants/{id}
     */
    public function show(Consultant $consultant): Response
    {
        abort_unless($consultant->isApproved(), 404);

        $consultant->load(['user:id,name', 'specialization']);

        return Inertia::render('ConsultantProfile', [
            'consultant' => [
                'id'                  => $consultant->id,
                'name'                => $consultant->full_name_ar ?: $consultant->user->name,
                'name_en'             => $consultant->full_name_en,
                'title'               => $consultant->professional_title,
                'city'                => $consultant->city,
                'country'             => $consultant->country,
                'nationality'         => $consultant->nationality,
                'avatar'              => $consultant->avatar_url,
                'bio'                 => $consultant->bio_ar,
                'bio_en'              => $consultant->bio_en,
                'specialization'      => $consultant->specialization?->only(['slug','name_ar','name_en','icon']),
                'services'            => $consultant->services ?? [],
                'years_experience'    => $consultant->years_experience,
                'hourly_rate'         => (float) $consultant->hourly_rate,
                'session_duration_min' => $consultant->session_duration_min,
                'languages'           => $consultant->languages ?? [],
                'rating_avg'          => (float) $consultant->rating_avg,
                'rating_count'        => $consultant->rating_count,
                'sessions_completed'  => $consultant->sessions_completed,
                'is_featured'         => $consultant->is_featured,
                'approved_at'         => $consultant->approved_at,
                'rich_content'        => $consultant->rich_content,
            ],
            'related' => Consultant::approved()
                ->where('id', '!=', $consultant->id)
                ->where('specialization_id', $consultant->specialization_id)
                ->limit(3)
                ->get()
                ->map(fn ($c) => [
                    'id'         => $c->id,
                    'name'       => $c->full_name_ar,
                    'title'      => $c->professional_title,
                    'avatar'     => $c->avatar_url,
                    'rating_avg' => (float) $c->rating_avg,
                ]),
        ]);
    }
}
