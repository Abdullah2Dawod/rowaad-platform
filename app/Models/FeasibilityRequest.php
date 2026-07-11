<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FeasibilityRequest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'goals'             => 'array',
        'study_types'       => 'array',
        'attachments'       => 'array',
        'needed_by'         => 'date',
        'estimated_budget'  => 'decimal:2',
        'quoted_price'      => 'decimal:2',
    ];

    // ─── Status flow ───
    public const STATUS_NEW          = 'new';
    public const STATUS_IN_REVIEW    = 'in_review';
    public const STATUS_QUOTED       = 'quoted';       // sent a price offer
    public const STATUS_ACCEPTED     = 'accepted';     // client accepted
    public const STATUS_IN_PROGRESS  = 'in_progress';  // team is working
    public const STATUS_DELIVERED    = 'delivered';
    public const STATUS_CLOSED       = 'closed';
    public const STATUS_REJECTED     = 'rejected';

    protected static function booted(): void
    {
        static::creating(function ($r) {
            if (empty($r->reference)) {
                $r->reference = 'FR-' . strtoupper(Str::random(6));
            }
        });
    }

    // Relationships
    public function user(): BelongsTo      { return $this->belongsTo(User::class); }
    public function assignee(): BelongsTo  { return $this->belongsTo(User::class, 'assigned_to'); }

    // Scopes
    public function scopePending($q) { return $q->whereIn('status', [self::STATUS_NEW, self::STATUS_IN_REVIEW]); }
    public function scopeActive($q)  { return $q->whereIn('status', [self::STATUS_ACCEPTED, self::STATUS_IN_PROGRESS]); }
}
