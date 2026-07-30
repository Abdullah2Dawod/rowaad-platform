<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon  = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'الطلبات (المبيعات)';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?string $modelLabel      = 'طلب';
    protected static ?string $pluralModelLabel = 'الطلبات';
    protected static ?int    $navigationSort  = 25;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Order::where('status', Order::STATUS_PENDING)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return Order::where('status', Order::STATUS_PENDING)->exists() ? 'warning' : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('الطلب')->schema([
                Forms\Components\TextInput::make('reference')->label('المرجع')->disabled(),
                Forms\Components\Select::make('status')->label('الحالة')->options(Order::$STATUS_LABELS)->required(),
                Forms\Components\Select::make('payment_method')->label('طريقة الدفع')->options(Order::$METHOD_LABELS)->disabled(),
                Forms\Components\TextInput::make('payment_reference')->label('مرجع البوابة')->disabled(),
                Forms\Components\TextInput::make('total')->label('الإجمالي')->prefix('ر.س')->disabled(),
                Forms\Components\DateTimePicker::make('paid_at')->label('وقت الدفع')->disabled(),
            ])->columns(2),

            Forms\Components\Section::make('المشتري')->schema([
                Forms\Components\TextInput::make('contact_name')->label('الاسم')->disabled(),
                Forms\Components\TextInput::make('contact_email')->label('البريد')->disabled(),
                Forms\Components\TextInput::make('contact_phone')->label('الجوال')->disabled(),
                Forms\Components\TextInput::make('company_name')->label('الشركة')->disabled(),
                Forms\Components\TextInput::make('tax_id')->label('الرقم الضريبي')->disabled(),
                Forms\Components\Textarea::make('billing_address')->label('العنوان')->rows(2)->columnSpanFull()->disabled(),
            ])->columns(2)->collapsed(),

            Forms\Components\Section::make('عناصر الطلب')->schema([
                Forms\Components\Repeater::make('items')->label('')
                    ->relationship('items')
                    ->schema([
                        Forms\Components\TextInput::make('title')->label('العنوان')->disabled(),
                        Forms\Components\TextInput::make('quantity')->label('الكمية')->disabled(),
                        Forms\Components\TextInput::make('unit_price')->label('السعر')->prefix('ر.س')->disabled(),
                        Forms\Components\TextInput::make('subtotal')->label('المجموع')->prefix('ر.س')->disabled(),
                    ])->columns(4)->deletable(false)->addable(false)->reorderable(false),
            ])->collapsible(),

            Forms\Components\Section::make('الأمن والتدقيق')->schema([
                Forms\Components\TextInput::make('ip_address')->label('عنوان IP')->disabled(),
                Forms\Components\Textarea::make('user_agent')->label('User Agent')->rows(2)->disabled(),
                Forms\Components\Textarea::make('notes')->label('ملاحظات المشتري')->rows(2)->disabled(),
            ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference')->label('المرجع')->fontFamily('mono')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('contact_name')->label('المشتري')->searchable(),
                Tables\Columns\TextColumn::make('contact_email')->label('البريد')->copyable()->color('gray')->size('xs'),
                Tables\Columns\TextColumn::make('items_count')->label('عناصر')->counts('items'),
                Tables\Columns\TextColumn::make('total')->label('الإجمالي')->money('SAR')->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('payment_method')->label('طريقة الدفع')->badge()
                    ->formatStateUsing(fn ($state) => Order::$METHOD_LABELS[$state] ?? $state)
                    ->color('info'),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->color(fn ($state) => match ($state) {
                        Order::STATUS_CART      => 'gray',      // in cart, not paid
                        Order::STATUS_PENDING   => 'warning',   // awaiting payment
                        Order::STATUS_PAID      => 'success',   // done ✓
                        Order::STATUS_FAILED    => 'danger',
                        Order::STATUS_CANCELLED => 'gray',
                        Order::STATUS_REFUNDED  => 'primary',
                        default => 'gray',
                    })
                    ->icon(fn ($state) => match ($state) {
                        Order::STATUS_CART      => 'heroicon-o-shopping-cart',
                        Order::STATUS_PENDING   => 'heroicon-o-clock',
                        Order::STATUS_PAID      => 'heroicon-o-check-badge',
                        Order::STATUS_FAILED    => 'heroicon-o-x-circle',
                        Order::STATUS_CANCELLED => 'heroicon-o-no-symbol',
                        Order::STATUS_REFUNDED  => 'heroicon-o-arrow-uturn-left',
                        default => null,
                    })
                    ->formatStateUsing(fn ($state) => Order::$STATUS_LABELS[$state] ?? $state),
                Tables\Columns\TextColumn::make('paid_at')->label('وقت الدفع')->dateTime('Y-m-d H:i')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('التاريخ')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(Order::$STATUS_LABELS),
                Tables\Filters\SelectFilter::make('payment_method')->options(Order::$METHOD_LABELS),
            ])
            ->actions([
                Tables\Actions\Action::make('markPaid')
                    ->label('تأكيد الدفع يدوياً')->icon('heroicon-o-check-badge')->color('success')
                    ->visible(fn (Order $r) => $r->status === Order::STATUS_PENDING)
                    ->requiresConfirmation()->action(function (Order $r) {
                        $r->markPaid($r->payment_method, 'MANUAL-' . strtoupper(bin2hex(random_bytes(4))));
                        Notification::make()->title('تم تأكيد الدفع')->success()->send();
                    }),
                Tables\Actions\EditAction::make()->iconButton()->tooltip('عرض'),
                Tables\Actions\DeleteAction::make()->iconButton()->tooltip('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ])->label('إجراءات جماعية'),
            ])
            ;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
