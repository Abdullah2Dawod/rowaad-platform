<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatRemittance extends Model
{
    protected $fillable = [
        'amount', 'period_from', 'period_to', 'remitted_at',
        'reference', 'notes', 'remitted_by',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'period_from' => 'date',
        'period_to'   => 'date',
        'remitted_at' => 'date',
    ];

    public function remittedBy()
    {
        return $this->belongsTo(User::class, 'remitted_by');
    }
}
