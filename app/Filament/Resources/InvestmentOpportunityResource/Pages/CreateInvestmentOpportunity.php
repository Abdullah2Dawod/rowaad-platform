<?php

namespace App\Filament\Resources\InvestmentOpportunityResource\Pages;

use App\Filament\Resources\InvestmentOpportunityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvestmentOpportunity extends CreateRecord
{
    protected static string $resource = InvestmentOpportunityResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (is_array($data['rich_content'] ?? null)
            && isset($data['rich_content']['investor_perks'])
            && is_array($data['rich_content']['investor_perks'])) {
            $data['rich_content']['investor_perks'] = array_values(array_filter(array_map(
                fn ($x) => is_array($x) ? ($x['item'] ?? null) : $x,
                $data['rich_content']['investor_perks']
            )));
        }
        return $data;
    }
}
