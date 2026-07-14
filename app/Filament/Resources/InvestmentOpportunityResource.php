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
                Forms\Components\TextInput::make('title')->label('عنوان الفرصة')->required()->maxLength(200)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set, $get) =>
                        blank($get('slug')) ? $set('slug', Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')->label('المعرّف (slug)')->required()->maxLength(120)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('subtitle')->label('العنوان الفرعي')->maxLength(200)->columnSpanFull(),
                Forms\Components\Textarea::make('summary')->label('الملخص')->rows(2)->columnSpanFull(),
                Forms\Components\Textarea::make('description')->label('الوصف الكامل')->rows(5)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('التصنيف والموقع')->schema([
                Forms\Components\TextInput::make('sector')->label('القطاع')->required()->maxLength(100),
                Forms\Components\TextInput::make('city')->label('المدينة')->maxLength(100),
                Forms\Components\TextInput::make('region')->label('المنطقة')->maxLength(100),
                Forms\Components\Select::make('risk_level')->label('مستوى المخاطرة')
                    ->options(['low' => 'منخفض', 'medium' => 'متوسط', 'high' => 'عالٍ']),
            ])->columns(2),

            Forms\Components\Section::make('البيانات المالية')->schema([
                Forms\Components\TextInput::make('investment_min')->label('الحد الأدنى للاستثمار (ر.س)')->numeric()->prefix('ر.س'),
                Forms\Components\TextInput::make('investment_max')->label('الحد الأقصى للاستثمار (ر.س)')->numeric()->prefix('ر.س'),
                Forms\Components\TextInput::make('expected_roi')->label('العائد المتوقع (%)')->numeric()->suffix('%'),
                Forms\Components\TextInput::make('payback_months')->label('استرداد رأس المال (شهر)')->numeric()->suffix('شهر'),
                Forms\Components\TextInput::make('duration_years')->label('مدة المشروع (سنة)')->numeric()->suffix('سنة'),
            ])->columns(2),

            Forms\Components\Section::make('العناصر البصرية')->schema([
                Forms\Components\FileUpload::make('cover_image')
                    ->label('صورة الغلاف')->image()
                    ->imageEditor()->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                    ->imagePreviewHeight('220')
                    ->disk('public')->directory('investments')->maxSize(5120)
                    ->downloadable()->openable()->deletable()
                    ->columnSpanFull()
                    ->helperText('صورة الفرصة الاستثمارية · 1200×800 · حتى 5MB'),
            ]),

            Forms\Components\Section::make('المصدر والتوثيق')->schema([
                Forms\Components\TextInput::make('source')->label('المصدر')->maxLength(100)->placeholder('مثل: وزارة الاستثمار'),
                Forms\Components\TextInput::make('source_name')->label('اسم المرجع')->maxLength(150),
                Forms\Components\TextInput::make('source_url')->label('رابط المصدر')->url()->columnSpanFull(),
                Forms\Components\TextInput::make('external_ref')->label('المرجع الخارجي')->maxLength(100),
            ])->columns(2)->collapsed(),

            // ═══════════ RICH CONTENT SECTIONS (all optional) ═══════════
            Forms\Components\Section::make('المحتوى التفصيلي (يظهر في صفحة الفرصة)')
                ->description('كل الحقول اختيارية — الحقول الفارغة لا تظهر في الموقع الخارجي')
                ->collapsible()->schema([
                    Forms\Components\Textarea::make('rich_content.executive_summary')
                        ->label('الملخص التنفيذي')
                        ->rows(4)->columnSpanFull(),

                    Forms\Components\Repeater::make('rich_content.investment_highlights')
                        ->label('أبرز مؤشرات الاستثمار')
                        ->schema([
                            Forms\Components\TextInput::make('label')->label('العنوان')->required(),
                            Forms\Components\TextInput::make('value')->label('القيمة')->required(),
                            Forms\Components\Select::make('icon')->label('الأيقونة')->native(false)->options([
                                'wallet' => '💼 محفظة', 'trend' => '📈 نمو',
                                'clock' => '⏱️ استرداد', 'coin' => '💰 عائد',
                                'chart' => '📊 حصة سوق', 'balance' => '⚖️ تعادل',
                            ])->default('chart'),
                        ])->columns(3)->columnSpanFull()->collapsed()
                        ->itemLabel(fn (?array $s = []) => data_get($s, 'label') ?: 'مؤشر')
                        ->addActionLabel('إضافة مؤشر')->reorderable(),

                    Forms\Components\Repeater::make('rich_content.opportunity_reasons')
                        ->label('لماذا هذه الفرصة الآن؟')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('العنوان')->required(),
                            Forms\Components\Textarea::make('desc')->label('الشرح')->rows(2)->required(),
                        ])->columns(2)->collapsed()->columnSpanFull()
                        ->itemLabel(fn (?array $s = []) => data_get($s, 'title') ?: 'سبب')
                        ->addActionLabel('إضافة سبب')->reorderable(),

                    Forms\Components\Repeater::make('rich_content.market_data')
                        ->label('بيانات السوق')
                        ->schema([
                            Forms\Components\TextInput::make('label')->label('التسمية')->required(),
                            Forms\Components\TextInput::make('value')->label('القيمة')->required(),
                        ])->columns(2)->collapsed()->columnSpanFull()
                        ->itemLabel(fn (?array $s = []) => data_get($s, 'label') ?: 'بيان')
                        ->addActionLabel('إضافة بيان')->reorderable(),

                    Forms\Components\Repeater::make('rich_content.financial_projections')
                        ->label('التوقعات المالية (سنوات)')
                        ->schema([
                            Forms\Components\TextInput::make('year')->label('السنة')->required()->placeholder('السنة 1'),
                            Forms\Components\TextInput::make('revenue')->label('الإيرادات')->placeholder('3.2 مليون ر.س'),
                            Forms\Components\TextInput::make('profit')->label('صافي الربح')->placeholder('850 ألف ر.س'),
                        ])->columns(3)->collapsed()->columnSpanFull()
                        ->itemLabel(fn (?array $s = []) => data_get($s, 'year') ?: 'سنة')
                        ->addActionLabel('إضافة سنة')->reorderable(),

                    Forms\Components\Repeater::make('rich_content.timeline')
                        ->label('الجدول الزمني للتنفيذ')
                        ->schema([
                            Forms\Components\TextInput::make('phase')->label('المرحلة')->required(),
                            Forms\Components\TextInput::make('duration')->label('المدة')->required()->placeholder('الشهر 1–3'),
                            Forms\Components\Textarea::make('desc')->label('الوصف')->rows(2)->required()->columnSpanFull(),
                        ])->columns(2)->collapsed()->columnSpanFull()
                        ->itemLabel(fn (?array $s = []) => data_get($s, 'phase') ?: 'مرحلة')
                        ->addActionLabel('إضافة مرحلة')->reorderable(),

                    Forms\Components\Repeater::make('rich_content.risks')
                        ->label('المخاطر وطرق تجاوزها')
                        ->schema([
                            Forms\Components\Textarea::make('risk')->label('الخطر')->rows(2)->required(),
                            Forms\Components\Textarea::make('mitigation')->label('طريقة التخفيف')->rows(2)->required(),
                        ])->columns(2)->collapsed()->columnSpanFull()
                        ->itemLabel(fn (?array $s = []) => \Illuminate\Support\Str::limit(data_get($s, 'risk') ?: 'خطر', 40))
                        ->addActionLabel('إضافة خطر')->reorderable(),

                    Forms\Components\Repeater::make('rich_content.investor_perks')
                        ->label('ماذا يحصل عليه المستثمر')
                        ->schema([
                            Forms\Components\TextInput::make('item')->hiddenLabel()->required()->columnSpanFull()
                                ->placeholder('مثال: حصة تصويتية في المجلس'),
                        ])
                        ->collapsed()->reorderable()->columnSpanFull()
                        ->addActionLabel('إضافة ميزة')
                        ->itemLabel(fn (?array $s = []) => \Illuminate\Support\Str::limit(data_get($s, 'item') ?: 'ميزة', 60)),
                ]),

            Forms\Components\Section::make('النشر والحالة')->schema([
                Forms\Components\Select::make('status')->label('الحالة')
                    ->options([
                        'draft'     => 'مسودة',
                        'published' => 'منشورة',
                        'closed'    => 'مغلقة',
                    ])->default('draft')->required(),
                Forms\Components\Toggle::make('is_featured')->label('مميّزة')->inline(false),
                Forms\Components\DateTimePicker::make('published_at')->label('تاريخ النشر'),
                Forms\Components\DateTimePicker::make('deadline_at')->label('تاريخ انتهاء الفرصة'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')->label('')->size(40)->disk('public')->circular(),
                Tables\Columns\TextColumn::make('title')->label('العنوان')->searchable()->weight('bold')
                    ->description(fn ($record) => $record->subtitle),
                Tables\Columns\TextColumn::make('sector')->label('القطاع')->badge()->color('info'),
                Tables\Columns\TextColumn::make('city')->label('المدينة')->toggleable()->color('gray'),
                Tables\Columns\TextColumn::make('investment_min')->label('من')->money('SAR')->toggleable(),
                Tables\Columns\TextColumn::make('expected_roi')->label('العائد')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}%" : '—')
                    ->color('success')->weight('bold'),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->color(fn ($state) => match ($state) {
                        'published' => 'success', 'draft' => 'warning', 'closed' => 'danger', default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => [
                        'draft' => 'مسودة', 'published' => 'منشورة', 'closed' => 'مغلقة',
                    ][$state] ?? $state),
                Tables\Columns\IconColumn::make('is_featured')->label('')->boolean()->trueIcon('heroicon-s-star')->trueColor('warning')->falseIcon(''),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'draft' => 'مسودة', 'published' => 'منشورة', 'closed' => 'مغلقة',
                ]),
                Tables\Filters\TernaryFilter::make('is_featured')->label('مميّزة'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->tooltip('تعديل'),
                Tables\Actions\DeleteAction::make()->iconButton()->tooltip('حذف'),
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
