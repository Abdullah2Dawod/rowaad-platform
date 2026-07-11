<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'avatar', 'locale'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Admins have full panel access; approved consultants have a limited view.
    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->role === 'admin') return true;
        if ($this->role === 'consultant') {
            return $this->consultant?->status === Consultant::STATUS_APPROVED;
        }
        return false;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // === Role helpers ===
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isConsultant(): bool { return $this->role === 'consultant'; }
    public function isUser(): bool       { return $this->role === 'user'; }
    public function isVerified(): bool   { return ! is_null($this->email_verified_at); }

    // === Relationships ===
    public function consultant(): HasOne
    {
        return $this->hasOne(Consultant::class);
    }

    /**
     * Unified avatar URL. Returns relative "/storage/..." paths for locally-stored
     * files (so the browser resolves against whatever host the app is currently
     * being served from — 127.0.0.1, localhost, or production domain).
     * External URLs pass through unchanged.
     *
     * For consultants, prefers the consultant profile picture so the same avatar
     * shows in the public site, the admin Filament panel, and the consultant panel.
     */
    public function avatarUrl(): ?string
    {
        // Consultants: prefer their consultant profile picture.
        if ($this->role === 'consultant') {
            $consultantAvatar = $this->consultant?->avatar_url;
            if (! empty($consultantAvatar)) return $consultantAvatar;
        }

        if (empty($this->avatar)) return null;
        if (\Illuminate\Support\Str::startsWith($this->avatar, ['http://', 'https://', '//'])) {
            return $this->avatar;
        }
        return '/storage/' . ltrim($this->avatar, '/');
    }

    /** Filament reads this to render the avatar in the top-right user menu (admin + consultant panels). */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatarUrl();
    }
}
