<?php

namespace App\Services;

/**
 * Central pricing engine for the platform.
 *
 * Applies the same zakat-on-top rule to every priced surface:
 * bookings, feasibility purchases, service requests, investment applications.
 *
 * Business rule (per platform policy):
 *   • Zakat 15% is charged ON TOP of the base amount.
 *   • The user pays base + zakat.
 *   • Base is split 50/50 between the provider (consultant/platform) and the platform.
 *   • Zakat is held by the platform for remittance to هيئة الزكاة والدخل.
 */
class Pricing
{
    public const ZAKAT_RATE = 0.15;

    /**
     * Compute the full breakdown for a base amount.
     *
     * @param  float  $baseAmount     The pre-tax price shown to the user.
     * @param  bool   $splitWithProvider  When true (bookings), splits base 50/50 between provider and platform.
     *                                    When false (feasibility studies owned by the platform), full base goes to platform.
     * @return array{
     *     baseAmount: float,
     *     zakat: float,
     *     total: float,
     *     consultantShare: float,
     *     platformShare: float
     * }
     */
    public static function compute(float $baseAmount, bool $splitWithProvider = true): array
    {
        $zakat = round($baseAmount * self::ZAKAT_RATE, 2);
        $total = round($baseAmount + $zakat, 2);

        if ($splitWithProvider) {
            $consultantShare = round($baseAmount / 2, 2);
            $platformShare   = round($baseAmount - $consultantShare, 2);
        } else {
            $consultantShare = 0.0;
            $platformShare   = $baseAmount;
        }

        return [
            'baseAmount'      => round($baseAmount, 2),
            'zakat'           => $zakat,
            'total'           => $total,
            'consultantShare' => $consultantShare,
            'platformShare'   => $platformShare,
        ];
    }
}
