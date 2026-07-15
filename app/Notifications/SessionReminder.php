<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionReminder extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking, public string $audience) {}
    // $audience: 'user' | 'consultant'

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $b = $this->booking;
        $when = $b->startsAt()->isoFormat('dddd, D MMMM YYYY — h:mm a');

        $mail = (new MailMessage)
            ->subject('تذكير: استشارة رواد بعد 10 دقائق · ' . $b->reference)
            ->greeting('أهلاً ' . $notifiable->name)
            ->line('تذكير ودّي: جلستك تبدأ بعد 10 دقائق تقريباً.')
            ->line('**الموعد:** ' . $when)
            ->line('**المدة:** ' . $b->duration_min . ' دقيقة')
            ->line('**رقم الحجز:** ' . $b->reference);

        if ($b->meeting_url) {
            $mail->action('انضم إلى الجلسة الآن', $b->meeting_url);
        } else {
            $mail->line('سيصلك رابط الجلسة قريباً من المستشار.');
        }

        return $mail
            ->line('نتمنى لك جلسة موفّقة!')
            ->salutation('فريق رواد');
    }
}
