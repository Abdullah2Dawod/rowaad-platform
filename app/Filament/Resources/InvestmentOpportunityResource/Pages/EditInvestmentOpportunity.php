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
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
