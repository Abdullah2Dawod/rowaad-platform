<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentOpportunity extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'gallery'          => 'array',
        'highlights'       => 'array',
        'documents'        => 'array',
        'is_featured'      => 'boolean',
        'published_at'     => 'datetime',
        'deadline_at'      => 'datetime',
        'investment_min'   => 'decimal:2',
        'investment_max'   => 'decimal:2',
        'expected_roi'     => 'decimal:2',
        'rich_content'     => 'array',
    ];

    public const STATUS_OPEN     = 'open';
    public const STATUS_CLOSED   = 'closed';
    public const STATUS_REVIEW   = 'in_review';
    public const STATUS_DRAFT    = 'draft';

    public function applications(): HasMany
    {
        return $this->hasMany(InvestmentApplication::class, 'opportunity_id');
    }

    public function scopeOpen($q)     { return $q->where('status', self::STATUS_OPEN); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }
}
