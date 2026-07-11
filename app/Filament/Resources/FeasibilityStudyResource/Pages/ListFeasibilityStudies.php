<?php

namespace App\Filament\Resources\FeasibilityStudyResource\Pages;

use App\Filament\Resources\FeasibilityStudyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeasibilityStudies extends ListRecords
{
    protected static string $resource = FeasibilityStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
