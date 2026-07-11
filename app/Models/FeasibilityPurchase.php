<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FeasibilityPurchase extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount'         => 'decimal:2',
        'base_amount'    => 'decimal:2',
        'zakat_amount'   => 'decimal:2',
        'platform_share' => 'decimal:2',
        'paid_at'        => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($p) {
            if (empty($p->reference)) {
                $p->reference = 'FS-' . strtoupper(Str::random(6));
            }
        });
    }

    public function user(): BelongsTo    { return $this->belongsTo(User::class); }
    public function study(): BelongsTo   { return $this->belongsTo(FeasibilityStudy::class, 'feasibility_study_id'); }
}
