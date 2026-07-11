<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class InvestmentApplication extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'investment_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($app) {
            if (empty($app->reference)) {
                $app->reference = 'IN-' . strtoupper(Str::random(6));
            }
        });
    }

    public function opportunity(): BelongsTo { return $this->belongsTo(InvestmentOpportunity::class, 'opportunity_id'); }
    public function user(): BelongsTo        { return $this->belongsTo(User::class); }
}
