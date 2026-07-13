<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\ConsultantReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsultantReviewController extends Controller
{
    /**
     * Submit / update a rating for a consultant. Any authenticated user can rate.
     * Enforces one review per (consultant, user) via DB unique constraint.
     */
    public function store(Request $request, Consultant $consultant): JsonResponse
    {
        abort_unless($consultant->isApproved(), 404);
        $user = $request->user();
        abort_unless($user, 401, 'يجب تسجيل الدخول لتقييم المستشار.');
        abort_if($user->id === $consultant->user_id, 403, 'لا يمكنك تقييم نفسك.');

        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review = ConsultantReview::updateOrCreate(
            ['consultant_id' => $consultant->id, 'user_id' => $user->id],
            [
                'rating'     => $data['rating'],
                'comment'    => $data['comment'] ?? null,
                'is_visible' => true,
            ]
        );

        // Recalc happens automatically via model's `saved` hook
        $consultant->refresh();

        return response()->json([
            'ok'      => true,
            'review'  => [
                'rating'  => $review->rating,
                'comment' => $review->comment,
            ],
            'consultant' => [
                'rating_avg'   => (float) $consultant->rating_avg,
                'rating_count' => $consultant->rating_count,
            ],
        ]);
    }

    /**
     * Return public reviews list for a consultant (paginated).
     */
    public function index(Consultant $consultant): JsonResponse
    {
        abort_unless($consultant->isApproved(), 404);

        $reviews = ConsultantReview::query()
            ->where('consultant_id', $consultant->id)
            ->where('is_visible', true)
            ->with('user:id,name,avatar')
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn ($r) => [
                'id'      => $r->id,
                'rating'  => $r->rating,
                'comment' => $r->comment,
                'user'    => [
                    'name'   => $r->user?->name,
                    'avatar' => $r->user?->avatarUrl(),
                ],
                'created_at' => $r->created_at->diffForHumans(),
            ]);

        return response()->json([
            'reviews'      => $reviews,
            'rating_avg'   => (float) $consultant->rating_avg,
            'rating_count' => $consultant->rating_count,
        ]);
    }

    /**
     * Return the current user's existing review for this consultant (if any).
     */
    public function myReview(Request $request, Consultant $consultant): JsonResponse
    {
        $user = $request->user();
        abort_unless($user, 401);

        $review = ConsultantReview::where('consultant_id', $consultant->id)
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'review' => $review ? [
                'rating'  => $review->rating,
                'comment' => $review->comment,
            ] : null,
        ]);
    }
}
