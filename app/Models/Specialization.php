<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialization extends Model
{
    protected $fillable = [
        'slug', 'name_ar', 'name_en', 'icon',
        'description_ar', 'description_en',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function consultants(): HasMany
    {
        return $this->hasMany(Consultant::class);
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
