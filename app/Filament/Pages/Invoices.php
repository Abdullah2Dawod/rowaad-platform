<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use Filament\Pages\Page;

class Invoices extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'الفواتير';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int    $navigationSort  = 20;
    protected static string  $view            = 'filament.pages.invoices';
    protected static ?string $title           = 'فواتير المنصة';

    public ?string $filter = 'all'; // all|paid|confirmed|completed

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    protected function getViewData(): array
    {
        $q = Booking::query()
            ->whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->with(['user', 'consultant.user'])
            ->latest('paid_at');

        if ($this->filter !== 'all') {
            $q->where('status', $this->filter);
        }

        $invoices = $q->paginate(20);

        $totals = Booking::query()
            ->whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(amount) as gross')
            ->selectRaw('SUM(consultant_share) as consultants')
            ->selectRaw('SUM(platform_share) as platform')
            ->selectRaw('SUM(zakat_amount) as zakat')
            ->first();

        return [
            'invoices' => $invoices,
            'totals'   => $totals,
        ];
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }
}
