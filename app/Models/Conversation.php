<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'last_message_at' => 'datetime',
        'assigned_at'     => 'datetime',
        'closed_at'       => 'datetime',
    ];

    public const STATUS_OPEN     = 'open';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_CLOSED   = 'closed';

    public function user(): BelongsTo       { return $this->belongsTo(User::class); }
    public function consultant(): BelongsTo { return $this->belongsTo(Consultant::class, 'assigned_consultant_id'); }
    public function messages(): HasMany     { return $this->hasMany(Message::class)->orderBy('created_at'); }
    public function latestMessage()         { return $this->hasOne(Message::class)->latestOfMany(); }

    public function scopeOpen($q)     { return $q->where('status', self::STATUS_OPEN); }
    public function scopeAssigned($q) { return $q->where('status', self::STATUS_ASSIGNED); }
    public function scopeActive($q)   { return $q->whereIn('status', [self::STATUS_OPEN, self::STATUS_ASSIGNED]); }

    /** First consultant to reply claims the conversation. */
    public function claim(Consultant $consultant): bool
    {
        if ($this->assigned_consultant_id) {
            return $this->assigned_consultant_id === $consultant->id;
        }
        $this->update([
            'assigned_consultant_id' => $consultant->id,
            'status'                 => self::STATUS_ASSIGNED,
            'assigned_at'            => now(),
        ]);
        return true;
    }
}
