<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectorResource\Pages;
use App\Models\Sector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SectorResource extends Resource
{
    protected static ?string $model = Sector::class;
    protected static ?string $navigationIcon  = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'القطاعات الاقتصادية';
    protected static ?string $navigationGroup = 'الموقع العام';
    protected static ?string $modelLabel      = 'قطاع';
    protected static ?string $pluralModelLabel = 'القطاعات';
    protected static ?int    $navigationSort  = 40;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('البيانات الأساسية')->schema([
                Forms\Components\TextInput::make('name_ar')->label('اسم القطاع (عربي)')->required()->maxLength(150)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set, $get) =>
                        blank($get('slug')) ? $set('slug', Str::slug($state, '-', null)) : null),
                Forms\Components\TextInput::make('name_en')->label('Name (English)')->maxLength(150),
                Forms\Components\TextInput::make('slug')->label('المعرّف (slug)')->required()->maxLength(120)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('color')->label('اللون')->type('color')->default('#3DAFB9'),
                Forms\Components\Textarea::make('description_ar')->label('الوصف (عربي)')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('description_en')->label('Description (English)')->rows(3)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('العناصر البصرية')->schema([
                Forms\Components\TextInput::make('icon')->label('أيقونة Solar')->placeholder('buildings-3-bold-duotone'),
                Forms\Components\FileUpload::make('hero_image')->label('صورة الغلاف')->image()->disk('public')->directory('sectors')->maxSize(4096),
            ])->columns(2),

            Forms\Components\Section::make('المحتوى')->schema([
                Forms\Components\Repeater::make('highlights')->label('الأرقام البارزة')
                    ->schema([
                        Forms\Components\TextInput::make('label')->label('التسمية')->required(),
                        Forms\Components\TextInput::make('value')->label('القيمة')->required(),
                    ])->columns(2)->reorderable()->addActionLabel('إضافة رقم')->defaultItems(0),
                Forms\Components\Repeater::make('opportunities')->label('الفرص الاستثمارية')
                    ->simple(Forms\Components\TextInput::make('opp')->required())
                    ->reorderable()->addActionLabel('إضافة فرصة')->defaultItems(0),
            ]),

            Forms\Components\Section::make('الإعدادات')->schema([
                Forms\Components\Toggle::make('is_active')->label('مفعّل')->default(true),
                Forms\Components\Toggle::make('featured')->label('مميّز'),
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
                Tables\Columns\ImageColumn::make('hero_image')->label('الصورة')->size(48)->disk('public'),
                Tables\Columns\ColorColumn::make('color')->label('اللون'),
                Tables\Columns\TextColumn::make('name_ar')->label('القطاع')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('slug')->label('المعرّف')->fontFamily('mono')->color('gray')->size('sm'),
                Tables\Columns\IconColumn::make('featured')->label('مميّز')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('مفعّل')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('الترتيب')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('الحالة'),
                Tables\Filters\TernaryFilter::make('featured')->label('مميّز'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggleActive')->iconButton()
                    ->tooltip(fn (Sector $r) => $r->is_active ? 'إيقاف' : 'تفعيل')
                    ->icon(fn (Sector $r) => $r->is_active ? 'heroicon-o-pause' : 'heroicon-o-play')
                    ->color(fn (Sector $r) => $r->is_active ? 'warning' : 'success')
                    ->action(fn (Sector $r) => $r->update(['is_active' => ! $r->is_active])),
                Tables\Actions\EditAction::make()->iconButton()->tooltip('تعديل'),
                Tables\Actions\DeleteAction::make()->iconButton()->tooltip('حذف'),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSectors::route('/'),
            'create' => Pages\CreateSector::route('/create'),
            'edit'   => Pages\EditSector::route('/{record}/edit'),
        ];
    }
}
