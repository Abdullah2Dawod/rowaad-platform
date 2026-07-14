<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeasibilityStudy extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_free'      => 'boolean',
        'is_featured'  => 'boolean',
        'price'        => 'decimal:2',
        'reviewed_at'  => 'datetime',
        'rich_content' => 'array',
    ];

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_HIDDEN   = 'hidden';

    // Relationships
    public function uploader(): BelongsTo       { return $this->belongsTo(User::class, 'user_id'); }
    public function specialization(): BelongsTo { return $this->belongsTo(Specialization::class); }
    public function purchases(): HasMany        { return $this->hasMany(FeasibilityPurchase::class); }
    public function reviewer(): BelongsTo       { return $this->belongsTo(User::class, 'reviewed_by'); }

    // Scopes
    public function scopePublic($q)   { return $q->where('status', self::STATUS_APPROVED); }
    public function scopePending($q)  { return $q->where('status', self::STATUS_PENDING); }
}
