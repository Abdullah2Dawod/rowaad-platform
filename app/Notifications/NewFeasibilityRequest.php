<?php

namespace App\Notifications;

use App\Models\FeasibilityRequest;
use Filament\Notifications\Actions\Action as FilamentAction;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFeasibilityRequest extends Notification
{
    use Queueable;

    public function __construct(public FeasibilityRequest $request) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("📋 طلب دراسة جدوى جديد — {$this->request->reference}")
            ->view('emails.notifications.new-feasibility-request', [
                'req' => $this->request,
                'adminUrl' => url("/admin/feasibility-requests/{$this->request->id}/edit"),
            ]);
    }

    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title('طلب دراسة جدوى جديد')
            ->body("مشروع: {$this->request->project_title} — {$this->request->contact_name}")
            ->icon('heroicon-o-document-magnifying-glass')
            ->iconColor('info')
            ->actions([
                FilamentAction::make('review')
                    ->label('مراجعة الطلب')
                    ->url("/admin/feasibility-requests/{$this->request->id}/edit")
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
