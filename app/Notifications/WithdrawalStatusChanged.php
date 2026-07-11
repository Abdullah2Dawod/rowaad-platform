<?php

namespace App\Notifications;

use App\Models\WithdrawalRequest;
use Filament\Notifications\Actions\Action as FilamentAction;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to a consultant whenever the status of one of their withdrawal requests changes.
 * Fires for: approved, paid, rejected.
 */
class WithdrawalStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public WithdrawalRequest $request) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $r = $this->request;

        $subject = match ($r->status) {
            WithdrawalRequest::STATUS_APPROVED => '✓ تم اعتماد طلب السحب — قيد التحويل',
            WithdrawalRequest::STATUS_PAID     => '💸 تم تحويل رصيدك بنجاح',
            WithdrawalRequest::STATUS_REJECTED => 'تحديث بشأن طلب السحب',
            default                            => 'تحديث طلب سحب',
        };

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.notifications.withdrawal-status-changed', [
                'r'    => $r,
                'name' => $r->consultant?->full_name_ar ?: $r->consultant?->user?->name,
            ]);
    }

    public function toDatabase($notifiable): array
    {
        $r = $this->request;

        [$title, $body, $icon, $color] = match ($r->status) {
            WithdrawalRequest::STATUS_APPROVED => [
                'تم اعتماد طلب سحبك ✓',
                "طلب السحب {$r->reference} بمبلغ " . number_format($r->amount, 2) . ' ر.س — سيتم التحويل قريباً.',
                'heroicon-o-check-badge', 'info',
            ],
            WithdrawalRequest::STATUS_PAID => [
                'تم تحويل رصيدك 💸',
                "حُوّل المبلغ " . number_format($r->amount, 2) . " ر.س إلى حسابك البنكي — {$r->reference}.",
                'heroicon-o-banknotes', 'success',
            ],
            WithdrawalRequest::STATUS_REJECTED => [
                'لم يُعتمد طلب السحب',
                "طلب السحب {$r->reference} لم يُعتمد — راجع الملاحظات.",
                'heroicon-o-x-circle', 'danger',
            ],
            default => [
                'تحديث طلب سحب', "الطلب {$r->reference}", 'heroicon-o-banknotes', 'gray',
            ],
        };

        return FilamentNotification::make()
            ->title($title)
            ->body($body)
            ->icon($icon)
            ->iconColor($color)
            ->actions([
                FilamentAction::make('view')
                    ->label('محفظتي')
                    ->url('/admin/wallet')
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
