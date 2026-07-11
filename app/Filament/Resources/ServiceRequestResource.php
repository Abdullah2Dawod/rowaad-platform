<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceRequestResource\Pages;
use App\Models\ServiceRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;
    protected static ?string $navigationIcon  = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'طلبات الخدمات والتواصل';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?string $modelLabel      = 'طلب';
    protected static ?string $pluralModelLabel = 'الطلبات والرسائل';
    protected static ?int    $navigationSort  = 35;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) ServiceRequest::where('status', 'new')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('بيانات التواصل')->schema([
                Forms\Components\TextInput::make('service_title')->label('نوع الطلب / الخدمة'),
                Forms\Components\TextInput::make('company_name')->label('الشركة'),
                Forms\Components\TextInput::make('contact_name')->label('الاسم')->required(),
                Forms\Components\TextInput::make('contact_email')->label('البريد')->email()->required(),
                Forms\Components\TextInput::make('contact_phone')->label('الجوال'),
                Forms\Components\Select::make('status')->label('الحالة')->options([
                    'new'=>'جديد','contacted'=>'تم التواصل','qualified'=>'مؤهَّل','won'=>'تم الفوز','lost'=>'مغلق',
                ])->required(),
            ])->columns(2),

            Forms\Components\Section::make('تفاصيل المشروع')->schema([
                Forms\Components\Textarea::make('project_brief')->label('وصف المشروع')->rows(5)->columnSpanFull(),
                Forms\Components\TextInput::make('budget')->label('الميزانية')->numeric(),
                Forms\Components\TextInput::make('timeline')->label('المهلة الزمنية'),
                Forms\Components\TextInput::make('company_size')->label('حجم الشركة'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('contact_name')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('service_title')->label('الطلب')->searchable(),
                Tables\Columns\TextColumn::make('company_name')->label('الشركة')->toggleable(),
                Tables\Columns\TextColumn::make('contact_email')->label('البريد')->toggleable()->copyable(),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()->color(fn ($state) => match($state) {
                    'new'=>'info','contacted'=>'warning','qualified'=>'primary','won'=>'success','lost'=>'danger',default=>'gray',
                })->formatStateUsing(fn ($state) => [
                    'new'=>'جديد','contacted'=>'تم التواصل','qualified'=>'مؤهَّل','won'=>'مقفل بالنجاح','lost'=>'مغلق',
                ][$state] ?? $state),
                Tables\Columns\TextColumn::make('created_at')->label('الاستلام')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'new'=>'جديد','contacted'=>'تم التواصل','qualified'=>'مؤهَّل','won'=>'فوز','lost'=>'مغلق',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServiceRequests::route('/'),
            'create' => Pages\CreateServiceRequest::route('/create'),
            'edit'   => Pages\EditServiceRequest::route('/{record}/edit'),
        ];
    }
}
