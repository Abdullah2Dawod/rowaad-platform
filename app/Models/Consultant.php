<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultant extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'certificates'               => 'array',
        'secondary_specializations'  => 'array',
        'services'                   => 'array',
        'languages'                  => 'array',
        'availability'               => 'array',
        'is_featured'                => 'boolean',
        'birth_date'                 => 'date',
        'submitted_at'               => 'datetime',
        'approved_at'                => 'datetime',
        'hourly_rate'                => 'decimal:2',
        'rating_avg'                 => 'decimal:2',
        'pending_changes'                => 'array',
        'pending_changes_submitted_at'   => 'datetime',
        'rich_content'                   => 'array',
    ];

    /**
     * Sensitive fields — editing these requires admin approval.
     * The consultant can freely edit anything NOT in this list
     * (name, avatar, phone, city, etc.).
     */
    public const SENSITIVE_FIELDS = [
        'professional_title',
        'bio_ar',
        'bio_en',
        'specialization_id',
        'secondary_specializations',
        'years_experience',
        'hourly_rate',
        'session_duration_min',
        'services',
        'certificates',
        'linkedin_url',
        'website_url',
    ];

    public function hasPendingChanges(): bool
    {
        return ! empty($this->pending_changes);
    }

    /**
     * Unified public avatar URL for the consultant.
     * Returns a relative URL for locally-stored files so the browser resolves it
     * against the current origin — this avoids the APP_URL/host mismatch on dev
     * (localhost vs 127.0.0.1:8000). Absolute URLs pass through unchanged.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        $p = $this->avatar_path;
        if (empty($p)) return null;
        if (\Illuminate\Support\Str::startsWith($p, ['http://', 'https://', '//'])) return $p;
        return '/storage/' . ltrim($p, '/');
    }

    /**
     * Consultant submits a profile update.
     * Non-sensitive fields (name, avatar, phone, city, birth_date...) apply immediately.
     * Sensitive fields queue in pending_changes and await admin approval.
     *
     * @return array{applied: array, pending: array}
     */
    public function submitProfileUpdate(array $data): array
    {
        $sensitive = collect($data)->only(self::SENSITIVE_FIELDS)->all();
        $free      = collect($data)->except(self::SENSITIVE_FIELDS)->all();

        if (! empty($free)) {
            $this->fill($free);
        }
        if (! empty($sensitive)) {
            $existing = (array) ($this->pending_changes ?? []);
            $this->pending_changes = array_merge($existing, $sensitive);
            $this->pending_changes_submitted_at = now();
        }
        $this->save();

        return ['applied' => $free, 'pending' => $sensitive];
    }

    /**
     * Admin applies the queued changes and notifies the consultant.
     * @return array The fields that were applied (for downstream use).
     */
    public function applyPendingChanges(?User $reviewer = null): array
    {
        if (! $this->hasPendingChanges()) return [];

        $applied = $this->pending_changes;
        $this->fill($applied);
        $this->pending_changes = null;
        $this->pending_changes_submitted_at = null;
        if ($reviewer) $this->reviewed_by = $reviewer->id;
        $this->save();

        // Notify the consultant of the approval (mail failures never break the action).
        if ($this->user) {
            try {
                $this->user->notify(new \App\Notifications\ConsultantChangesReviewed(
                    $this, 'approved', $applied
                ));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('[Consultant approval notify] failed: ' . $e->getMessage());
            }
        }

        return $applied;
    }

    /**
     * Admin rejects the queued changes with an optional reason and notifies the consultant.
     * @return array The fields that were rejected (for downstream use).
     */
    public function rejectPendingChanges(?User $reviewer = null, ?string $reason = null): array
    {
        $rejected = (array) ($this->pending_changes ?? []);

        $this->pending_changes = null;
        $this->pending_changes_submitted_at = null;
        if ($reviewer) $this->reviewed_by = $reviewer->id;
        $this->save();

        if ($this->user) {
            try {
                $this->user->notify(new \App\Notifications\ConsultantChangesReviewed(
                    $this, 'rejected', $rejected, $reason
                ));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('[Consultant rejection notify] failed: ' . $e->getMessage());
            }
        }

        return $rejected;
    }

    // === Status constants ===
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED  = 'approved';
    public const STATUS_REJECTED  = 'rejected';

    // === Relationships ===
    public function user(): BelongsTo          { return $this->belongsTo(User::class); }
    public function specialization(): BelongsTo { return $this->belongsTo(Specialization::class); }
    public function reviewer(): BelongsTo      { return $this->belongsTo(User::class, 'reviewed_by'); }

    // === Scopes ===
    public function scopeApproved($q) { return $q->where('status', self::STATUS_APPROVED); }
    public function scopePending($q)  { return $q->where('status', self::STATUS_SUBMITTED); }

    // === Helpers ===
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isPending(): bool  { return $this->status === self::STATUS_SUBMITTED; }
}
