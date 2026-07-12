<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $fillable = [
        'slug', 'name_ar', 'name_en', 'description_ar', 'description_en',
        'icon', 'hero_image', 'color', 'highlights', 'opportunities',
        'featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'highlights'    => 'array',
        'opportunities' => 'array',
        'featured'      => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeFeatured($q) { return $q->where('featured', true); }
    public function scopeOrdered($q)  { return $q->orderBy('sort_order')->orderBy('id'); }
}
