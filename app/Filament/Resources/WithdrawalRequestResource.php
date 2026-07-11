<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalRequestResource\Pages;
use App\Models\WithdrawalRequest;
use App\Notifications\WithdrawalStatusChanged;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WithdrawalRequestResource extends Resource
{
    protected static ?string $model = WithdrawalRequest::class;
    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'طلبات السحب';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?string $modelLabel      = 'طلب سحب';
    protected static ?string $pluralModelLabel = 'طلبات السحب';
    protected static ?int    $navigationSort  = 27;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) WithdrawalRequest::pending()->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return WithdrawalRequest::pending()->exists() ? 'warning' : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('بيانات الطلب')->schema([
                Forms\Components\TextInput::make('reference')->label('المرجع')->disabled(),
                Forms\Components\Select::make('consultant_id')->label('المستشار')
                    ->relationship('consultant.user', 'name')->disabled(),
                Forms\Components\TextInput::make('amount')->label('المبلغ (ر.س)')->numeric()->disabled(),
                Forms\Components\Select::make('status')->label('الحالة')->options([
                    'pending'=>'قيد المراجعة','approved'=>'مُعتمَد','paid'=>'تم التحويل','rejected'=>'مرفوض',
                ])->required(),
            ])->columns(2),

            Forms\Components\Section::make('بيانات البنك')->schema([
                Forms\Components\TextInput::make('bank_name')->label('البنك')->disabled(),
                Forms\Components\TextInput::make('bank_account_holder')->label('صاحب الحساب')->disabled(),
                Forms\Components\TextInput::make('iban')->label('IBAN')->disabled(),
                Forms\Components\TextInput::make('swift_code')->label('SWIFT')->disabled(),
            ])->columns(2),

            Forms\Components\Section::make('المعالجة')->schema([
                Forms\Components\Textarea::make('consultant_notes')->label('ملاحظات المستشار')->disabled()->rows(2),
                Forms\Components\Textarea::make('admin_notes')->label('ملاحظات إدارية')->rows(3),
                Forms\Components\TextInput::make('payment_reference')->label('مرجع التحويل البنكي'),
                Forms\Components\DateTimePicker::make('paid_at')->label('وقت التحويل'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference')->label('المرجع')->fontFamily('mono')->searchable(),
                Tables\Columns\TextColumn::make('consultant.user.name')->label('المستشار')->searchable(),
                Tables\Columns\TextColumn::make('amount')->label('المبلغ')->money('SAR')->sortable(),
                Tables\Columns\TextColumn::make('bank_name')->label('البنك')->toggleable(),
                Tables\Columns\TextColumn::make('iban')->label('IBAN')->fontFamily('mono')->toggleable()->copyable(),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning', 'approved' => 'info',
                        'paid' => 'success', 'rejected' => 'danger', default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => [
                        'pending'=>'قيد المراجعة','approved'=>'مُعتمَد','paid'=>'تم التحويل','rejected'=>'مرفوض',
                    ][$state] ?? $state),
                Tables\Columns\TextColumn::make('created_at')->label('التقديم')->since()->sortable(),
                Tables\Columns\TextColumn::make('paid_at')->label('تاريخ التحويل')->dateTime()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending'=>'قيد المراجعة','approved'=>'مُعتمَد','paid'=>'تم التحويل','rejected'=>'مرفوض',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('اعتماد')->icon('heroicon-o-check-badge')->color('info')
                    ->visible(fn (WithdrawalRequest $r) => $r->status === WithdrawalRequest::STATUS_PENDING)
                    ->requiresConfirmation()
                    ->action(function (WithdrawalRequest $r) {
                        $r->update([
                            'status'       => WithdrawalRequest::STATUS_APPROVED,
                            'processed_by' => auth()->id(),
                            'processed_at' => now(),
                        ]);
                        static::notifyConsultant($r);
                        Notification::make()->title('تم اعتماد الطلب')->body('أُرسل إشعار للمستشار.')->success()->send();
                    }),

                Tables\Actions\Action::make('markPaid')
                    ->label('تم التحويل')->icon('heroicon-o-check-circle')->color('success')
                    ->visible(fn (WithdrawalRequest $r) => in_array($r->status, [
                        WithdrawalRequest::STATUS_PENDING, WithdrawalRequest::STATUS_APPROVED,
                    ]))
                    ->form([
                        Forms\Components\TextInput::make('payment_reference')->label('مرجع التحويل البنكي')->required(),
                        Forms\Components\Textarea::make('admin_notes')->label('ملاحظات')->rows(2),
                    ])
                    ->action(function (WithdrawalRequest $r, array $data) {
                        $r->update([
                            'status'            => WithdrawalRequest::STATUS_PAID,
                            'payment_reference' => $data['payment_reference'],
                            'admin_notes'       => $data['admin_notes'] ?? null,
                            'processed_by'      => auth()->id(),
                            'processed_at'      => $r->processed_at ?? now(),
                            'paid_at'           => now(),
                        ]);
                        static::notifyConsultant($r);
                        Notification::make()->title('تم تسجيل التحويل بنجاح')->body('أُرسل إشعار بالتحويل للمستشار.')->success()->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('رفض')->icon('heroicon-o-x-circle')->color('danger')
                    ->visible(fn (WithdrawalRequest $r) => $r->status === WithdrawalRequest::STATUS_PENDING)
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')->label('سبب الرفض')->required()->rows(3),
                    ])
                    ->action(function (WithdrawalRequest $r, array $data) {
                        $r->update([
                            'status'       => WithdrawalRequest::STATUS_REJECTED,
                            'admin_notes'  => $data['admin_notes'],
                            'processed_by' => auth()->id(),
                            'processed_at' => now(),
                        ]);
                        static::notifyConsultant($r);
                        Notification::make()->title('تم رفض الطلب')->body('أُعيد المبلغ لرصيد المستشار وأُرسل إشعار له.')->warning()->send();
                    }),

                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWithdrawalRequests::route('/'),
            'create' => Pages\CreateWithdrawalRequest::route('/create'),
            'edit'   => Pages\EditWithdrawalRequest::route('/{record}/edit'),
        ];
    }

    /** Sends a bell + email notification to the consultant about the request's new state. */
    protected static function notifyConsultant(WithdrawalRequest $r): void
    {
        $consultantUser = $r->consultant?->user;
        if (! $consultantUser) return;
        try {
            $consultantUser->notify(new WithdrawalStatusChanged($r));
        } catch (\Throwable $e) {
            Log::warning('[Withdrawal notify] failed: ' . $e->getMessage());
        }
    }
}
