<?php

namespace App\Notifications;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterConfirmation extends Notification
{
    use Queueable;

    public function __construct(public NewsletterSubscriber $subscriber) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $confirmUrl     = url('/newsletter/confirm/' . $this->subscriber->confirm_token);
        $unsubscribeUrl = url('/newsletter/unsubscribe/' . $this->subscriber->unsubscribe_token);

        return (new MailMessage)
            ->subject('✉️ أكّد اشتراكك في نشرة رواد بلا حدود')
            ->view('emails.notifications.newsletter-confirm', [
                'subscriber'     => $this->subscriber,
                'confirmUrl'     => $confirmUrl,
                'unsubscribeUrl' => $unsubscribeUrl,
            ]);
    }
}
