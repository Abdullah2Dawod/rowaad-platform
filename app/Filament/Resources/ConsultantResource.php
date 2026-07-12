<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultantResource\Pages;
use App\Models\Consultant;
use App\Notifications\ConsultantApproved;
use App\Notifications\ConsultantRejected;
use Filament\Forms;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ConsultantResource extends Resource
{
    protected static ?string $model = Consultant::class;

    protected static ?string $navigationIcon    = 'heroicon-o-user-group';
    protected static ?string $navigationLabel   = 'المستشارون';
    protected static ?string $modelLabel        = 'مستشار';
    protected static ?string $pluralModelLabel  = 'المستشارون';
    protected static ?string $navigationGroup   = 'الموقع العام';
    protected static ?int    $navigationSort    = 10;

    // Admin-only — consultants don't manage other consultants
    public static function canViewAny(): bool { return auth()->user()?->role === 'admin'; }
    public static function shouldRegisterNavigation(): bool { return auth()->user()?->role === 'admin'; }

    public static function getNavigationBadge(): ?string
    {
        // Count both: pending applications + consultants with pending profile changes
        $applications  = Consultant::pending()->count();
        $pendingEdits  = Consultant::whereNotNull('pending_changes')->count();
        $total = $applications + $pendingEdits;
        return $total > 0 ? (string) $total : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        $apps   = Consultant::pending()->count();
        $edits  = Consultant::whereNotNull('pending_changes')->count();
        $parts = [];
        if ($apps > 0)  $parts[] = "{$apps} طلب انضمام";
        if ($edits > 0) $parts[] = "{$edits} تعديل ملف معلّق";
        return $parts ? implode(' · ', $parts) : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getNavigationBadge() ? 'warning' : null;
    }

    // ============= FORM =============
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('حالة الطلب')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('الحالة')
                        ->options([
                            Consultant::STATUS_DRAFT     => 'مسودة',
                            Consultant::STATUS_SUBMITTED => 'قيد المراجعة',
                            Consultant::STATUS_APPROVED  => 'معتمد',
                            Consultant::STATUS_REJECTED  => 'مرفوض',
                        ])
                        ->required()
                        ->native(false),
                    Forms\Components\Toggle::make('is_featured')
                        ->label('مميّز')
                        ->inline(false),
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('سبب الرفض')
                        ->rows(3)
                        ->columnSpanFull()
                        ->visible(fn (callable $get) => $get('status') === Consultant::STATUS_REJECTED),
                ]),

            Forms\Components\Section::make('الملف الشخصي')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('full_name_ar')->label('الاسم (عربي)'),
                    Forms\Components\TextInput::make('full_name_en')->label('الاسم (إنجليزي)'),
                    Forms\Components\TextInput::make('professional_title')->label('المسمى المهني')->columnSpanFull(),
                    Forms\Components\TextInput::make('city')->label('المدينة'),
                    Forms\Components\TextInput::make('country')->label('الدولة'),
                    Forms\Components\Textarea::make('bio_ar')->label('نبذة')->rows(4)->columnSpanFull(),
                ]),

            Forms\Components\Section::make('التخصص والتسعير')
                ->columns(3)
                ->schema([
                    Forms\Components\Select::make('specialization_id')
                        ->label('التخصص الرئيسي')
                        ->relationship('specialization', 'name_ar')
                        ->searchable(),
                    Forms\Components\TextInput::make('years_experience')->label('سنوات الخبرة')->numeric(),
                    Forms\Components\TextInput::make('hourly_rate')->label('سعر الجلسة (ر.س)')->numeric()->prefix('ر.س'),
                ]),
        ]);
    }

    // ============= TABLE =============
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Uses the model accessor `avatar_url` which handles both
                // stored disk paths and legacy absolute URLs — no `disk('public')`
                // here because the accessor already returns a full URL.
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(fn () => 'https://api.iconify.design/solar:user-bold-duotone.svg?color=%233DAFB9&width=48'),

                Tables\Columns\TextColumn::make('full_name_ar')
                    ->label('الاسم')
                    ->searchable(['full_name_ar', 'full_name_en'])
                    ->description(fn (Consultant $c) => $c->professional_title)
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('specialization.name_ar')
                    ->label('التخصص')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => [
                        Consultant::STATUS_DRAFT     => 'مسودة',
                        Consultant::STATUS_SUBMITTED => 'قيد المراجعة',
                        Consultant::STATUS_APPROVED  => 'معتمد',
                        Consultant::STATUS_REJECTED  => 'مرفوض',
                    ][$state] ?? $state)
                    ->color(fn (string $state): string => [
                        Consultant::STATUS_DRAFT     => 'gray',
                        Consultant::STATUS_SUBMITTED => 'warning',
                        Consultant::STATUS_APPROVED  => 'success',
                        Consultant::STATUS_REJECTED  => 'danger',
                    ][$state] ?? 'gray'),

                Tables\Columns\TextColumn::make('hourly_rate')
                    ->label('السعر')
                    ->money('SAR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating_avg')
                    ->label('التقييم')
                    ->formatStateUsing(fn ($state, $record) => $state > 0
                        ? '⭐ ' . number_format($state, 1) . " ({$record->rating_count})"
                        : '—')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميّز')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('')
                    ->trueColor('warning'),

                Tables\Columns\TextColumn::make('pending_changes')
                    ->label('تعديلات معلّقة')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->formatStateUsing(fn ($state) => is_array($state) && count($state) > 0
                        ? count($state) . ' حقل بانتظار الاعتماد'
                        : null)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('تاريخ التقديم')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('اُعتمد في')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        Consultant::STATUS_DRAFT     => 'مسودة',
                        Consultant::STATUS_SUBMITTED => 'قيد المراجعة',
                        Consultant::STATUS_APPROVED  => 'معتمد',
                        Consultant::STATUS_REJECTED  => 'مرفوض',
                    ]),
                    // No default — show all so approved consultants with pending edits are visible.

                Tables\Filters\SelectFilter::make('specialization_id')
                    ->label('التخصص')
                    ->relationship('specialization', 'name_ar')
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('is_featured')->label('مميّز'),

                Tables\Filters\Filter::make('has_pending_changes')
                    ->label('لديه تعديلات معلّقة فقط')
                    ->query(fn ($query) => $query->whereNotNull('pending_changes')),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('اعتماد')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('اعتماد المستشار')
                    ->modalDescription('سيظهر المستشار للعملاء ويتمكن من استقبال الحجوزات.')
                    ->visible(fn (Consultant $c) => $c->status === Consultant::STATUS_SUBMITTED)
                    ->action(function (Consultant $c) {
                        $c->update([
                            'status'      => Consultant::STATUS_APPROVED,
                            'approved_at' => now(),
                            'reviewed_by' => auth()->id(),
                            'rejection_reason' => null,
                        ]);

                        // Generate a memorable random password
                        $password = Str::upper(Str::random(4)) . '-' . random_int(1000, 9999);

                        $c->user->update([
                            'role'              => 'consultant',
                            'password'          => Hash::make($password),
                            'email_verified_at' => now(),
                        ]);

                        // Email consultant credentials + persist in-app notification
                        try {
                            $c->user->notify(new ConsultantApproved($c, $password));
                            $bodyMsg = "أُرسلت بيانات الدخول إلى بريد {$c->user->email}";
                        } catch (\Throwable $e) {
                            \Log::warning('[Consultant approved mail] failed: ' . $e->getMessage());
                            $bodyMsg = "الاعتماد ناجح — لكن فشل إرسال البريد. كلمة المرور: {$password}";
                        }

                        Notification::make()
                            ->title('تم اعتماد المستشار')
                            ->body($bodyMsg)
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('رفض')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('سبب الرفض')
                            ->required()
                            ->rows(4)
                            ->placeholder('اشرح للمستشار سبب عدم الاعتماد ليتمكن من تحسين طلبه.'),
                    ])
                    ->visible(fn (Consultant $c) => in_array($c->status, [Consultant::STATUS_SUBMITTED, Consultant::STATUS_APPROVED]))
                    ->action(function (Consultant $c, array $data) {
                        $c->update([
                            'status'           => Consultant::STATUS_REJECTED,
                            'rejection_reason' => $data['rejection_reason'],
                            'reviewed_by'      => auth()->id(),
                        ]);

                        // Notify the consultant (mail failures don't break the action)
                        try {
                            $c->user->notify(new ConsultantRejected($c, $data['rejection_reason']));
                        } catch (\Throwable $e) {
                            \Log::warning('[Consultant rejected mail] failed: ' . $e->getMessage());
                        }

                        Notification::make()
                            ->title('تم رفض الطلب')
                            ->body('سيتم إخطار المستشار بسبب الرفض.')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\Action::make('reviewPending')
                    ->label('مراجعة التعديلات المعلّقة')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('warning')
                    ->visible(fn ($record) => $record && $record->hasPendingChanges())
                    ->modalHeading('التعديلات المعلّقة من المستشار')
                    ->modalWidth('4xl')
                    ->modalIcon('heroicon-o-clipboard-document-check')
                    ->modalContent(fn ($record) => view('filament.partials.consultant-pending-changes', [
                        'consultant' => $record,
                        'changes'    => (array) ($record->pending_changes ?? []),
                    ]))
                    ->modalSubmitActionLabel('✓ اعتماد كل التعديلات')
                    ->modalCancelActionLabel('إغلاق')
                    ->action(function ($record) {
                        $record->applyPendingChanges(auth()->user());
                        Notification::make()
                            ->title('تم اعتماد التعديلات وتطبيقها على الملف العام')
                            ->body('أُرسل إشعار بريدي للمستشار.')
                            ->success()->send();
                    }),

                // Separate reject action — asks for a reason to send to the consultant
                Tables\Actions\Action::make('rejectPending')
                    ->label('رفض التعديلات')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record && $record->hasPendingChanges())
                    ->modalHeading('رفض تعديلات المستشار')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('سبب الرفض')
                            ->required()
                            ->rows(4)
                            ->placeholder('اكتب سبباً واضحاً — سيصل للمستشار بالبريد ليتمكّن من تحسين التعديل.'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->rejectPendingChanges(auth()->user(), $data['reason'] ?? null);
                        Notification::make()
                            ->title('تم رفض التعديلات')
                            ->body('أُرسل إشعار للمستشار بسبب الرفض.')
                            ->warning()->send();
                    }),

                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف'),
                ]),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->emptyStateHeading('لا يوجد مستشارون')
            ->emptyStateDescription('لم تُقدَّم أي طلبات مستشارين بعد.');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'specialization']);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListConsultants::route('/'),
            'create' => Pages\CreateConsultant::route('/create'),
            'edit'   => Pages\EditConsultant::route('/{record}/edit'),
        ];
    }
}
