<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookings extends BaseWidget
{
    protected static ?string $heading = 'أحدث الحجوزات';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 3;

    // Poll every 8 seconds — no page reload needed
    protected static ?string $pollingInterval = '8s';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->getScopedQuery())
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->label('المرجع')->fontFamily('mono')->weight('bold')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->description(fn ($record) => $record->user?->email),
                Tables\Columns\TextColumn::make('consultant.full_name_ar')
                    ->label('المستشار')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('التاريخ')->date('Y-m-d'),
                Tables\Columns\TextColumn::make('amount')->label('المبلغ')->money('SAR'),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->formatStateUsing(fn (string $state): string => [
                        'pending_payment' => 'بانتظار الدفع',
                        'paid'            => 'مدفوع · بانتظار التأكيد',
                        'confirmed'       => 'مؤكّد',
                        'cancelled'       => 'ملغى',
                        'completed'       => 'مكتمل',
                    ][$state] ?? $state)
                    ->color(fn (string $state): string => [
                        'pending_payment' => 'warning',
                        'paid'            => 'info',
                        'confirmed'       => 'success',
                        'cancelled'       => 'danger',
                        'completed'       => 'gray',
                    ][$state] ?? 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('منذ')->since()->toggleable(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('فتح')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => "/admin/bookings/{$record->id}/edit"),
            ]);
    }

    private function getScopedQuery()
    {
        $q = Booking::query()->with(['user', 'consultant'])->limit(6);
        $user = auth()->user();
        if ($user?->role === 'consultant' && $user->consultant) {
            $q->where('consultant_id', $user->consultant->id);
        }
        return $q;
    }
}
