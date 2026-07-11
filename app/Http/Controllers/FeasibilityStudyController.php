<?php

namespace App\Http\Controllers;

use App\Models\FeasibilityStudy;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class FeasibilityStudyController extends Controller
{
    /**
     * Public marketplace.
     */
    public function index(Request $request): Response
    {
        $query = FeasibilityStudy::public()->with('specialization:id,slug,name_ar,icon');

        if ($slug = $request->query('specialization')) {
            $query->whereHas('specialization', fn ($q) => $q->where('slug', $slug));
        }
        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('sector', 'like', "%{$search}%");
            });
        }

        $studies = $query->orderByDesc('is_featured')->latest()->paginate(12)->withQueryString();

        return Inertia::render('Feasibility/Index', [
            'studies' => $studies->through(fn (FeasibilityStudy $s) => [
                'id'           => $s->id,
                'slug'         => $s->slug,
                'title'        => $s->title,
                'excerpt'      => Str::limit($s->excerpt, 140),
                'cover_image'  => $s->cover_image,
                'price'        => (float) $s->price,
                'is_free'      => $s->is_free,
                'is_featured'  => $s->is_featured,
                'sector'       => $s->sector,
                'pages_count'  => $s->pages_count,
                'purchases_count' => $s->purchases_count,
                'specialization' => $s->specialization?->only(['slug','name_ar','icon']),
            ]),
            'specializations' => Specialization::active()->orderBy('sort_order')->get(['slug','name_ar','icon']),
            'filters' => [
                'q'              => $request->query('q'),
                'specialization' => $request->query('specialization'),
            ],
        ]);
    }

    /**
     * Individual study.
     */
    public function show(FeasibilityStudy $feasibility): Response
    {
        abort_unless($feasibility->status === FeasibilityStudy::STATUS_APPROVED, 404);
        $feasibility->increment('views_count');
        $feasibility->load('specialization', 'uploader:id,name');

        return Inertia::render('Feasibility/Show', [
            'study' => [
                'id'            => $feasibility->id,
                'title'         => $feasibility->title,
                'excerpt'       => $feasibility->excerpt,
                'description'   => $feasibility->description,
                'cover_image'   => $feasibility->cover_image,
                'price'         => (float) $feasibility->price,
                'is_free'       => $feasibility->is_free,
                'sector'        => $feasibility->sector,
                'pages_count'   => $feasibility->pages_count,
                'language'      => $feasibility->language,
                'views_count'   => $feasibility->views_count,
                'purchases_count' => $feasibility->purchases_count,
                'specialization' => $feasibility->specialization?->only(['slug','name_ar','icon']),
            ],
        ]);
    }

    /**
     * Upload form — a user submits their own study for review.
     */
    public function createForm(): Response
    {
        return Inertia::render('Feasibility/Submit', [
            'specializations' => Specialization::active()->orderBy('sort_order')->get(['id','name_ar']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:150'],
            'excerpt'           => ['required', 'string', 'min:40', 'max:400'],
            'description'       => ['required', 'string', 'min:200', 'max:8000'],
            'sector'            => ['required', 'string', 'max:80'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'pages_count'       => ['nullable', 'integer', 'min:1', 'max:5000'],
            'price'             => ['required', 'numeric', 'min:0', 'max:50000'],
            'cover_image'       => ['nullable', 'image', 'max:4096'],
            'file'              => ['required', 'file', 'mimes:pdf', 'max:30720'], // 30MB
        ]);

        $slug = Str::slug($data['title']) . '-' . strtolower(Str::random(4));

        $paths = [];
        if ($f = $request->file('cover_image')) {
            $paths['cover_image'] = $f->store('feasibility/covers', 'public');
        }
        $paths['file_path'] = $request->file('file')->store('feasibility/files', 'public');

        FeasibilityStudy::create([
            'user_id'           => $request->user()->id,
            'specialization_id' => $data['specialization_id'],
            'title'             => $data['title'],
            'slug'              => $slug,
            'excerpt'           => $data['excerpt'],
            'description'       => $data['description'],
            'sector'            => $data['sector'],
            'pages_count'       => $data['pages_count'] ?? null,
            'price'             => $data['price'],
            'is_free'           => (float) $data['price'] === 0.0,
            'language'          => 'ar',
            'status'            => FeasibilityStudy::STATUS_PENDING,
            ...$paths,
        ]);

        return redirect()->route('feasibility.index')
            ->with('success', 'تم استلام دراستك وستُراجَع خلال 24 ساعة.');
    }
}
