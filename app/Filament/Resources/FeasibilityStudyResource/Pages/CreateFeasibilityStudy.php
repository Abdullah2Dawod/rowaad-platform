<?php

namespace App\Filament\Resources\FeasibilityStudyResource\Pages;

use App\Filament\Resources\FeasibilityStudyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeasibilityStudy extends CreateRecord
{
    protected static string $resource = FeasibilityStudyResource::class;

    /** Unwrap {item: string} back to plain strings for target_market / includes. */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (is_array($data['rich_content'] ?? null)) {
            foreach (['target_market', 'includes'] as $key) {
                if (isset($data['rich_content'][$key]) && is_array($data['rich_content'][$key])) {
                    $data['rich_content'][$key] = array_values(array_filter(array_map(
                        fn ($x) => is_array($x) ? ($x['item'] ?? null) : $x,
                        $data['rich_content'][$key]
                    )));
                }
            }
        }
        return $data;
    }
}
