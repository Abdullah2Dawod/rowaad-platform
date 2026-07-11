<?php

namespace App\Notifications;

use App\Models\Consultant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultantApproved extends Notification
{
    use Queueable;

    public function __construct(
        public Consultant $consultant,
        public string $password,
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 تم اعتماد طلبك في منصة رواد')
            ->view('emails.notifications.consultant-approved', [
                'consultantName' => $this->consultant->full_name_ar,
                'email' => $notifiable->email,
                'password' => $this->password,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'consultant.approved',
            'message' => 'تم اعتماد طلبك كمستشار',
            'url'     => '/login',
        ];
    }
}
