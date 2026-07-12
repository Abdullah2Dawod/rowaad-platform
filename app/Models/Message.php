<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public const SENDER_USER       = 'user';
    public const SENDER_CONSULTANT = 'consultant';
    public const SENDER_SYSTEM     = 'system';

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /** Resolve the actual sender model (User or Consultant). */
    public function sender()
    {
        if ($this->sender_type === self::SENDER_USER) {
            return User::find($this->sender_id);
        }
        if ($this->sender_type === self::SENDER_CONSULTANT) {
            return Consultant::find($this->sender_id);
        }
        return null;
    }
}
