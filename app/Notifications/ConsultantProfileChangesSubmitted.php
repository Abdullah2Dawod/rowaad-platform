<?php

namespace App\Notifications;

use App\Models\Consultant;
use Filament\Notifications\Actions\Action as FilamentAction;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to admins when a consultant submits changes to sensitive profile fields
 * that require admin approval before applying.
 */
class ConsultantProfileChangesSubmitted extends Notification
{
    use Queueable;

    public function __construct(public Consultant $consultant, public array $changedFields = []) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $name = $this->consultant->full_name_ar ?: $this->consultant->user?->name;
        $url  = url("/admin/consultants/{$this->consultant->id}/edit");

        return (new MailMessage)
            ->subject('تعديلات معلّقة على ملف مستشار — بحاجة لمراجعتك')
            ->view('emails.notifications.consultant-changes-submitted', [
                'consultantName' => $name,
                'fields'         => $this->changedFields,
                'url'            => $url,
            ]);
    }

    public function toDatabase($notifiable): array
    {
        $name  = $this->consultant->full_name_ar ?: $this->consultant->user?->name;
        $count = count($this->changedFields);

        return FilamentNotification::make()
            ->title('تعديلات ملف مستشار بحاجة للاعتماد')
            ->body("قدّم {$name} تعديلاً على {$count} حقلاً حسّاساً في ملفه.")
            ->icon('heroicon-o-clipboard-document-check')
            ->iconColor('warning')
            ->actions([
                FilamentAction::make('review')
                    ->label('مراجعة')
                    ->url("/admin/consultants")
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
