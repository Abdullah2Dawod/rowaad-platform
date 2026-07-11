<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\ZakatRemittance;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ZakatPool extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'وعاء الزكاة';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int    $navigationSort  = 25;
    protected static string  $view            = 'filament.pages.zakat-pool';
    protected static ?string $title           = 'وعاء الزكاة والدخل';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public function mount(): void
    {
        $this->form->fill([
            'amount'      => $this->getAvailablePool(),
            'period_from' => now()->startOfMonth()->toDateString(),
            'period_to'   => now()->endOfMonth()->toDateString(),
            'remitted_at' => now()->toDateString(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('تسجيل تحويل للهيئة العامة للزكاة والدخل')
                ->description('سجّل هنا كل عملية تحويل للزكاة المتراكمة في وعاء المنصة.')
                ->schema([
                    Forms\Components\TextInput::make('amount')->label('المبلغ (ر.س)')->numeric()->required(),
                    Forms\Components\DatePicker::make('remitted_at')->label('تاريخ التحويل')->required(),
                    Forms\Components\DatePicker::make('period_from')->label('من تاريخ')->required(),
                    Forms\Components\DatePicker::make('period_to')->label('إلى تاريخ')->required(),
                    Forms\Components\TextInput::make('reference')->label('رقم مرجع التحويل / GAZT'),
                    Forms\Components\Textarea::make('notes')->label('ملاحظات')->rows(2)->columnSpanFull(),
                ])->columns(2)
                ->collapsed(),
        ])->statePath('data');
    }

    public function record(): void
    {
        $data = $this->form->getState();
        ZakatRemittance::create([...$data, 'remitted_by' => auth()->id()]);
        Notification::make()->title('تم تسجيل التحويل بنجاح')->success()->send();
        $this->mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('record')->label('تسجيل تحويل')->icon('heroicon-o-plus')->action('record'),
        ];
    }

    public function getAvailablePool(): float
    {
        $collected = (float) Booking::query()
            ->whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->sum('zakat_amount');
        $remitted  = (float) ZakatRemittance::sum('amount');
        return round($collected - $remitted, 2);
    }

    protected function getViewData(): array
    {
        $collected = (float) Booking::query()
            ->whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->sum('zakat_amount');
        $remitted  = (float) ZakatRemittance::sum('amount');
        return [
            'collected'   => $collected,
            'remitted'    => $remitted,
            'available'   => round($collected - $remitted, 2),
            'remittances' => ZakatRemittance::with('remittedBy')->latest('remitted_at')->paginate(15),
        ];
    }
}
