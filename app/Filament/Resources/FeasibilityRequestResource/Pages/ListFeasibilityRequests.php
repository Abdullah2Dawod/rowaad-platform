<?php

namespace App\Filament\Resources\FeasibilityRequestResource\Pages;

use App\Filament\Resources\FeasibilityRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeasibilityRequests extends ListRecords
{
    protected static string $resource = FeasibilityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
