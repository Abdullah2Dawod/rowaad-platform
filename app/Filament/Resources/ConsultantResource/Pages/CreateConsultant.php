<?php

namespace App\Filament\Resources\ConsultantResource\Pages;

use App\Filament\Resources\ConsultantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateConsultant extends CreateRecord
{
    protected static string $resource = ConsultantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
