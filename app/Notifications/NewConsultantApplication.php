<?php

namespace App\Notifications;

use App\Models\Consultant;
use Filament\Notifications\Actions\Action as FilamentAction;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewConsultantApplication extends Notification
{
    use Queueable;

    public function __construct(public Consultant $consultant) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $name = $this->consultant->full_name_ar ?? $this->consultant->user?->name;
        return (new MailMessage)
            ->subject('طلب مستشار جديد قيد المراجعة')
            ->view('emails.notifications.new-consultant-application', [
                'c' => $this->consultant->loadMissing('user'),
                'consultantName' => $name,
                'url' => url("/admin/consultants/{$this->consultant->id}/edit"),
            ]);
    }

    public function toDatabase($notifiable): array
    {
        $name = $this->consultant->full_name_ar ?? $this->consultant->user->name;

        return FilamentNotification::make()
            ->title('طلب مستشار جديد')
            ->body("قدّم {$name} طلب انضمام كمستشار — بانتظار مراجعتك.")
            ->icon('heroicon-o-user-plus')
            ->iconColor('warning')
            ->actions([
                FilamentAction::make('review')
                    ->label('مراجعة الطلب')
                    ->url("/admin/consultants/{$this->consultant->id}/edit")
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
