<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\WithdrawalRequest;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ConsultantWallet extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-wallet';
    protected static ?string $navigationLabel = 'محفظتي';
    protected static ?string $navigationGroup = 'نظرة عامة';
    protected static ?int    $navigationSort  = 5;
    protected static string  $view            = 'filament.pages.consultant-wallet';
    protected static ?string $title           = 'محفظة الاستشارات — الأرباح';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'consultant';
    }

    /** Minimum withdrawal amount in SAR. */
    public const MIN_WITHDRAWAL = 100;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('requestWithdrawal')
                ->label('طلب سحب')
                ->icon('heroicon-o-banknotes')
                ->color('primary')
                ->modalHeading('طلب سحب رصيد')
                ->modalDescription(fn () => 'الحد الأدنى للسحب ' . self::MIN_WITHDRAWAL . ' ر.س. يُعالَج الطلب خلال 3-5 أيام عمل.')
                ->modalSubmitActionLabel('إرسال الطلب')
                ->form([
                    Forms\Components\TextInput::make('amount')
                        ->label('المبلغ المطلوب سحبه (ر.س)')
                        ->numeric()->required()
                        ->minValue(self::MIN_WITHDRAWAL)
                        ->maxValue(fn () => $this->getAvailableBalance())
                        ->helperText(fn () => 'الرصيد المتاح: ' . number_format($this->getAvailableBalance(), 2) . ' ر.س'),
                    Forms\Components\TextInput::make('bank_name')->label('اسم البنك')->required(),
                    Forms\Components\TextInput::make('bank_account_holder')->label('اسم صاحب الحساب')->required(),
                    Forms\Components\TextInput::make('iban')->label('رقم الآيبان (IBAN)')->required()
                        ->placeholder('SA00 0000 0000 0000 0000 0000')->maxLength(34),
                    Forms\Components\TextInput::make('swift_code')->label('SWIFT / BIC (اختياري)')->maxLength(20),
                    Forms\Components\Textarea::make('consultant_notes')->label('ملاحظات')->rows(2),
                ])
                ->visible(fn () => $this->getAvailableBalance() >= self::MIN_WITHDRAWAL)
                ->action(function (array $data) {
                    $consultant = auth()->user()->consultant;
                    if (! $consultant) {
                        Notification::make()->title('لم يُعثر على ملف المستشار')->danger()->send();
                        return;
                    }
                    if ($data['amount'] > $this->getAvailableBalance()) {
                        Notification::make()->title('المبلغ يتجاوز الرصيد المتاح')->danger()->send();
                        return;
                    }
                    WithdrawalRequest::create([
                        'consultant_id'       => $consultant->id,
                        'amount'              => $data['amount'],
                        'bank_name'           => $data['bank_name'],
                        'bank_account_holder' => $data['bank_account_holder'],
                        'iban'                => $data['iban'],
                        'swift_code'          => $data['swift_code'] ?? null,
                        'consultant_notes'    => $data['consultant_notes'] ?? null,
                        'status'              => WithdrawalRequest::STATUS_PENDING,
                    ]);
                    Notification::make()->title('تم إرسال طلب السحب — بانتظار موافقة الإدارة')->success()->send();
                }),
        ];
    }

    public function getAvailableBalance(): float
    {
        $consultant = auth()->user()->consultant;
        if (! $consultant) return 0;

        $completed = (float) Booking::query()
            ->where('consultant_id', $consultant->id)
            ->where('status', Booking::STATUS_COMPLETED)
            ->sum('consultant_share');

        $committed = (float) WithdrawalRequest::query()
            ->where('consultant_id', $consultant->id)
            ->committed()
            ->sum('amount');

        return round($completed - $committed, 2);
    }

    public function getViewData(): array
    {
        $consultant = auth()->user()->consultant;
        if (! $consultant) {
            return [
                'available'=>0,'pending'=>0,'lifetime'=>0,'completed'=>0,
                'thisMonth'=>0,'lastMonth'=>0,'currency'=>'ر.س',
                'transactions'=>collect(),'withdrawals'=>collect(),
                'minWithdrawal'=>self::MIN_WITHDRAWAL,
            ];
        }

        $paidBookings = Booking::query()
            ->where('consultant_id', $consultant->id)
            ->whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->with('user')
            ->latest('paid_at')
            ->get();

        $available = $this->getAvailableBalance();
        $pending   = (float) $paidBookings->whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED])->sum('consultant_share');
        $lifetime  = (float) $paidBookings->sum('consultant_share');

        $thisMonth = (float) $paidBookings->filter(fn ($b) => $b->paid_at?->isCurrentMonth())->sum('consultant_share');
        $lastMonth = (float) $paidBookings->filter(fn ($b) => $b->paid_at?->isLastMonth())->sum('consultant_share');

        $withdrawals = WithdrawalRequest::query()
            ->where('consultant_id', $consultant->id)
            ->latest()
            ->limit(20)
            ->get();

        return [
            'available'     => $available,
            'pending'       => $pending,
            'lifetime'      => $lifetime,
            'completed'     => $paidBookings->where('status', Booking::STATUS_COMPLETED)->count(),
            'thisMonth'     => $thisMonth,
            'lastMonth'     => $lastMonth,
            'currency'      => 'ر.س',
            'transactions'  => $paidBookings->take(30),
            'withdrawals'   => $withdrawals,
            'minWithdrawal' => self::MIN_WITHDRAWAL,
        ];
    }
}
