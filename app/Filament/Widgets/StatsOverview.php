<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Consultant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Auto-refresh every 10 seconds via Livewire polling
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $user = auth()->user();

        // Consultants see only their own scoped stats
        if ($user?->role === 'consultant' && $user->consultant) {
            $consultantId = $user->consultant->id;
            $bookings = Booking::where('consultant_id', $consultantId);

            return [
                Stat::make('حجوزات بانتظار التأكيد', (clone $bookings)->where('status', Booking::STATUS_PAID)->count())
                    ->description('حجوزات مدفوعة تحتاج تأكيدك')
                    ->descriptionIcon('heroicon-o-clock')
                    ->color('warning'),

                Stat::make('حجوزات مؤكّدة', (clone $bookings)->where('status', Booking::STATUS_CONFIRMED)->count())
                    ->description('جلسات قادمة')
                    ->descriptionIcon('heroicon-o-calendar-days')
                    ->color('success'),

                Stat::make('إجمالي الحجوزات', (clone $bookings)->count())
                    ->description('كل الحجوزات على حسابك')
                    ->descriptionIcon('heroicon-o-inbox-stack')
                    ->color('info'),

                Stat::make('حجوزات هذا الشهر', (clone $bookings)->where('created_at', '>=', now()->startOfMonth())->count())
                    ->description('منذ ' . now()->startOfMonth()->format('Y-m-d'))
                    ->descriptionIcon('heroicon-o-chart-bar')
                    ->color('primary'),
            ];
        }

        // Admin sees platform-wide stats — with 7-day sparklines for context
        $bookingsPerDay = collect(range(6, 0))->map(function ($d) {
            $day = now()->subDays($d);
            return Booking::whereBetween('created_at', [
                $day->copy()->startOfDay(), $day->copy()->endOfDay(),
            ])->count();
        })->all();

        $usersPerDay = collect(range(6, 0))->map(function ($d) {
            $day = now()->subDays($d);
            return User::whereBetween('created_at', [
                $day->copy()->startOfDay(), $day->copy()->endOfDay(),
            ])->count();
        })->all();

        $todayBookings = Booking::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count();
        $yesterdayBookings = Booking::whereBetween('created_at', [
            now()->subDay()->startOfDay(), now()->subDay()->endOfDay(),
        ])->count();
        $bookingsTrend = $yesterdayBookings > 0
            ? round((($todayBookings - $yesterdayBookings) / $yesterdayBookings) * 100)
            : 0;

        return [
            Stat::make('طلبات مستشارين قيد المراجعة', Consultant::pending()->count())
                ->description('بانتظار اعتمادك')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('warning'),

            Stat::make('حجوزات مدفوعة جديدة', Booking::where('status', Booking::STATUS_PAID)->count())
                ->description('بانتظار تأكيد المستشار')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('info')
                ->chart($bookingsPerDay),

            Stat::make('مستشارون معتمدون', Consultant::approved()->count())
                ->description('يظهرون للعملاء')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),

            Stat::make('إجمالي المستخدمين', User::count())
                ->description($bookingsTrend >= 0
                    ? "▲ اليوم +{$todayBookings} حجز"
                    : "▼ اليوم {$todayBookings} حجز")
                ->descriptionIcon($bookingsTrend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color('primary')
                ->chart($usersPerDay),
        ];
    }
}
