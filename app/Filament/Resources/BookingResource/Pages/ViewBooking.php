<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use App\Notifications\BookingConfirmed;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    // Render our custom blade instead of the default infolist
    protected static string $view = 'filament.resources.booking-resource.pages.view-booking';

    public function getTitle(): string
    {
        return 'حجز ' . $this->record->reference;
    }

    /** Consultant of the booking, and only when it's still upcoming/paid/confirmed, can push a meeting link. */
    protected function canManageLink(): bool
    {
        $u = auth()->user();
        if (! $u) return false;
        if ($u->role !== 'consultant') return false;
        if ($u->consultant?->id !== $this->record->consultant_id) return false;
        return in_array($this->record->status, [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED]);
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        if ($this->canManageLink()) {
            $actions[] = Actions\Action::make('sendLink')
                ->label($this->record->meeting_url ? 'تحديث رابط الجلسة' : 'إرسال رابط الجلسة')
                ->icon('heroicon-o-link')
                ->color($this->record->meeting_url ? 'warning' : 'success')
                ->modalHeading($this->record->meeting_url ? 'تحديث رابط الجلسة' : 'تأكيد الحجز وإرسال رابط الجلسة')
                ->modalDescription('اضغط "توليد رابط تلقائي" ليُنشئ النظام رابطاً فوراً، أو ألصق رابطك من Zoom / Meet. سيصل الرابط للعميل فور الحفظ.')
                ->form([
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('generate')
                            ->label('توليد رابط تلقائي')
                            ->icon('heroicon-o-sparkles')
                            ->color('primary')
                            ->action(function (Forms\Set $set) {
                                $room = 'rowaad-' . strtolower(\Illuminate\Support\Str::random(10));
                                $set('meeting_url', "https://meet.rowaad.org/{$room}");
                            }),
                    ]),
                    Forms\Components\TextInput::make('meeting_url')
                        ->label('رابط الاجتماع (Zoom / Meet)')
                        ->url()->required()
                        ->default($this->record->meeting_url)
                        ->placeholder('https://zoom.us/j/...')
                        ->helperText('يُرسَل هذا الرابط للعميل عبر البريد فور الحفظ.'),
                ])
                ->action(function (array $data) {
                    $b = $this->record;
                    $wasConfirmed = $b->status === Booking::STATUS_CONFIRMED;
                    $b->update([
                        'status'       => Booking::STATUS_CONFIRMED,
                        'meeting_url'  => $data['meeting_url'],
                        'confirmed_at' => $b->confirmed_at ?? now(),
                        'confirmed_by' => auth()->id(),
                    ]);
                    try { $b->user?->notify(new BookingConfirmed($b)); } catch (\Throwable $e) {}
                    Notification::make()
                        ->title($wasConfirmed ? 'تم تحديث الرابط' : 'تم تأكيد الحجز')
                        ->body("أُرسل الرابط إلى {$b->user->email}")
                        ->success()->send();
                    $this->record->refresh();
                });
        }

        return $actions;
    }

    // Ensure related models are eager-loaded for the blade view
    protected function resolveRecord(int | string $key): \Illuminate\Database\Eloquent\Model
    {
        return parent::resolveRecord($key)->loadMissing(['user', 'consultant.user', 'consultant.specialization']);
    }

    // Pass extras into the blade
    protected function getViewData(): array
    {
        $b = $this->record;
        return [
            'b'          => $b,
            'canManage'  => $this->canManageLink(),
            'startsIso'  => $b->startsAt()->toIso8601String(),
            'endsIso'    => $b->endsAt()->toIso8601String(),
            'liveState'  => $b->liveState(),
            'client'     => $b->user,
            'consultant' => $b->consultant,
        ];
    }
}
