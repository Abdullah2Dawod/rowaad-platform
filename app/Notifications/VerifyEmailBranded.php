<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

/**
 * Override Laravel's default VerifyEmail notification with the Rowaad-branded
 * template so verification emails match the platform's visual identity.
 */
class VerifyEmailBranded extends BaseVerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verifyUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('وثّق بريدك الإلكتروني — رواد بلا حدود')
            ->view('emails.notifications.verify-email', [
                'name' => $notifiable->name ?? '',
                'url'  => $verifyUrl,
            ]);
    }
}
