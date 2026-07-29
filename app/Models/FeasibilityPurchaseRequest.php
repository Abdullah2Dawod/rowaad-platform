<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FeasibilityPurchaseRequest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount'        => 'decimal:2',
        'paid_at'       => 'datetime',
        'downloaded_at' => 'datetime',
    ];

    public const STATUS_NEW       = 'new';
    public const STATUS_PAID      = 'paid';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    protected static function booted(): void
    {
        static::creating(function ($r) {
            if (empty($r->reference)) {
                $r->reference = 'FP-' . strtoupper(Str::random(6));
            }
        });
    }

    public function study(): BelongsTo { return $this->belongsTo(FeasibilityStudy::class, 'study_id'); }
    public function user(): BelongsTo  { return $this->belongsTo(User::class); }
}
