<?php

namespace App\Filament\Resources\FeasibilityRequestResource\Pages;

use App\Filament\Resources\FeasibilityRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeasibilityRequest extends EditRecord
{
    protected static string $resource = FeasibilityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
