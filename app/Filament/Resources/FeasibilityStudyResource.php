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

            // ═══════════ RICH CONTENT SECTIONS (all optional) ═══════════
            Forms\Components\Section::make('المحتوى التفصيلي (يظهر في صفحة الدراسة)')
                ->description('كل الحقول اختيارية — الحقول الفارغة لن تظهر في الموقع الخارجي')
                ->collapsible()->schema([
                    Forms\Components\Textarea::make('rich_content.summary')
                        ->label('الملخص التنفيذي')
                        ->rows(4)->columnSpanFull(),

                    Forms\Components\Repeater::make('rich_content.financials')
                        ->label('المؤشرات المالية')
                        ->schema([
                            Forms\Components\TextInput::make('label')->label('العنوان')->placeholder('مثال: العائد السنوي المتوقع')->required(),
                            Forms\Components\TextInput::make('value')->label('القيمة')->placeholder('مثال: 38%')->required(),
                            Forms\Components\Select::make('icon')->label('الأيقونة')->native(false)->options([
                                'wallet' => '💼 محفظة', 'trend' => '📈 نمو',
                                'clock' => '⏱️ ساعة', 'coin' => '💰 عملة',
                                'chart' => '📊 مخطط', 'balance' => '⚖️ ميزان',
                            ])->default('chart'),
                        ])->columns(3)->columnSpanFull()->collapsed()
                        ->itemLabel(fn (?array $s = []) => data_get($s, 'label') ?: 'مؤشر مالي')->reorderable(),

                    Forms\Components\Repeater::make('rich_content.target_market')
                        ->label('السوق المستهدف')
                        ->schema([
                            Forms\Components\TextInput::make('item')->hiddenLabel()->required()->placeholder('مثال: الشباب المهني في الرياض')->columnSpanFull(),
                        ])
                        ->addActionLabel('إضافة شريحة سوقية')
                        ->columnSpanFull()->collapsed()->reorderable()
                        ->itemLabel(fn (?array $s = []) => \Illuminate\Support\Str::limit(data_get($s, 'item') ?: 'شريحة', 60)),

                    Forms\Components\Repeater::make('rich_content.advantages')
                        ->label('مميزات المشروع')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('العنوان')->required(),
                            Forms\Components\Textarea::make('desc')->label('الوصف')->rows(2)->required(),
                        ])->columns(2)->columnSpanFull()->collapsed()
                        ->itemLabel(fn (?array $s = []) => data_get($s, 'title') ?: 'ميزة')->reorderable(),

                    Forms\Components\Repeater::make('rich_content.phases')
                        ->label('مراحل التنفيذ')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('اسم المرحلة')->required(),
                            Forms\Components\TextInput::make('duration')->label('المدة')->placeholder('مثال: الشهر 1–3')->required(),
                            Forms\Components\Textarea::make('desc')->label('الوصف')->rows(2)->required()->columnSpanFull(),
                        ])->columns(2)->columnSpanFull()->collapsed()
                        ->itemLabel(fn (?array $s = []) => data_get($s, 'title') ?: 'مرحلة')->reorderable(),

                    Forms\Components\Repeater::make('rich_content.risks')
                        ->label('المخاطر وحلولها')
                        ->schema([
                            Forms\Components\Textarea::make('risk')->label('الخطر')->rows(2)->required(),
                            Forms\Components\Textarea::make('mitigation')->label('الحل المقترح')->rows(2)->required(),
                        ])->columns(2)->columnSpanFull()->collapsed()
                        ->itemLabel(fn (?array $s = []) => \Illuminate\Support\Str::limit(data_get($s, 'risk') ?: 'خطر', 40))->reorderable(),

                    Forms\Components\Repeater::make('rich_content.includes')
                        ->label('ماذا سيحصل عليه المشتري')
                        ->schema([
                            Forms\Components\TextInput::make('item')->hiddenLabel()->required()->placeholder('مثال: ملف PDF كامل بـ 80 صفحة')->columnSpanFull(),
                        ])
                        ->addActionLabel('إضافة بند')
                        ->columnSpanFull()->collapsed()->reorderable()
                        ->itemLabel(fn (?array $s = []) => \Illuminate\Support\Str::limit(data_get($s, 'item') ?: 'بند', 60)),
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
                Tables\Columns\TextColumn::make('source')->label('المصدر')
                    ->state(fn (FeasibilityStudy $record) => $record->user_id ? 'user' : 'admin')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'user' ? 'من طرف مستخدم' : 'من طرف رواد')
                    ->color(fn (string $state) => $state === 'user' ? 'warning' : 'primary')
                    ->icon(fn (string $state) => $state === 'user' ? 'heroicon-o-user' : 'heroicon-o-shield-check'),
                Tables\Columns\TextColumn::make('uploader.name')->label('المرسِل')
                    ->formatStateUsing(fn ($state) => $state ?: 'المنصة')
                    ->color('gray')->size('xs')->toggleable(),
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
                ]),
                Tables\Filters\SelectFilter::make('source')->label('المصدر')
                    ->options(['admin' => 'من طرف رواد', 'user' => 'من طرف مستخدم'])
                    ->query(fn ($query, $data) => match ($data['value'] ?? null) {
                        'admin' => $query->whereNull('user_id'),
                        'user'  => $query->whereNotNull('user_id'),
                        default => $query,
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')->iconButton()->tooltip('اعتماد ونشر')->icon('heroicon-o-check-badge')->color('success')
                    ->visible(fn (FeasibilityStudy $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (FeasibilityStudy $record) {
                        $record->update(['status' => 'approved', 'reviewed_at' => now(), 'reviewed_by' => auth()->id()]);
                        Notification::make()->title('تم اعتماد الدراسة ونشرها في الموقع')->success()->send();
                    }),
                Tables\Actions\Action::make('reject')->iconButton()->tooltip('رفض')->icon('heroicon-o-x-circle')->color('danger')
                    ->form([Forms\Components\Textarea::make('rejection_reason')->label('السبب')->required()->rows(3)])
                    ->visible(fn (FeasibilityStudy $record) => $record->status === 'pending')
                    ->action(function (FeasibilityStudy $record, array $data) {
                        $record->update(['status' => 'rejected', 'rejection_reason' => $data['rejection_reason'], 'reviewed_at' => now(), 'reviewed_by' => auth()->id()]);
                        Notification::make()->title('تم رفض الدراسة')->warning()->send();
                    }),
                Tables\Actions\Action::make('togglePublish')->iconButton()
                    ->tooltip(fn (FeasibilityStudy $r) => $r->status === 'approved' ? 'إخفاء' : 'نشر')
                    ->icon(fn (FeasibilityStudy $r) => $r->status === 'approved' ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn (FeasibilityStudy $r) => $r->status === 'approved' ? 'gray' : 'success')
                    ->visible(fn (FeasibilityStudy $r) => in_array($r->status, ['approved', 'hidden']))
                    ->action(function (FeasibilityStudy $record) {
                        $newStatus = $record->status === 'approved' ? 'hidden' : 'approved';
                        $record->update(['status' => $newStatus]);
                        Notification::make()->title($newStatus === 'approved' ? 'تم النشر' : 'تم الإخفاء')->success()->send();
                    }),
                Tables\Actions\EditAction::make()->iconButton()->tooltip('تعديل'),
                Tables\Actions\DeleteAction::make()->iconButton()->tooltip('حذف'),
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
