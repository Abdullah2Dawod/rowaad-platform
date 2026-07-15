<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Notifications\BookingCancelled;
use App\Notifications\BookingConfirmed;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon    = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel   = 'الحجوزات';
    protected static ?string $modelLabel        = 'حجز';
    protected static ?string $pluralModelLabel  = 'الحجوزات';
    protected static ?string $navigationGroup   = 'العمليات';
    protected static ?int    $navigationSort    = 10;

    public static function getNavigationBadge(): ?string
    {
        return (string) Booking::where('status', Booking::STATUS_PAID)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getNavigationBadge() > '0' ? 'info' : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('reference')->label('المرجع')->disabled(),
                Forms\Components\Select::make('status')->label('الحالة')->options([
                    Booking::STATUS_PENDING_PAYMENT => 'بانتظار الدفع',
                    Booking::STATUS_PAID            => 'مدفوع',
                    Booking::STATUS_CONFIRMED       => 'مؤكّد',
                    Booking::STATUS_CANCELLED       => 'ملغى',
                    Booking::STATUS_COMPLETED       => 'مكتمل',
                ])->required()->native(false),
                Forms\Components\DatePicker::make('preferred_date')->label('التاريخ')->required(),
                Forms\Components\TimePicker::make('preferred_time')->label('الوقت')->required(),
                Forms\Components\TextInput::make('meeting_url')->label('رابط الاجتماع')->url()->columnSpanFull(),
                Forms\Components\Textarea::make('notes')->label('ملاحظات')->columnSpanFull()->rows(3),
                Forms\Components\Textarea::make('cancellation_reason')->label('سبب الإلغاء')->columnSpanFull()
                    ->visible(fn ($get) => $get('status') === Booking::STATUS_CANCELLED),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->label('المرجع')->fontFamily('mono')->weight('bold')->searchable()->copyable()->copyMessage('نُسخ المرجع'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')->searchable()->weight('bold')
                    ->description(fn ($record) => $record->user?->email),

                Tables\Columns\ImageColumn::make('consultant.avatar_url')
                    ->label('')->circular()->size(28)
                    ->defaultImageUrl(fn () => 'https://api.iconify.design/solar:user-bold-duotone.svg?color=%233DAFB9&width=32'),

                Tables\Columns\TextColumn::make('consultant.full_name_ar')
                    ->label('المستشار')->searchable()
                    ->description(fn ($record) => $record->consultant?->professional_title),

                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('الموعد')->sortable()
                    ->formatStateUsing(fn ($record) => optional($record->preferred_date)->format('Y-m-d'))
                    ->description(fn ($record) => $record->preferred_time.' · '.$record->duration_min.'د'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('المبلغ')->money('SAR')->sortable()->weight('bold')
                    ->description(fn ($record) => 'زكاة: '.number_format($record->zakat_amount, 0).' ر.س'),

                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->formatStateUsing(fn (string $state): string => [
                        'pending_payment' => 'بانتظار الدفع',
                        'paid' => 'مدفوع', 'confirmed' => 'مؤكّد',
                        'cancelled' => 'ملغى', 'completed' => 'مكتمل',
                    ][$state] ?? $state)
                    ->color(fn (string $state): string => [
                        'pending_payment' => 'warning', 'paid' => 'info',
                        'confirmed' => 'success', 'cancelled' => 'danger', 'completed' => 'gray',
                    ][$state] ?? 'gray'),

                Tables\Columns\TextColumn::make('created_at')->label('التسجيل')->since()->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('الحالة')->options([
                    'pending_payment' => 'بانتظار الدفع', 'paid' => 'مدفوع',
                    'confirmed' => 'مؤكّد', 'cancelled' => 'ملغى', 'completed' => 'مكتمل',
                ])->default('paid'),
            ])
            ->actions([
                Tables\Actions\Action::make('confirm')
                    ->iconButton()->tooltip('تأكيد وإرسال رابط الجلسة')
                    ->icon('heroicon-o-check-badge')->color('success')
                    ->modalHeading('تأكيد الحجز وإرسال رابط الجلسة')
                    ->modalDescription('اضغط "توليد رابط تلقائي" ليُنشئ النظام رابط اجتماع فوراً، أو ألصق رابطك الخاص. سيصل الرابط للعميل عبر البريد الإلكتروني تلقائياً.')
                    ->form([
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generate')
                                ->label('توليد رابط تلقائي')
                                ->icon('heroicon-o-sparkles')
                                ->color('primary')
                                ->action(function (Forms\Set $set) {
                                    // Auto-generated meeting room ID — replace with real Zoom API integration in production
                                    $room = 'rowaad-' . strtolower(\Illuminate\Support\Str::random(10));
                                    $set('meeting_url', "https://meet.rowaad.org/{$room}");
                                }),
                        ]),
                        Forms\Components\TextInput::make('meeting_url')
                            ->label('رابط الاجتماع (Zoom / Meet)')
                            ->url()->required()
                            ->placeholder('https://zoom.us/j/... أو استخدم زر التوليد التلقائي')
                            ->helperText('يُرسَل هذا الرابط للعميل عبر بريده الإلكتروني فور التأكيد.'),
                    ])
                    // Only the assigned consultant can confirm & send the meeting link.
                    // Admin can VIEW but cannot send (per platform policy).
                    ->visible(function (Booking $b) {
                        if ($b->status !== Booking::STATUS_PAID) return false;
                        $user = auth()->user();
                        if (!$user) return false;
                        // Consultants can confirm only their own bookings
                        if ($user->role === 'consultant') {
                            return $user->consultant?->id === $b->consultant_id;
                        }
                        return false; // admins do NOT confirm — that's consultant-only
                    })
                    ->action(function (Booking $b, array $data) {
                        $b->update([
                            'status'       => Booking::STATUS_CONFIRMED,
                            'meeting_url'  => $data['meeting_url'],
                            'confirmed_at' => now(),
                            'confirmed_by' => auth()->id(),
                        ]);

                        // Notify user (mail + in-app) — mail failure never blocks the confirm
                        try {
                            $b->user?->notify(new BookingConfirmed($b));
                        } catch (\Throwable $e) {
                            \Log::warning('[Booking confirmed mail] failed: ' . $e->getMessage());
                        }

                        Notification::make()
                            ->title('تم تأكيد الحجز')
                            ->body("أُرسل رابط الجلسة إلى {$b->user->email}")
                            ->success()->send();
                    }),

                Tables\Actions\Action::make('cancel')
                    ->iconButton()->tooltip('إلغاء الحجز')
                    ->icon('heroicon-o-x-circle')->color('danger')
                    ->modalHeading('إلغاء الحجز')
                    ->modalDescription('سيتم إشعار العميل بالإلغاء وسبب الإلغاء عبر البريد الإلكتروني.')
                    ->form([
                        Forms\Components\Textarea::make('cancellation_reason')
                            ->label('سبب الإلغاء')->required()->rows(3)
                            ->placeholder('اشرح السبب حتى يفهمه العميل ويقرّر الحجز التالي.'),
                    ])
                    ->visible(fn (Booking $b) => in_array($b->status, [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED]))
                    ->action(function (Booking $b, array $data) {
                        $b->update([
                            'status'              => Booking::STATUS_CANCELLED,
                            'cancellation_reason' => $data['cancellation_reason'],
                        ]);

                        // Notify user (mail + in-app) — mail failure never blocks the cancel
                        try {
                            $b->user?->notify(new BookingCancelled($b));
                        } catch (\Throwable $e) {
                            \Log::warning('[Booking cancelled mail] failed: ' . $e->getMessage());
                        }

                        Notification::make()
                            ->title('تم إلغاء الحجز')
                            ->body("أُبلغ العميل ({$b->user->email}) بالإلغاء وسببه")
                            ->warning()->send();
                    }),
                Tables\Actions\Action::make('view_details')
                    ->iconButton()->tooltip('عرض تفاصيل الحجز')
                    ->icon('heroicon-o-eye')->color('gray')
                    ->url(fn (Booking $b) => static::getUrl('view', ['record' => $b->id])),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $q = parent::getEloquentQuery()->with(['user', 'consultant']);

        // Consultants only see their own bookings
        $user = auth()->user();
        if ($user && $user->role === 'consultant' && $user->consultant) {
            $q->where('consultant_id', $user->consultant->id);
        }
        return $q;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view'   => Pages\ViewBooking::route('/{record}'),
            'edit'   => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
