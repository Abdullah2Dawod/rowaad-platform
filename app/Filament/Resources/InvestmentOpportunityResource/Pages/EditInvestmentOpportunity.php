<?php

namespace App\Filament\Resources\InvestmentOpportunityResource\Pages;

use App\Filament\Resources\InvestmentOpportunityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestmentOpportunity extends EditRecord
{
    protected static string $resource = InvestmentOpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [ Actions\DeleteAction::make() ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (is_array($data['rich_content'] ?? null)
            && isset($data['rich_content']['investor_perks'])
            && is_array($data['rich_content']['investor_perks'])) {
            $data['rich_content']['investor_perks'] = array_map(
                fn ($x) => is_array($x) ? $x : ['item' => (string) $x],
                $data['rich_content']['investor_perks']
            );
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
