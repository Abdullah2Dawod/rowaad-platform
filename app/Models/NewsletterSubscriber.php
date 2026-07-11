<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'preferences'      => 'array',
        'confirmed_at'     => 'datetime',
        'unsubscribed_at'  => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($s) {
            if (empty($s->confirm_token))     $s->confirm_token = Str::random(48);
            if (empty($s->unsubscribe_token)) $s->unsubscribe_token = Str::random(48);
        });
    }

    // ─────── Helpers ───────
    public function isConfirmed(): bool  { return ! is_null($this->confirmed_at); }
    public function isActive(): bool     { return $this->isConfirmed() && is_null($this->unsubscribed_at); }

    // ─────── Scopes ───────
    public function scopeActive($q)      { return $q->whereNotNull('confirmed_at')->whereNull('unsubscribed_at'); }
    public function scopePending($q)     { return $q->whereNull('confirmed_at')->whereNull('unsubscribed_at'); }
    public function scopeAudience($q, string $tag)
    {
        return $q->active()->where(function ($q) use ($tag) {
            if ($tag === 'all') return;
            $q->whereJsonContains('preferences', $tag);
        });
    }
}
