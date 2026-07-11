<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Role-aware financial overview:
 *  • Consultant sees their own earnings (gross + net after 15% zakat).
 *  • Admin sees platform revenue and total zakat obligation.
 */
class FinancialsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'consultant']);
    }

    protected function getStats(): array
    {
        $user = auth()->user();

        // ─────── Consultant view ───────
        // Per business rule: zakat is charged ON TOP of the base amount (paid by the client).
        // The consultant receives their FULL share — no zakat deduction from their earnings.
        if ($user?->role === 'consultant' && $user->consultant) {
            $bookings = Booking::where('consultant_id', $user->consultant->id)->revenue();

            $earned = (float) (clone $bookings)->sum('consultant_share');
            $pending = (float) Booking::where('consultant_id', $user->consultant->id)
                ->whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED])
                ->sum('consultant_share');
            $thisMonth = (float) (clone $bookings)
                ->where('paid_at', '>=', now()->startOfMonth())
                ->sum('consultant_share');
            $sessions = (clone $bookings)->count();

            return [
                Stat::make('إجمالي أرباحك', number_format($earned, 0) . ' ر.س')
                    ->description('كامل حصتك — الزكاة على العميل، لا تُخصم منك')
                    ->descriptionIcon('heroicon-o-banknotes')
                    ->color('success')
                    ->chart([2, 4, 3, 6, 8, 7, 10]),

                Stat::make('أرباح هذا الشهر', number_format($thisMonth, 0) . ' ر.س')
                    ->description('منذ ' . now()->translatedFormat('j F'))
                    ->descriptionIcon('heroicon-o-calendar-days')
                    ->color('info'),

                Stat::make('قيد التنفيذ', number_format($pending, 0) . ' ر.س')
                    ->description('حجوزات مؤكّدة/مدفوعة لم تكتمل')
                    ->descriptionIcon('heroicon-o-clock')
                    ->color('warning'),

                Stat::make('جلساتك المُربحة', number_format($sessions))
                    ->description('كل الجلسات (مدفوعة → مكتملة)')
                    ->descriptionIcon('heroicon-o-users')
                    ->color('primary'),
            ];
        }

        // ─────── Admin view ───────
        $bookings = Booking::query()->revenue();

        $revenue  = (float) (clone $bookings)->sum('platform_share');
        $zakatDue = (float) (clone $bookings)->sum('zakat_amount');
        $paidToConsultants = (float) (clone $bookings)->sum('consultant_share');
        $totalGross = (float) (clone $bookings)->sum('amount');

        // Last 7-day sparkline
        $spark = collect(range(6, 0))->map(function ($daysAgo) {
            $day = now()->subDays($daysAgo);
            return (float) Booking::query()->revenue()
                ->whereBetween('paid_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
                ->sum('platform_share');
        })->all();

        return [
            Stat::make('إيراد المنصة', number_format($revenue, 0) . ' ر.س')
                ->description('حصة المنصة من جميع الحجوزات')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('success')
                ->chart($spark),

            Stat::make('وعاء الزكاة', number_format($zakatDue, 0) . ' ر.س')
                ->description('15% مدفوعة من العميل — للتحويل للهيئة')
                ->descriptionIcon('heroicon-o-heart')
                ->color('warning'),

            Stat::make('حصص المستشارين', number_format($paidToConsultants, 0) . ' ر.س')
                ->description('إجمالي أرباح المستشارين (بلا خصم)')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('إجمالي التدفّق النقدي', number_format($totalGross, 0) . ' ر.س')
                ->description('كل ما مرّ عبر المنصة')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('primary'),
        ];
    }
}
