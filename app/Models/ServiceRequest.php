<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ServiceRequest extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['budget' => 'decimal:2'];

    protected static function booted(): void
    {
        static::creating(function ($r) {
            if (empty($r->reference)) {
                $r->reference = 'SR-' . strtoupper(Str::random(6));
            }
        });
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
