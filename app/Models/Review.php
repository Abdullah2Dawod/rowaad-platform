<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class Review extends Model
{
    protected $fillable = ['reviewable_type', 'reviewable_id', 'user_id', 'rating', 'comment', 'is_visible'];

    protected $casts = [
        'rating'     => 'integer',
        'is_visible' => 'boolean',
    ];

    public function reviewable(): MorphTo { return $this->morphTo(); }
    public function user(): BelongsTo     { return $this->belongsTo(User::class); }

    protected static function booted(): void
    {
        static::saved(fn (Review $r)   => self::recalcTarget($r->reviewable_type, $r->reviewable_id));
        static::deleted(fn (Review $r) => self::recalcTarget($r->reviewable_type, $r->reviewable_id));
    }

    /** Recompute rating_avg + rating_count on the target row. */
    public static function recalcTarget(string $type, int $id): void
    {
        $stats = static::query()
            ->where('reviewable_type', $type)
            ->where('reviewable_id', $id)
            ->where('is_visible', true)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
            ->first();

        $model = new $type;
        $table = $model->getTable();

        DB::table($table)->where('id', $id)->update([
            'rating_avg'   => $stats?->avg_rating ? round((float) $stats->avg_rating, 2) : 0,
            'rating_count' => (int) ($stats?->total ?? 0),
        ]);
    }
}
