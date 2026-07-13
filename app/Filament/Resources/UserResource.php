<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon    = 'heroicon-o-users';
    protected static ?string $navigationLabel   = 'المستخدمون';
    protected static ?string $modelLabel        = 'مستخدم';
    protected static ?string $pluralModelLabel  = 'المستخدمون';

    // Admin-only
    public static function canViewAny(): bool { return auth()->user()?->role === 'admin'; }
    public static function shouldRegisterNavigation(): bool { return auth()->user()?->role === 'admin'; }
    protected static ?string $navigationGroup   = 'إدارة المستخدمين';
    protected static ?int    $navigationSort    = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('الاسم')->required(),
                Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email()->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('role')->label('الدور')->options([
                    'user'       => 'مستخدم',
                    'consultant' => 'مستشار',
                    'admin'      => 'مدير',
                ])->required()->native(false),
                Forms\Components\TextInput::make('phone')->label('الجوال')->tel(),
                Forms\Components\Select::make('locale')->label('اللغة')->options([
                    'ar' => 'العربية', 'en' => 'English',
                ])->default('ar')->native(false),
                Forms\Components\DateTimePicker::make('email_verified_at')->label('تاريخ تفعيل الإيميل'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('email')->label('البريد')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('role')->label('الدور')->badge()
                    ->formatStateUsing(fn (string $state): string => ['user'=>'مستخدم','consultant'=>'مستشار','admin'=>'مدير'][$state] ?? $state)
                    ->color(fn (string $state): string => ['admin'=>'danger','consultant'=>'info','user'=>'gray'][$state] ?? 'gray'),
                Tables\Columns\TextColumn::make('phone')->label('الجوال')->toggleable(),
                Tables\Columns\IconColumn::make('email_verified_at')->label('مفعّل')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('التسجيل')->dateTime('Y-m-d')->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')->label('الدور')->options([
                    'user'=>'مستخدم','consultant'=>'مستشار','admin'=>'مدير',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->tooltip('تعديل'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
