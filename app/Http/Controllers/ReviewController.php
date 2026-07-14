<?php

namespace App\Http\Controllers;

use App\Models\FeasibilityStudy;
use App\Models\InvestmentOpportunity;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Polymorphic reviews API for services / feasibility studies / investments.
 * Consultants keep their existing ConsultantReview flow (already wired).
 */
class ReviewController extends Controller
{
    private const TYPES = [
        'service'      => Service::class,
        'feasibility'  => FeasibilityStudy::class,
        'investment'   => InvestmentOpportunity::class,
    ];

    public function index(string $type, int $id): JsonResponse
    {
        $modelClass = self::TYPES[$type] ?? abort(404);
        $modelClass::findOrFail($id);

        $reviews = Review::where('reviewable_type', $modelClass)
            ->where('reviewable_id', $id)
            ->where('is_visible', true)
            ->with('user:id,name')
            ->latest()->take(50)->get()
            ->map(fn ($r) => [
                'id'        => $r->id,
                'user_name' => $r->user?->name ?? 'مستخدم',
                'rating'    => $r->rating,
                'comment'   => $r->comment,
                'date'      => $r->created_at->diffForHumans(),
            ]);

        return response()->json(['reviews' => $reviews]);
    }

    public function myReview(Request $request, string $type, int $id): JsonResponse
    {
        $modelClass = self::TYPES[$type] ?? abort(404);
        $modelClass::findOrFail($id);

        $review = Review::where('reviewable_type', $modelClass)
            ->where('reviewable_id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        return response()->json(['review' => $review ? [
            'rating'  => $review->rating,
            'comment' => $review->comment,
        ] : null]);
    }

    public function store(Request $request, string $type, int $id): JsonResponse
    {
        $modelClass = self::TYPES[$type] ?? abort(404);
        $modelClass::findOrFail($id);

        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $review = Review::updateOrCreate(
            [
                'reviewable_type' => $modelClass,
                'reviewable_id'   => $id,
                'user_id'         => $request->user()->id,
            ],
            [
                'rating'     => $data['rating'],
                'comment'    => $data['comment'] ?? null,
                'is_visible' => true,
            ]
        );

        return response()->json([
            'ok'      => true,
            'message' => 'شكراً! تم تسجيل تقييمك.',
            'rating'  => $review->rating,
        ]);
    }
}
