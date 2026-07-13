<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultantReview extends Model
{
    protected $fillable = [
        'consultant_id', 'user_id', 'booking_id',
        'rating', 'comment', 'is_visible',
    ];

    protected $casts = [
        'rating'     => 'integer',
        'is_visible' => 'boolean',
    ];

    public function consultant(): BelongsTo { return $this->belongsTo(Consultant::class); }
    public function user(): BelongsTo       { return $this->belongsTo(User::class); }
    public function booking(): BelongsTo    { return $this->belongsTo(Booking::class); }

    protected static function booted(): void
    {
        static::saved(fn (self $r)   => static::recalcConsultantRating($r->consultant_id));
        static::deleted(fn (self $r) => static::recalcConsultantRating($r->consultant_id));
    }

    /**
     * Recompute the consultant's rating_avg + rating_count from all visible reviews.
     * Keeps the denormalized fields on the consultants table in sync so listings
     * and the public profile don't need extra joins.
     */
    public static function recalcConsultantRating(int $consultantId): void
    {
        $stats = static::query()
            ->where('consultant_id', $consultantId)
            ->where('is_visible', true)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
            ->first();

        Consultant::where('id', $consultantId)->update([
            'rating_avg'   => $stats?->avg_rating ? round((float) $stats->avg_rating, 2) : 0,
            'rating_count' => (int) ($stats?->total ?? 0),
        ]);
    }
}
