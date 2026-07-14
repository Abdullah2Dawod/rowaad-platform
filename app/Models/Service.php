<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'slug', 'title', 'tagline', 'icon', 'hero_image', 'summary',
        'includes', 'deliverables', 'stats', 'rich_content',
        'featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'includes'     => 'array',
        'deliverables' => 'array',
        'stats'        => 'array',
        'rich_content' => 'array',
        'featured'     => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeOrdered($q)  { return $q->orderBy('sort_order')->orderBy('id'); }
    public function scopeFeatured($q) { return $q->where('featured', true); }
}
