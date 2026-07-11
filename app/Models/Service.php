<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'slug', 'title', 'tagline', 'icon', 'hero_image', 'summary',
        'includes', 'deliverables', 'stats', 'featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'includes'     => 'array',
        'deliverables' => 'array',
        'stats'        => 'array',
        'featured'     => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeOrdered($q)  { return $q->orderBy('sort_order')->orderBy('id'); }
    public function scopeFeatured($q) { return $q->where('featured', true); }
}
