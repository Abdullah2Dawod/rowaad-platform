<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestmentOpportunityResource\Pages;
use App\Models\InvestmentOpportunity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class InvestmentOpportunityResource extends Resource
{
    protected static ?string $model = InvestmentOpportunity::class;
    protected static ?string $navigationIcon  = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'الفرص الاستثمارية';
    protected static ?string $navigationGroup = 'الموقع العام';
    protected static ?string $modelLabel      = 'فرصة استثمارية';
    protected static ?string $pluralModelLabel = 'الفرص الاستثمارية';
    protected static ?int    $navigationSort  = 30;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('البيانات الأساسية')->schema([
                Forms\Components\TextInput::make('title')->label('عنوان الفرصة')->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set, $get) =>
                        blank($get('slug')) ? $set('slug', Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')->label('المعرّف (slug)')->required()->maxLength(120)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('sector')->label('القطاع')->required()->maxLength(100),
                Forms\Components\TextInput::make('location')->label('الموقع')->maxLength(100),
                Forms\Components\Textarea::make('summary')->label('الملخص')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('description')->label('الوصف الكامل')->rows(5)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('البيانات المالية')->schema([
                Forms\Components\TextInput::make('min_investment')->label('الحد الأدنى (ر.س)')->numeric(),
                Forms\Components\TextInput::make('expected_return')->label('العائد المتوقع (%)')->numeric()->suffix('%'),
                Forms\Components\TextInput::make('duration_months')->label('المدة (شهر)')->numeric(),
                Forms\Components\TextInput::make('risk_level')->label('مستوى المخاطرة')->maxLength(50),
            ])->columns(2),

            Forms\Components\Section::make('العناصر البصرية')->schema([
                Forms\Components\TextInput::make('icon')->label('أيقونة Solar')->maxLength(100),
                Forms\Components\FileUpload::make('hero_image')->label('صورة الغلاف')->image()->disk('public')->directory('investments')->maxSize(4096),
            ])->columns(2),

            Forms\Components\Section::make('الإعدادات')->schema([
                Forms\Components\Toggle::make('is_active')->label('مفعّلة')->default(true),
                Forms\Components\Toggle::make('is_featured')->label('مميّزة'),
                Forms\Components\TextInput::make('sort_order')->label('ترتيب العرض')->numeric()->default(0),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('hero_image')->label('')->size(48)->disk('public'),
                Tables\Columns\TextColumn::make('title')->label('العنوان')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('sector')->label('القطاع')->badge()->color('info'),
                Tables\Columns\TextColumn::make('location')->label('الموقع')->toggleable(),
                Tables\Columns\TextColumn::make('min_investment')->label('الحد الأدنى')->money('SAR')->toggleable(),
                Tables\Columns\TextColumn::make('expected_return')->label('العائد')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}%" : '—'),
                Tables\Columns\IconColumn::make('is_featured')->label('مميّزة')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('مفعّلة')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('الحالة'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('مميّزة'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInvestmentOpportunities::route('/'),
            'create' => Pages\CreateInvestmentOpportunity::route('/create'),
            'edit'   => Pages\EditInvestmentOpportunity::route('/{record}/edit'),
        ];
    }
}
