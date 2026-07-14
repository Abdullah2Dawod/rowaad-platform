<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $unwrap = fn ($arr) => is_array($arr)
            ? array_values(array_filter(array_map(
                fn ($x) => is_array($x) ? ($x['item'] ?? null) : $x, $arr
            )))
            : [];

        $data['includes']     = $unwrap($data['includes']     ?? []);
        $data['deliverables'] = $unwrap($data['deliverables'] ?? []);
        if (is_array($data['rich_content'] ?? null)) {
            foreach (['target_audience', 'outcomes'] as $k) {
                $data['rich_content'][$k] = $unwrap($data['rich_content'][$k] ?? []);
            }
        }
        return $data;
    }
}
