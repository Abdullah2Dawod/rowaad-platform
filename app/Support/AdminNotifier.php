<?php

namespace App\Support;

use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

/**
 * Small helper to fan out Filament DB notifications to all admins.
 * Silently swallows failures so a broken notification never breaks a user flow.
 */
class AdminNotifier
{
    public static function ping(string $title, string $body, ?string $url = null, string $icon = 'heroicon-o-bell', string $color = 'primary'): void
    {
        try {
            $admins = User::where('role', 'admin')->get();
            if ($admins->isEmpty()) return;

            $notification = Notification::make()
                ->title($title)
                ->body($body)
                ->icon($icon)
                ->color($color);

            if ($url) {
                $notification->actions([
                    Action::make('open')->label('عرض')->url($url),
                ]);
            }

            $admins->each(fn ($admin) => $notification->sendToDatabase($admin));
        } catch (\Throwable $e) {
            \Log::warning('[AdminNotifier] ' . $e->getMessage());
        }
    }
}
