<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecializationResource\Pages;
use App\Models\Specialization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SpecializationResource extends Resource
{
    protected static ?string $model = Specialization::class;

    protected static ?string $navigationIcon    = 'heroicon-o-tag';
    protected static ?string $navigationLabel   = 'التخصصات';
    protected static ?string $modelLabel        = 'تخصص';
    protected static ?string $pluralModelLabel  = 'التخصصات';
    protected static ?string $navigationGroup   = 'إدارة المستشارين';
    protected static ?int    $navigationSort    = 20;

    // Admin-only
    public static function canViewAny(): bool { return auth()->user()?->role === 'admin'; }
    public static function shouldRegisterNavigation(): bool { return auth()->user()?->role === 'admin'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('slug')
                    ->label('المعرّف')->required()->unique(ignoreRecord: true)->alphaDash(),
                Forms\Components\TextInput::make('icon')
                    ->label('أيقونة (Solar)')->placeholder('graph-up-bold-duotone'),
                Forms\Components\TextInput::make('name_ar')->label('الاسم (عربي)')->required(),
                Forms\Components\TextInput::make('name_en')->label('الاسم (إنجليزي)')->required(),
                Forms\Components\Textarea::make('description_ar')->label('الوصف (عربي)')->columnSpanFull()->rows(3),
                Forms\Components\Textarea::make('description_en')->label('الوصف (إنجليزي)')->columnSpanFull()->rows(3),
                Forms\Components\TextInput::make('sort_order')->label('الترتيب')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->label('مفعّل')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->label('#')->sortable()->width(60),
                Tables\Columns\TextColumn::make('name_ar')->label('الاسم')
                    ->description(fn ($record) => $record->name_en)->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('slug')->label('المعرّف')->fontFamily('mono')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('consultants_count')->label('المستشارون')
                    ->counts('consultants')->badge()->color('info'),
                Tables\Columns\IconColumn::make('is_active')->label('مفعّل')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSpecializations::route('/'),
            'create' => Pages\CreateSpecialization::route('/create'),
            'edit'   => Pages\EditSpecialization::route('/{record}/edit'),
        ];
    }
}
