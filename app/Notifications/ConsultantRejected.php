<?php

namespace App\Notifications;

use App\Models\Consultant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultantRejected extends Notification
{
    use Queueable;

    public function __construct(public Consultant $consultant, public string $reason) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('بخصوص طلبك في منصة رواد')
            ->view('emails.notifications.consultant-rejected', [
                'consultantName' => $this->consultant->full_name_ar,
                'reason' => $this->reason,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'consultant.rejected',
            'message' => 'لم يُعتمد طلبك — راجع الملاحظات',
            'url'     => '/become-a-consultant/rejected',
        ];
    }
}
