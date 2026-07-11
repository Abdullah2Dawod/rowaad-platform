<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the booking's user when the consultant / admin cancels the booking.
 */
class BookingCancelled extends Notification
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
        $b->loadMissing(['consultant.user']);

        return (new MailMessage)
            ->subject("⚠️ تم إلغاء استشارتك — {$b->reference}")
            ->view('emails.notifications.booking-cancelled', [
                'b' => $b,
                'notifiableName' => $notifiable->name,
                'consultantName' => $b->consultant?->full_name_ar ?: $b->consultant?->user?->name,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'type'      => 'booking.cancelled',
            'title'     => 'تم إلغاء الاستشارة',
            'message'   => "الحجز {$this->booking->reference} أُلغي.",
            'reference' => $this->booking->reference,
            'url'       => "/bookings/{$this->booking->id}",
        ];
    }
}
