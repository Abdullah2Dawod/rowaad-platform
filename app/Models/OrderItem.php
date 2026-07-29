<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
        'meta'       => 'array',
    ];

    public function order():       BelongsTo { return $this->belongsTo(Order::class); }
    public function purchasable(): MorphTo   { return $this->morphTo(); }
}
