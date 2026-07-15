<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Notifications\SessionReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendBookingReminders extends Command
{
    protected $signature   = 'bookings:tick';
    protected $description = 'Send 10-min reminders + auto-complete finished sessions';

    public function handle(): int
    {
        $this->fireReminders();
        $this->autoCompleteEnded();
        return self::SUCCESS;
    }

    private function fireReminders(): void
    {
        $target = now()->copy()->addMinutes(10);

        $candidates = Booking::whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED])
            ->whereNull('completed_at')
            ->whereNull('reminded_at')
            ->get()
            ->filter(function (Booking $b) use ($target) {
                $diff = $b->startsAt()->diffInSeconds($target, false);
                return abs($diff) < 60;
            });

        foreach ($candidates as $b) {
            try {
                $b->loadMissing(['user', 'consultant.user']);
                if ($b->user)             $b->user->notify(new SessionReminder($b, 'user'));
                if ($b->consultant?->user) $b->consultant->user->notify(new SessionReminder($b, 'consultant'));

                $b->forceFill(['reminded_at' => now()])->save();
                Log::info("[bookings:tick] reminder sent for {$b->reference}");
            } catch (\Throwable $e) {
                Log::warning("[bookings:tick] reminder failed for {$b->reference}: " . $e->getMessage());
            }
        }
    }

    private function autoCompleteEnded(): void
    {
        $ended = Booking::whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED])
            ->whereNull('completed_at')
            ->get()
            ->filter(fn (Booking $b) => now()->greaterThan($b->endsAt()));

        foreach ($ended as $b) {
            try {
                $b->update([
                    'status'       => Booking::STATUS_COMPLETED,
                    'completed_at' => now(),
                ]);
                \App\Support\AdminNotifier::ping(
                    'اكتملت جلسة استشارة',
                    "الجلسة {$b->reference} انتهت — يمكن اعتماد الأرباح للمستشار.",
                    "/admin/bookings/{$b->id}/edit",
                    'heroicon-o-check-badge', 'success'
                );
            } catch (\Throwable $e) {
                Log::warning("[bookings:tick] auto-complete failed for {$b->reference}: " . $e->getMessage());
            }
        }
    }
}
