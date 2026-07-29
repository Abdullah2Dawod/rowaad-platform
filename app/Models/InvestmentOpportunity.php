<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentOpportunity extends Model
{
    use \App\Traits\Reviewable;

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

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_CLOSED    = 'closed';

    public static array $STATUS_LABELS = [
        self::STATUS_DRAFT     => 'مسودة',
        self::STATUS_PUBLISHED => 'منشورة',
        self::STATUS_CLOSED    => 'مغلقة',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(InvestmentApplication::class, 'opportunity_id');
    }

    /** Visible on the external site: published (accepting applications) + closed (display-only). */
    public function scopeVisible($q)   { return $q->whereIn('status', [self::STATUS_PUBLISHED, self::STATUS_CLOSED]); }
    /** Accepting new applications. */
    public function scopePublished($q) { return $q->where('status', self::STATUS_PUBLISHED); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }
}
