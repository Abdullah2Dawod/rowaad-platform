<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeasibilityRequestResource\Pages;
use App\Models\FeasibilityRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeasibilityRequestResource extends Resource
{
    protected static ?string $model = FeasibilityRequest::class;
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'طلبات دراسة الجدوى';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?string $modelLabel      = 'طلب دراسة';
    protected static ?string $pluralModelLabel = 'طلبات الدراسات المخصّصة';
    protected static ?int    $navigationSort  = 30;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) FeasibilityRequest::pending()->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        $statuses = [
            'new' => 'جديد', 'in_review' => 'قيد المراجعة', 'quoted' => 'تم التسعير',
            'accepted' => 'مقبول', 'in_progress' => 'قيد التنفيذ',
            'delivered' => 'تم التسليم', 'closed' => 'مغلق', 'rejected' => 'مرفوض',
        ];

        return $form->schema([
            Forms\Components\Section::make('بيانات مقدّم الطلب')->schema([
                Forms\Components\TextInput::make('reference')->label('المرجع')->disabled(),
                Forms\Components\Select::make('status')->label('الحالة')->options($statuses)->required(),
                Forms\Components\TextInput::make('contact_name')->label('الاسم')->required(),
                Forms\Components\TextInput::make('contact_email')->label('البريد')->email()->required(),
                Forms\Components\TextInput::make('contact_phone')->label('الجوال')->required(),
                Forms\Components\TextInput::make('company_name')->label('الشركة'),
            ])->columns(2),

            Forms\Components\Section::make('تفاصيل المشروع')->schema([
                Forms\Components\TextInput::make('project_title')->label('عنوان المشروع')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('idea_description')->label('وصف الفكرة')->rows(4)->required()->columnSpanFull(),
                Forms\Components\TextInput::make('sector')->label('القطاع')->required(),
                Forms\Components\TextInput::make('sub_sector')->label('القطاع الفرعي'),
                Forms\Components\TextInput::make('city')->label('المدينة'),
                Forms\Components\TextInput::make('country')->label('الدولة')->default('SA'),
                Forms\Components\TextInput::make('estimated_budget')->label('الميزانية التقديرية')->numeric(),
                Forms\Components\TextInput::make('quoted_price')->label('السعر المُقدَّم')->numeric(),
                Forms\Components\DatePicker::make('needed_by')->label('المطلوب بحلول'),
                Forms\Components\Select::make('urgency')->label('الأولوية')->options([
                    'low'=>'منخفضة','normal'=>'عادية','high'=>'عالية','urgent'=>'عاجل',
                ])->default('normal'),
            ])->columns(2),

            Forms\Components\Section::make('ملاحظات الفريق')->schema([
                Forms\Components\Textarea::make('admin_notes')->label('ملاحظات داخلية')->rows(3)->columnSpanFull(),
                Forms\Components\Select::make('assigned_to')->label('مُسنَد إلى')
                    ->relationship('assignee', 'name')->searchable()->preload(),
            ])->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference')->label('المرجع')->fontFamily('mono')->searchable(),
                Tables\Columns\TextColumn::make('contact_name')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('project_title')->label('المشروع')->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('sector')->label('القطاع')->badge()->toggleable(),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->color(fn ($state) => match ($state) {
                        'new'=>'info','in_review'=>'warning',
                        'quoted','accepted','in_progress'=>'primary',
                        'delivered','closed'=>'success','rejected'=>'danger',default=>'gray',
                    })
                    ->formatStateUsing(fn ($state) => [
                        'new'=>'جديد','in_review'=>'مراجعة','quoted'=>'مُسعَّر','accepted'=>'مقبول',
                        'in_progress'=>'قيد التنفيذ','delivered'=>'مُسلَّم','closed'=>'مغلق','rejected'=>'مرفوض',
                    ][$state] ?? $state),
                Tables\Columns\TextColumn::make('estimated_budget')->label('الميزانية')->money('SAR')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('التقديم')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'new'=>'جديد','in_review'=>'مراجعة','quoted'=>'مُسعَّر','accepted'=>'مقبول',
                    'in_progress'=>'قيد التنفيذ','delivered'=>'مُسلَّم','closed'=>'مغلق','rejected'=>'مرفوض',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFeasibilityRequests::route('/'),
            'create' => Pages\CreateFeasibilityRequest::route('/create'),
            'edit'   => Pages\EditFeasibilityRequest::route('/{record}/edit'),
        ];
    }
}
