<?php

namespace App\Filament\Resources\ConsultantResource\Pages;

use App\Filament\Resources\ConsultantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsultant extends EditRecord
{
    protected static string $resource = ConsultantResource::class;

    protected function getHeaderActions(): array
    {
        return [ Actions\DeleteAction::make() ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (is_array($data['rich_content'] ?? null)) {
            foreach (['expertise', 'achievements'] as $k) {
                if (isset($data['rich_content'][$k]) && is_array($data['rich_content'][$k])) {
                    $data['rich_content'][$k] = array_map(
                        fn ($x) => is_array($x) ? $x : ['item' => (string) $x],
                        $data['rich_content'][$k]
                    );
                }
            }
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (is_array($data['rich_content'] ?? null)) {
            foreach (['expertise', 'achievements'] as $k) {
                if (isset($data['rich_content'][$k]) && is_array($data['rich_content'][$k])) {
                    $data['rich_content'][$k] = array_values(array_filter(array_map(
                        fn ($x) => is_array($x) ? ($x['item'] ?? null) : $x,
                        $data['rich_content'][$k]
                    )));
                }
            }
        }
        return $data;
    }
}
