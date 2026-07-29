<?php

namespace App\Http\Controllers;

use App\Models\FeasibilityPurchaseRequest;
use App\Models\FeasibilityStudy;
use App\Models\Specialization;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
                'cover_image'  => \App\Support\Media::url($s->cover_image, \App\Support\Media::feasibilityPlaceholder()),
                'price'        => (float) $s->price,
                'is_free'      => $s->is_free,
                'is_featured'  => $s->is_featured,
                'sector'       => $s->sector,
                'pages_count'  => $s->pages_count,
                'purchases_count' => $s->purchases_count,
                'source'       => $s->user_id ? 'user' : 'admin',
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
                'cover_image'   => \App\Support\Media::url($feasibility->cover_image, \App\Support\Media::feasibilityPlaceholder()),
                'price'         => (float) $feasibility->price,
                'is_free'       => $feasibility->is_free,
                'sector'        => $feasibility->sector,
                'pages_count'   => $feasibility->pages_count,
                'language'      => $feasibility->language,
                'views_count'   => $feasibility->views_count,
                'purchases_count' => $feasibility->purchases_count,
                'source'        => $feasibility->user_id ? 'user' : 'admin',
                'author'        => $feasibility->user_id ? $feasibility->uploader?->name : 'رواد',
                'rich_content'  => $feasibility->rich_content,
                'rating_avg'    => (float) ($feasibility->rating_avg ?? 0),
                'rating_count'  => (int)   ($feasibility->rating_count ?? 0),
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

        $study = FeasibilityStudy::create([
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

        // Notify all admins of the new pending submission
        User::where('role', 'admin')->get()->each(function ($admin) use ($study) {
            FilamentNotification::make()
                ->title('دراسة جدوى جديدة بانتظار المراجعة')
                ->body('قام مستخدم بإرسال دراسة: ' . $study->title)
                ->icon('heroicon-o-document-magnifying-glass')
                ->color('warning')
                ->actions([
                    \Filament\Notifications\Actions\Action::make('review')
                        ->label('مراجعة')
                        ->url('/admin/feasibility-studies/' . $study->id . '/edit'),
                ])
                ->sendToDatabase($admin);
        });

        return redirect()->route('feasibility.index')
            ->with('success', 'تم استلام دراستك وستُراجَع خلال 24 ساعة.');
    }

    /**
     * Download / purchase entrypoint used by the "شراء وتحميل" button.
     * — Free studies: streams the file immediately (increments purchases_count once per session).
     * — Paid studies: rejects with 402 if no paid purchase found for the current user/email.
     */
    public function download(FeasibilityStudy $feasibility, Request $request)
    {
        abort_unless($feasibility->status === FeasibilityStudy::STATUS_APPROVED, 404);
        abort_unless($feasibility->file_path, 404, 'ملف الدراسة غير مرفوع بعد.');
        abort_unless(Storage::disk('public')->exists($feasibility->file_path), 404, 'الملف مفقود من التخزين.');

        // Free download: always allowed
        if ($feasibility->is_free) {
            $this->markDownloaded($feasibility, $request);
            return $this->streamStudyFile($feasibility);
        }

        // Paid: must have an approved purchase record for this study + user
        $userId = $request->user()?->id;
        $email  = $request->user()?->email;

        $hasPaid = FeasibilityPurchaseRequest::query()
            ->where('study_id', $feasibility->id)
            ->where('status', FeasibilityPurchaseRequest::STATUS_PAID)
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when(! $userId && $email, fn ($q) => $q->where('contact_email', $email))
            ->exists();

        if (! $hasPaid) {
            return back()->with('error', 'لم يتم العثور على عملية دفع مكتملة لهذه الدراسة. يرجى إتمام الشراء أولاً.');
        }

        $this->markDownloaded($feasibility, $request);
        return $this->streamStudyFile($feasibility);
    }

    /**
     * Purchase request — user submits contact info; admin approves via Filament.
     * On approval the user receives a bell notification + email with the download link.
     */
    public function purchase(Request $request, FeasibilityStudy $feasibility): RedirectResponse
    {
        abort_unless($feasibility->status === FeasibilityStudy::STATUS_APPROVED, 404);
        abort_if($feasibility->is_free, 403, 'الدراسة مجانية — استخدم زر التحميل المباشر.');

        $data = $request->validate([
            'contact_name'  => ['required', 'string', 'max:120'],
            'contact_email' => ['required', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $purchase = FeasibilityPurchaseRequest::create([
            'study_id'      => $feasibility->id,
            'user_id'       => $request->user()?->id,
            'contact_name'  => $data['contact_name'],
            'contact_email' => $data['contact_email'],
            'contact_phone' => $data['contact_phone'] ?? null,
            'amount'        => $feasibility->price,
            'status'        => FeasibilityPurchaseRequest::STATUS_NEW,
        ]);

        \App\Support\AdminNotifier::ping(
            'طلب شراء دراسة جدوى 💳',
            $data['contact_name'] . ' — ' . $feasibility->title . ' (' . number_format((float) $feasibility->price, 0) . ' ر.س)',
            null,
            'heroicon-o-shopping-cart',
            'success'
        );

        return back()->with('success', "تم استلام طلب الشراء (المرجع: {$purchase->reference}). سيتواصل معك فريقنا خلال ساعات لإتمام الدفع وإرسال رابط التحميل.");
    }

    protected function streamStudyFile(FeasibilityStudy $s): StreamedResponse
    {
        $filename = Str::slug($s->title, '-', null) . '.pdf';
        return Storage::disk('public')->download($s->file_path, $filename);
    }

    protected function markDownloaded(FeasibilityStudy $s, Request $request): void
    {
        $key = 'study_dl_' . $s->id;
        if (! $request->session()->get($key)) {
            $s->increment('purchases_count');
            $request->session()->put($key, now()->timestamp);
        }
    }
}
