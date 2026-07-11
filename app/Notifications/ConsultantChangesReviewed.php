<?php

namespace App\Notifications;

use App\Models\Consultant;
use Filament\Notifications\Actions\Action as FilamentAction;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to a consultant when the admin approves OR rejects their pending profile changes.
 */
class ConsultantChangesReviewed extends Notification
{
    use Queueable;

    public function __construct(
        public Consultant $consultant,
        public string $decision, // 'approved' | 'rejected'
        public array $changedFields = [],
        public ?string $reason = null,
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $subject = $this->decision === 'approved'
            ? '✓ تم اعتماد تعديلات ملفّك الشخصي'
            : 'تحديث بشأن تعديلات ملفّك الشخصي';

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.notifications.consultant-changes-reviewed', [
                'consultantName' => $this->consultant->full_name_ar ?: $this->consultant->user?->name,
                'decision'       => $this->decision,
                'fields'         => $this->changedFields,
                'reason'         => $this->reason,
                'url'            => url('/admin/consultant-profile'),
            ]);
    }

    public function toDatabase($notifiable): array
    {
        $count = count($this->changedFields);

        if ($this->decision === 'approved') {
            $title = 'تم اعتماد تعديلاتك ✓';
            $body  = "اعتمد الأدمن تعديل {$count} حقلاً من ملفّك، وطُبِّقت على ملفّك العام.";
            $icon  = 'heroicon-o-check-badge';
            $color = 'success';
        } else {
            $title = 'لم يُعتمد التعديل';
            $body  = "لم يُعتمد تعديل {$count} حقلاً من ملفّك. راجع الملاحظات وأعِد التقديم.";
            $icon  = 'heroicon-o-x-circle';
            $color = 'danger';
        }

        return FilamentNotification::make()
            ->title($title)
            ->body($body)
            ->icon($icon)
            ->iconColor($color)
            ->actions([
                FilamentAction::make('view')
                    ->label('عرض ملفّي')
                    ->url('/admin/consultant-profile')
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
