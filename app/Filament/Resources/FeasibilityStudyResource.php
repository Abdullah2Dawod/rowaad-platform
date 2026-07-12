<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeasibilityStudyResource\Pages;
use App\Models\FeasibilityStudy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeasibilityStudyResource extends Resource
{
    protected static ?string $model = FeasibilityStudy::class;

    protected static ?string $navigationIcon    = 'heroicon-o-document-text';
    protected static ?string $navigationLabel   = 'دراسات الجدوى';
    protected static ?string $modelLabel        = 'دراسة';
    protected static ?string $pluralModelLabel  = 'دراسات الجدوى';
    protected static ?string $navigationGroup   = 'الموقع العام';
    protected static ?int    $navigationSort    = 10;

    // Admin-only
    public static function canViewAny(): bool { return auth()->user()?->role === 'admin'; }
    public static function shouldRegisterNavigation(): bool { return auth()->user()?->role === 'admin'; }

    public static function getNavigationBadge(): ?string
    {
        return (string) FeasibilityStudy::pending()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getNavigationBadge() > '0' ? 'warning' : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('البيانات الأساسية')->columns(2)->schema([
                Forms\Components\TextInput::make('title')->label('العنوان')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('sector')->label('القطاع'),
                Forms\Components\Select::make('specialization_id')->label('التخصص')
                    ->relationship('specialization', 'name_ar')->searchable(),
                Forms\Components\Textarea::make('excerpt')->label('الملخّص')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('description')->label('الوصف الكامل')->rows(8)->columnSpanFull(),
            ]),
            Forms\Components\Section::make('التسعير والحالة')->columns(3)->schema([
                Forms\Components\TextInput::make('price')->label('السعر (ر.س)')->numeric()->prefix('ر.س'),
                Forms\Components\Toggle::make('is_free')->label('مجانية'),
                Forms\Components\Toggle::make('is_featured')->label('مميّزة'),
                Forms\Components\Select::make('status')->label('الحالة')->options([
                    'pending' => 'قيد المراجعة', 'approved' => 'معتمدة',
                    'rejected' => 'مرفوضة', 'hidden' => 'مخفية',
                ])->required()->native(false),
                Forms\Components\TextInput::make('pages_count')->label('عدد الصفحات')->numeric(),
                Forms\Components\Textarea::make('rejection_reason')->label('سبب الرفض')->rows(3)->columnSpanFull()
                    ->visible(fn ($get) => $get('status') === 'rejected'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')->label('')->size(50)->square(),
                Tables\Columns\TextColumn::make('title')->label('العنوان')->searchable()->weight('bold')
                    ->description(fn ($record) => $record->sector)->wrap(),
                Tables\Columns\TextColumn::make('uploader.name')->label('المرسِل')
                    ->formatStateUsing(fn ($state) => $state ?: 'المنصة')->badge()
                    ->color(fn ($state) => $state ? 'info' : 'gray'),
                Tables\Columns\TextColumn::make('price')->label('السعر')
                    ->formatStateUsing(fn ($state, $record) => $record->is_free ? 'مجاناً' : number_format((float) $state, 0) . ' ر.س')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->formatStateUsing(fn (string $state): string => [
                        'pending' => 'قيد المراجعة', 'approved' => 'معتمدة',
                        'rejected' => 'مرفوضة', 'hidden' => 'مخفية',
                    ][$state] ?? $state)
                    ->color(fn (string $state): string => [
                        'pending' => 'warning', 'approved' => 'success',
                        'rejected' => 'danger', 'hidden' => 'gray',
                    ][$state] ?? 'gray'),
                Tables\Columns\TextColumn::make('purchases_count')->label('المبيعات')->sortable(),
                Tables\Columns\TextColumn::make('views_count')->label('المشاهدات')->sortable()->toggleable(),
                Tables\Columns\IconColumn::make('is_featured')->label('مميّز')->boolean()->trueIcon('heroicon-s-star')->trueColor('warning'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('الحالة')->options([
                    'pending' => 'قيد المراجعة', 'approved' => 'معتمدة',
                    'rejected' => 'مرفوضة', 'hidden' => 'مخفية',
                ])->default('pending'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')->label('اعتماد')->icon('heroicon-o-check-badge')->color('success')
                    ->visible(fn (FeasibilityStudy $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (FeasibilityStudy $record) {
                        $record->update(['status' => 'approved', 'reviewed_at' => now(), 'reviewed_by' => auth()->id()]);
                        Notification::make()->title('تم اعتماد الدراسة')->success()->send();
                    }),
                Tables\Actions\Action::make('reject')->label('رفض')->icon('heroicon-o-x-circle')->color('danger')
                    ->form([Forms\Components\Textarea::make('rejection_reason')->label('السبب')->required()->rows(3)])
                    ->visible(fn (FeasibilityStudy $record) => $record->status === 'pending')
                    ->action(function (FeasibilityStudy $record, array $data) {
                        $record->update(['status' => 'rejected', 'rejection_reason' => $data['rejection_reason'], 'reviewed_at' => now(), 'reviewed_by' => auth()->id()]);
                        Notification::make()->title('تم رفض الدراسة')->warning()->send();
                    }),
                Tables\Actions\EditAction::make()->label('تعديل'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFeasibilityStudies::route('/'),
            'create' => Pages\CreateFeasibilityStudy::route('/create'),
            'edit'   => Pages\EditFeasibilityStudy::route('/{record}/edit'),
        ];
    }
}
