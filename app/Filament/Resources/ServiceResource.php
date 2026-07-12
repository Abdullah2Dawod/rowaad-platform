<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'الخدمات';
    protected static ?string $navigationGroup = 'الموقع العام';
    protected static ?string $modelLabel      = 'خدمة';
    protected static ?string $pluralModelLabel = 'الخدمات';
    protected static ?int    $navigationSort  = 10;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('البيانات الأساسية')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('عنوان الخدمة')->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set, $get) =>
                        blank($get('slug')) ? $set('slug', Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->label('المعرّف (slug)')->required()->maxLength(120)->unique(ignoreRecord: true)
                    ->helperText('يُستخدم في الرابط: /services/{slug}'),
                Forms\Components\TextInput::make('tagline')->label('السطر التعريفي')->maxLength(255)->columnSpanFull(),
                Forms\Components\Textarea::make('summary')->label('الوصف المختصر')->rows(4)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('العناصر البصرية')->schema([
                Forms\Components\TextInput::make('icon')->label('أيقونة (Solar)')
                    ->helperText('مثال: graph-up-bold-duotone'),
                Forms\Components\FileUpload::make('hero_image')
                    ->label('صورة الغلاف')->image()->disk('public')->directory('services')->maxSize(4096),
            ])->columns(2),

            Forms\Components\Section::make('المحتوى')->schema([
                Forms\Components\Repeater::make('includes')
                    ->label('ما تشمله الخدمة')
                    ->simple(Forms\Components\TextInput::make('item')->required())
                    ->reorderable()->addActionLabel('إضافة عنصر')->defaultItems(0),
                Forms\Components\Repeater::make('deliverables')
                    ->label('المخرجات')
                    ->simple(Forms\Components\TextInput::make('item')->required())
                    ->reorderable()->addActionLabel('إضافة مخرج')->defaultItems(0),
                Forms\Components\Repeater::make('stats')
                    ->label('الإحصائيات')
                    ->schema([
                        Forms\Components\TextInput::make('label')->label('التسمية')->required(),
                        Forms\Components\TextInput::make('value')->label('القيمة')->required(),
                    ])->columns(2)->reorderable()->addActionLabel('إضافة إحصائية')->defaultItems(0),
            ])->columns(1),

            Forms\Components\Section::make('الإعدادات')->schema([
                Forms\Components\Toggle::make('is_active')->label('مفعّلة')->default(true),
                Forms\Components\Toggle::make('featured')->label('مميّزة (تظهر في الصفحة الرئيسية)'),
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
                Tables\Columns\ImageColumn::make('hero_image')->label('الصورة')->circular(),
                Tables\Columns\TextColumn::make('title')->label('العنوان')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('slug')->label('المعرّف')->fontFamily('mono')->color('gray')->size('sm'),
                Tables\Columns\IconColumn::make('featured')->label('مميّزة')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('مفعّلة')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('الترتيب')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('آخر تحديث')->since()->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('الحالة'),
                Tables\Filters\TernaryFilter::make('featured')->label('مميّزة'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggleActive')
                    ->label(fn (Service $r) => $r->is_active ? 'إيقاف' : 'تفعيل')
                    ->icon(fn (Service $r) => $r->is_active ? 'heroicon-o-pause' : 'heroicon-o-play')
                    ->color(fn (Service $r) => $r->is_active ? 'warning' : 'success')
                    ->action(fn (Service $r) => $r->update(['is_active' => ! $r->is_active])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')->label('تفعيل')->icon('heroicon-o-play')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')->label('إيقاف')->icon('heroicon-o-pause')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
