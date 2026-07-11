<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WithdrawalRequest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount'       => 'decimal:2',
        'processed_at' => 'datetime',
        'paid_at'      => 'datetime',
    ];

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PAID     = 'paid';
    public const STATUS_REJECTED = 'rejected';

    protected static function booted(): void
    {
        static::creating(function (self $r) {
            if (empty($r->reference)) {
                $r->reference = 'WD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function consultant(): BelongsTo { return $this->belongsTo(Consultant::class); }
    public function processor(): BelongsTo  { return $this->belongsTo(User::class, 'processed_by'); }

    public function scopePending($q)  { return $q->where('status', self::STATUS_PENDING); }
    public function scopeApproved($q) { return $q->where('status', self::STATUS_APPROVED); }
    public function scopePaid($q)     { return $q->where('status', self::STATUS_PAID); }

    /** Amounts that reduce the consultant's available balance (pending/approved/paid — everything except rejected). */
    public function scopeCommitted($q)
    {
        return $q->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_PAID]);
    }
}
