<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the booking's user when the consultant confirms and provides
 * a meeting link (Zoom / Google Meet / Zoho, etc.).
 */
class BookingConfirmed extends Notification
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
            ->subject("✅ تم تأكيد استشارتك — {$b->reference}")
            ->view('emails.notifications.booking-confirmed', [
                'b' => $b,
                'notifiableName' => $notifiable->name,
                'consultantName' => $b->consultant?->full_name_ar ?: $b->consultant?->user?->name,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'type'        => 'booking.confirmed',
            'title'       => 'تم تأكيد الاستشارة',
            'message'     => "الاستشارة {$this->booking->reference} مؤكّدة — رابط الجلسة متاح.",
            'reference'   => $this->booking->reference,
            'meeting_url' => $this->booking->meeting_url,
            'url'         => "/bookings/{$this->booking->id}",
        ];
    }
}
