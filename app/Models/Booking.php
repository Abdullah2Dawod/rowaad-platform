<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'preferred_date' => 'date',
        'paid_at'        => 'datetime',
        'confirmed_at'   => 'datetime',
        'completed_at'   => 'datetime',
        'amount'           => 'decimal:2',
        'consultant_share' => 'decimal:2',
        'platform_share'   => 'decimal:2',
        'zakat_amount'     => 'decimal:2',
    ];

    // === Status constants ===
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID            = 'paid';
    public const STATUS_CONFIRMED       = 'confirmed';
    public const STATUS_CANCELLED       = 'cancelled';
    public const STATUS_COMPLETED       = 'completed';

    /**
     * Duration → hourly-rate multiplier. Non-linear (like taarfu.com):
     * longer sessions cost more overall but LESS per minute — reward loyalty.
     *
     *   30 min → 0.60 × hourly   (short intro)
     *   45 min → 0.80 × hourly
     *   60 min → 1.00 × hourly   (base)
     *   90 min → 1.35 × hourly   (not 1.5 — small discount)
     *  120 min → 1.65 × hourly   (not 2.0 — larger discount)
     *
     * Example with 300 SAR/hour:
     *   30m = 180  ·  45m = 240  ·  60m = 300  ·  90m = 405  ·  120m = 495
     */
    public const DURATION_MULTIPLIERS = [
        30  => 0.60,
        45  => 0.80,
        60  => 1.00,
        90  => 1.35,
        120 => 1.65,
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $b) {
            if (empty($b->reference)) {
                $b->reference = 'BK-' . strtoupper(Str::random(6));
            }
        });
    }

    // Relationships
    public function user(): BelongsTo       { return $this->belongsTo(User::class); }
    public function consultant(): BelongsTo { return $this->belongsTo(Consultant::class); }
    public function confirmer(): BelongsTo  { return $this->belongsTo(User::class, 'confirmed_by'); }

    // Scopes
    public function scopePaid($q)      { return $q->where('status', self::STATUS_PAID); }
    public function scopeConfirmed($q) { return $q->where('status', self::STATUS_CONFIRMED); }
    public function scopeUpcoming($q)  { return $q->whereIn('status', [self::STATUS_PAID, self::STATUS_CONFIRMED]); }
    public function scopeRevenue($q)   { return $q->whereIn('status', [self::STATUS_PAID, self::STATUS_CONFIRMED, self::STATUS_COMPLETED]); }

    // Helpers
    public function isPaid(): bool      { return in_array($this->status, [self::STATUS_PAID, self::STATUS_CONFIRMED, self::STATUS_COMPLETED]); }
    public function isConfirmed(): bool { return $this->status === self::STATUS_CONFIRMED; }

    /** Full session start as a Carbon datetime (Asia/Riyadh implicit via app timezone). */
    public function startsAt(): \Carbon\Carbon
    {
        $date = $this->preferred_date instanceof \Carbon\Carbon ? $this->preferred_date : \Carbon\Carbon::parse($this->preferred_date);
        $time = $this->preferred_time; // e.g. "14:30:00"
        return $date->copy()->setTimeFromTimeString($time);
    }

    public function endsAt(): \Carbon\Carbon
    {
        return $this->startsAt()->copy()->addMinutes((int) ($this->duration_min ?? 60));
    }

    /** Live status derived from time + booking status. */
    public function liveState(): string
    {
        if ($this->status === self::STATUS_CANCELLED) return 'cancelled';
        if ($this->status === self::STATUS_COMPLETED) return 'completed';
        $now = now();
        if ($now->lt($this->startsAt())) return 'upcoming';   // countdown
        if ($now->lt($this->endsAt()))   return 'live';       // in-session, join available
        return 'ended';                                        // past end, awaiting mark complete
    }

    /** Seconds until session starts (negative if past). */
    public function secondsUntilStart(): int
    {
        return (int) now()->diffInSeconds($this->startsAt(), false);
    }

    public function secondsUntilEnd(): int
    {
        return (int) now()->diffInSeconds($this->endsAt(), false);
    }

    /**
     * Compute total amount for a session based on consultant hourly rate + duration.
     * Falls back to linear pro-rating for non-standard durations.
     */
    public static function computeAmount(float $hourlyRate, int $durationMin): float
    {
        $multiplier = self::DURATION_MULTIPLIERS[$durationMin] ?? ($durationMin / 60);
        return round($hourlyRate * $multiplier, 2);
    }

    /**
     * Zakat / VAT rate applied ON TOP of the base amount.
     * Example: base = 300 SAR → user pays 300 + 15% = 345 SAR.
     */
    public const ZAKAT_RATE = 0.15;

    /**
     * Financial split for a session price (base amount).
     *
     * Business rules (per platform policy):
     *   • Zakat 15% is charged ON TOP of the base amount — the user pays the total.
     *   • The BASE amount is split 50/50 between consultant and platform.
     *   • The zakat portion is held by the platform and remitted to the
     *     تهيئة الزكاة والدخل periodically (monthly/yearly).
     *
     * Example: base = 300 SAR
     *   → zakat        =  45  (15% of 300)
     *   → total charged = 345  (what the user pays)
     *   → consultant   = 150  (50% of base)
     *   → platform     = 150  (50% of base)
     *   → zakat pool   =  45
     *   Consultant nets the FULL 150 (zakat is NOT deducted from their share).
     *
     * The stored `amount` column on `bookings` holds the TOTAL (345), so:
     *   amount == consultant_share + platform_share + zakat_amount
     */
    public static function computePricing(float $baseAmount): array
    {
        $zakat           = round($baseAmount * self::ZAKAT_RATE, 2);
        $total           = round($baseAmount + $zakat, 2);
        $consultantShare = round($baseAmount / 2, 2);
        $platformShare   = round($baseAmount - $consultantShare, 2); // avoids rounding drift

        return [
            'baseAmount'      => $baseAmount,
            'zakat'           => $zakat,
            'total'           => $total,
            'consultantShare' => $consultantShare,
            'platformShare'   => $platformShare,
            'consultantNet'   => $consultantShare, // Consultant receives full share
        ];
    }
}
