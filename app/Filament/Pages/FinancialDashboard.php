<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\Consultant;
use App\Models\WithdrawalRequest;
use App\Models\ZakatRemittance;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class FinancialDashboard extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'اللوحة المالية';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int    $navigationSort  = 15;
    protected static string  $view            = 'filament.pages.financial-dashboard';
    protected static ?string $title           = 'اللوحة المالية الشاملة';

    public string $range = '12m'; // 30d | 90d | 12m | ytd | all

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    protected function getViewData(): array
    {
        $paidStatuses = [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED];
        [$from, $to] = $this->rangeBounds();

        $inRange = Booking::whereIn('status', $paidStatuses)
            ->when($from, fn ($q) => $q->where('paid_at', '>=', $from));

        // Top KPIs
        $totals = (clone $inRange)
            ->selectRaw('COUNT(*) as bookings')
            ->selectRaw('SUM(amount) as gross')
            ->selectRaw('SUM(consultant_share) as consultants')
            ->selectRaw('SUM(platform_share) as platform')
            ->selectRaw('SUM(zakat_amount) as zakat')
            ->first();

        // Monthly series (last 12 months)
        $monthly = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end   = $month->copy()->endOfMonth();
            $row = Booking::whereIn('status', $paidStatuses)
                ->whereBetween('paid_at', [$start, $end])
                ->selectRaw('SUM(amount) as gross, SUM(consultant_share) as c, SUM(platform_share) as p, SUM(zakat_amount) as z')
                ->first();
            $monthly->push([
                'label'    => $month->translatedFormat('M Y'),
                'gross'    => (float) ($row->gross ?? 0),
                'platform' => (float) ($row->p ?? 0),
                'consultants' => (float) ($row->c ?? 0),
                'zakat'    => (float) ($row->z ?? 0),
            ]);
        }
        $maxMonthly = max($monthly->max('gross') ?? 1, 1);

        // Top-earning consultants
        $topConsultants = Booking::whereIn('status', $paidStatuses)
            ->when($from, fn ($q) => $q->where('paid_at', '>=', $from))
            ->selectRaw('consultant_id, SUM(consultant_share) as earnings, COUNT(*) as bookings')
            ->groupBy('consultant_id')
            ->orderByDesc('earnings')
            ->with('consultant.user')
            ->limit(8)
            ->get();

        // Per-service revenue breakdown
        $byService = Booking::whereIn('status', $paidStatuses)
            ->when($from, fn ($q) => $q->where('paid_at', '>=', $from))
            ->whereNotNull('service_title')
            ->selectRaw('service_title, SUM(amount) as revenue, COUNT(*) as bookings')
            ->groupBy('service_title')
            ->orderByDesc('revenue')
            ->limit(6)
            ->get();

        // Withdrawals summary
        $withdrawalsPending = (float) WithdrawalRequest::pending()->sum('amount');
        $withdrawalsPaid    = (float) WithdrawalRequest::paid()->sum('amount');

        // Zakat pool
        $zakatCollected = (float) Booking::whereIn('status', $paidStatuses)->sum('zakat_amount');
        $zakatRemitted  = (float) ZakatRemittance::sum('amount');
        $zakatAvailable = round($zakatCollected - $zakatRemitted, 2);

        return [
            'range'              => $this->range,
            'totals'             => $totals,
            'monthly'            => $monthly,
            'maxMonthly'         => $maxMonthly,
            'topConsultants'     => $topConsultants,
            'byService'          => $byService,
            'withdrawalsPending' => $withdrawalsPending,
            'withdrawalsPaid'    => $withdrawalsPaid,
            'zakatCollected'     => $zakatCollected,
            'zakatRemitted'      => $zakatRemitted,
            'zakatAvailable'     => $zakatAvailable,
            'consultantsCount'   => Consultant::approved()->count(),
        ];
    }

    protected function rangeBounds(): array
    {
        return match ($this->range) {
            '30d'  => [now()->subDays(30), now()],
            '90d'  => [now()->subDays(90), now()],
            'ytd'  => [now()->startOfYear(), now()],
            'all'  => [null, now()],
            default => [now()->subYear(), now()],
        };
    }
}
