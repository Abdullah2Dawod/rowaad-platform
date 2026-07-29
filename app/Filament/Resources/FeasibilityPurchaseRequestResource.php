<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeasibilityPurchaseRequestResource\Pages;
use App\Models\FeasibilityPurchaseRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeasibilityPurchaseRequestResource extends Resource
{
    protected static ?string $model = FeasibilityPurchaseRequest::class;
    protected static ?string $navigationIcon  = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'مشتريات دراسات الجدوى';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?string $modelLabel      = 'طلب شراء دراسة';
    protected static ?string $pluralModelLabel = 'مشتريات دراسات الجدوى';
    protected static ?int    $navigationSort  = 34;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) FeasibilityPurchaseRequest::where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return FeasibilityPurchaseRequest::where('status', 'new')->exists() ? 'warning' : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('بيانات الطلب')->schema([
                Forms\Components\TextInput::make('reference')->label('المرجع')->disabled(),
                Forms\Components\Select::make('study_id')->label('الدراسة')
                    ->relationship('study', 'title')->disabled(),
                Forms\Components\TextInput::make('amount')->label('المبلغ (ر.س)')->numeric()->prefix('ر.س')->disabled(),
                Forms\Components\Select::make('status')->label('الحالة')->options([
                    'new'       => 'جديد',
                    'paid'      => 'مدفوع (السماح بالتحميل)',
                    'delivered' => 'تم التحميل',
                    'cancelled' => 'ملغى',
                ])->required(),
            ])->columns(2),

            Forms\Components\Section::make('بيانات المشتري')->schema([
                Forms\Components\TextInput::make('contact_name')->label('الاسم')->disabled(),
                Forms\Components\TextInput::make('contact_email')->label('البريد')->email()->disabled(),
                Forms\Components\TextInput::make('contact_phone')->label('الجوال')->disabled(),
            ])->columns(3),

            Forms\Components\Section::make('المتابعة الإدارية')->schema([
                Forms\Components\Textarea::make('admin_notes')->label('ملاحظات إدارية')->rows(3),
                Forms\Components\DateTimePicker::make('paid_at')->label('وقت تأكيد الدفع'),
                Forms\Components\DateTimePicker::make('downloaded_at')->label('وقت التحميل')->disabled(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference')->label('المرجع')->fontFamily('mono')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('study.title')->label('الدراسة')->limit(35)->searchable(),
                Tables\Columns\TextColumn::make('contact_name')->label('المشتري')->searchable(),
                Tables\Columns\TextColumn::make('contact_email')->label('البريد')->copyable(),
                Tables\Columns\TextColumn::make('contact_phone')->label('الجوال')->copyable(),
                Tables\Columns\TextColumn::make('amount')->label('المبلغ')->money('SAR')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->color(fn ($state) => match($state) {
                        'new' => 'warning', 'paid' => 'success',
                        'delivered' => 'primary', 'cancelled' => 'danger', default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => [
                        'new'=>'جديد','paid'=>'مدفوع','delivered'=>'تم التحميل','cancelled'=>'ملغى',
                    ][$state] ?? $state),
                Tables\Columns\TextColumn::make('created_at')->label('الاستلام')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'new'=>'جديد','paid'=>'مدفوع','delivered'=>'تم التحميل','cancelled'=>'ملغى',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('markPaid')
                    ->label('تأكيد الدفع')->icon('heroicon-o-check-badge')->color('success')
                    ->visible(fn (FeasibilityPurchaseRequest $r) => $r->status === 'new')
                    ->requiresConfirmation()
                    ->action(function (FeasibilityPurchaseRequest $r) {
                        $r->update(['status' => 'paid', 'paid_at' => now()]);
                        Notification::make()->title('تم تأكيد الدفع')->body('يستطيع المشتري الآن تحميل الدراسة.')->success()->send();
                    }),
                Tables\Actions\EditAction::make()->iconButton()->tooltip('تعديل'),
                Tables\Actions\DeleteAction::make()->iconButton()->tooltip('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ])->label('إجراءات جماعية'),
            ])
            ;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeasibilityPurchaseRequests::route('/'),
            'edit'  => Pages\EditFeasibilityPurchaseRequest::route('/{record}/edit'),
        ];
    }
}
