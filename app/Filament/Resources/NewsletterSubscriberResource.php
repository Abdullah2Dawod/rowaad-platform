<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;
    protected static ?string $navigationIcon  = 'heroicon-o-envelope-open';
    protected static ?string $navigationLabel = 'المشتركون في النشرة';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $modelLabel      = 'مشترك';
    protected static ?string $pluralModelLabel = 'المشتركون';
    protected static ?int    $navigationSort  = 40;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('email')->label('البريد')->email()->required(),
            Forms\Components\TextInput::make('name')->label('الاسم'),
            Forms\Components\Select::make('locale')->label('اللغة')->options(['ar'=>'العربية','en'=>'English'])->default('ar')->required(),
            Forms\Components\TextInput::make('source')->label('المصدر'),
            Forms\Components\DateTimePicker::make('confirmed_at')->label('تاريخ التأكيد'),
            Forms\Components\DateTimePicker::make('unsubscribed_at')->label('تاريخ إلغاء الاشتراك'),
            Forms\Components\TextInput::make('unsubscribe_reason')->label('سبب الإلغاء'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('email')->label('البريد')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('name')->label('الاسم')->toggleable(),
                Tables\Columns\TextColumn::make('locale')->label('اللغة')->badge(),
                Tables\Columns\IconColumn::make('confirmed_at')->label('مؤكَّد')->boolean(),
                Tables\Columns\IconColumn::make('unsubscribed_at')->label('ملغى')->boolean(),
                Tables\Columns\TextColumn::make('source')->label('المصدر')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('الاشتراك')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('confirmed')->label('مؤكَّد فقط')
                    ->query(fn ($q) => $q->whereNotNull('confirmed_at')->whereNull('unsubscribed_at')),
                Tables\Filters\Filter::make('unsubscribed')->label('ملغَى الاشتراك')
                    ->query(fn ($q) => $q->whereNotNull('unsubscribed_at')),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNewsletterSubscribers::route('/'),
            'create' => Pages\CreateNewsletterSubscriber::route('/create'),
            'edit'   => Pages\EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }
}
