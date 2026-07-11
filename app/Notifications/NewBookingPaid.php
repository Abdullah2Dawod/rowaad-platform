<?php

namespace App\Notifications;

use App\Models\Booking;
use Filament\Notifications\Actions\Action as FilamentAction;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to admin(s) + the target consultant when a booking is paid.
 * The consultant needs to review the details, then confirm with a meeting link.
 */
class NewBookingPaid extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $b = $this->booking;
        $b->loadMissing(['user', 'consultant.user']);

        $isConsultant = $notifiable->role === 'consultant';
        $consultantName = $b->consultant?->full_name_ar ?: ($b->consultant?->user?->name ?? '');

        return (new MailMessage)
            ->subject("💼 حجز جديد بانتظار التأكيد — {$b->reference}")
            ->view('emails.notifications.booking-paid', [
                'b' => $b,
                'isConsultant' => $isConsultant,
                'consultantName' => $consultantName,
                'url' => url("/admin/bookings/{$b->id}/edit"),
            ]);
    }

    /**
     * Filament reads the notification via its own format when using
     * `->databaseNotifications()`. We return the Filament payload here.
     */
    public function toDatabase($notifiable): array
    {
        $b = $this->booking;
        $b->loadMissing(['user']);

        return FilamentNotification::make()
            ->title('حجز جديد بانتظار التأكيد')
            ->body("العميل {$b->user?->name} — المرجع {$b->reference}")
            ->icon('heroicon-o-calendar-days')
            ->iconColor('info')
            ->actions([
                FilamentAction::make('view')
                    ->label('عرض الحجز')
                    ->url("/admin/bookings/{$b->id}/edit")
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
