<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestmentApplicationResource\Pages;
use App\Models\InvestmentApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvestmentApplicationResource extends Resource
{
    protected static ?string $model = InvestmentApplication::class;
    protected static ?string $navigationIcon  = 'heroicon-o-envelope-open';
    protected static ?string $navigationLabel = 'طلبات الاستثمار';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?string $modelLabel      = 'طلب استثمار';
    protected static ?string $pluralModelLabel = 'طلبات الاستثمار';
    protected static ?int    $navigationSort  = 33;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) InvestmentApplication::where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return InvestmentApplication::where('status', 'new')->exists() ? 'warning' : null;
    }

    public static function form(Form $form): Form
    {
        $statuses = [
            'new'        => 'جديد',
            'contacted'  => 'تم التواصل',
            'qualified'  => 'مؤهَّل',
            'in_review'  => 'قيد المراجعة',
            'accepted'   => 'مقبول',
            'closed'     => 'مقفل',
            'rejected'   => 'مرفوض',
        ];

        return $form->schema([
            Forms\Components\Section::make('بيانات الطلب')->schema([
                Forms\Components\TextInput::make('reference')->label('المرجع')->disabled(),
                Forms\Components\Select::make('opportunity_id')->label('الفرصة الاستثمارية')
                    ->relationship('opportunity', 'title')->disabled(),
                Forms\Components\Select::make('status')->label('الحالة')->options($statuses)->required(),
                Forms\Components\TextInput::make('investment_amount')->label('مبلغ الاستثمار (ر.س)')->numeric()->prefix('ر.س'),
            ])->columns(2),

            Forms\Components\Section::make('بيانات مقدّم الطلب')->schema([
                Forms\Components\TextInput::make('contact_name')->label('الاسم')->required(),
                Forms\Components\TextInput::make('company_name')->label('الشركة'),
                Forms\Components\TextInput::make('contact_email')->label('البريد')->email()->required(),
                Forms\Components\TextInput::make('contact_phone')->label('الجوال')->required(),
            ])->columns(2),

            Forms\Components\Section::make('الرسالة والملاحظات')->schema([
                Forms\Components\Textarea::make('message')->label('رسالة مقدّم الطلب')->rows(4)->disabled(),
                Forms\Components\Textarea::make('admin_notes')->label('ملاحظات إدارية')->rows(4),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference')->label('المرجع')->fontFamily('mono')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('opportunity.title')->label('الفرصة')->limit(35)->searchable(),
                Tables\Columns\TextColumn::make('contact_name')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('company_name')->label('الشركة')->color('gray'),
                Tables\Columns\TextColumn::make('contact_email')->label('البريد')->copyable(),
                Tables\Columns\TextColumn::make('contact_phone')->label('الجوال')->copyable(),
                Tables\Columns\TextColumn::make('investment_amount')->label('المبلغ')->money('SAR')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->color(fn ($state) => match($state) {
                        'new' => 'info', 'contacted' => 'warning', 'qualified' => 'primary',
                        'in_review' => 'primary', 'accepted' => 'success',
                        'closed' => 'success', 'rejected' => 'danger', default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => [
                        'new'=>'جديد','contacted'=>'تم التواصل','qualified'=>'مؤهَّل',
                        'in_review'=>'قيد المراجعة','accepted'=>'مقبول',
                        'closed'=>'مقفل','rejected'=>'مرفوض',
                    ][$state] ?? $state),
                Tables\Columns\TextColumn::make('created_at')->label('الاستلام')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('الحالة')->options([
                    'new'=>'جديد','contacted'=>'تم التواصل','qualified'=>'مؤهَّل',
                    'in_review'=>'قيد المراجعة','accepted'=>'مقبول',
                    'closed'=>'مقفل','rejected'=>'مرفوض',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('markContacted')
                    ->label('تم التواصل')->icon('heroicon-o-phone-arrow-up-right')->color('warning')
                    ->visible(fn (InvestmentApplication $r) => $r->status === 'new')
                    ->action(function (InvestmentApplication $r) {
                        $r->update(['status' => 'contacted']);
                        Notification::make()->title('تم تحديث الحالة')->success()->send();
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
            'index'  => Pages\ListInvestmentApplications::route('/'),
            'edit'   => Pages\EditInvestmentApplication::route('/{record}/edit'),
        ];
    }
}
