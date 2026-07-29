<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'subtotal'    => 'decimal:2',
        'vat_amount'  => 'decimal:2',
        'discount'    => 'decimal:2',
        'total'       => 'decimal:2',
        'paid_at'     => 'datetime',
        'cancelled_at'=> 'datetime',
    ];

    public const STATUS_PENDING   = 'pending';
    public const STATUS_PAID      = 'paid';
    public const STATUS_FAILED    = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED  = 'refunded';

    public static array $STATUS_LABELS = [
        self::STATUS_PENDING   => 'قيد الدفع',
        self::STATUS_PAID      => 'مدفوع',
        self::STATUS_FAILED    => 'فشل الدفع',
        self::STATUS_CANCELLED => 'ملغى',
        self::STATUS_REFUNDED  => 'مسترد',
    ];

    public static array $METHOD_LABELS = [
        'mada'          => 'مدى',
        'apple_pay'     => 'Apple Pay',
        'stc_pay'       => 'STC Pay',
        'bank_transfer' => 'تحويل بنكي',
        'credit_card'   => 'بطاقة ائتمان',
    ];

    protected static function booted(): void
    {
        static::creating(function ($order) {
            if (empty($order->reference)) {
                $order->reference = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function user():  BelongsTo { return $this->belongsTo(User::class); }

    public function isPaid(): bool { return $this->status === self::STATUS_PAID; }

    public function markPaid(?string $method = null, ?string $reference = null): void
    {
        $this->update([
            'status'            => self::STATUS_PAID,
            'payment_method'    => $method ?? $this->payment_method,
            'payment_reference' => $reference ?? $this->payment_reference,
            'paid_at'           => now(),
        ]);
    }
}
