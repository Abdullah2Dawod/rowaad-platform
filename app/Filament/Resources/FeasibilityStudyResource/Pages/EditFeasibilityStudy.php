<?php

namespace App\Filament\Resources\FeasibilityStudyResource\Pages;

use App\Filament\Resources\FeasibilityStudyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeasibilityStudy extends EditRecord
{
    protected static string $resource = FeasibilityStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [ Actions\DeleteAction::make() ];
    }

    /**
     * Normalize rich_content BEFORE the form fills, so simple-list fields
     * (target_market, includes) that live in DB as plain string arrays
     * are wrapped as [{item: string}, …] which the Repeater expects.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['rich_content'] = $this->normalizeInbound($data['rich_content'] ?? null);
        return $data;
    }

    /**
     * Reverse the transformation before writing back so the DB stays clean
     * (plain string arrays for target_market / includes).
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['rich_content'] = $this->normalizeOutbound($data['rich_content'] ?? null);
        return $data;
    }

    private function normalizeInbound(mixed $rich): array
    {
        if (! is_array($rich)) return [];
        foreach (['target_market', 'includes'] as $key) {
            if (isset($rich[$key]) && is_array($rich[$key])) {
                $rich[$key] = array_map(
                    fn ($x) => is_array($x) ? $x : ['item' => (string) $x],
                    $rich[$key]
                );
            }
        }
        return $rich;
    }

    private function normalizeOutbound(mixed $rich): ?array
    {
        if (! is_array($rich)) return null;
        foreach (['target_market', 'includes'] as $key) {
            if (isset($rich[$key]) && is_array($rich[$key])) {
                $rich[$key] = array_values(array_filter(array_map(
                    fn ($x) => is_array($x) ? ($x['item'] ?? null) : $x,
                    $rich[$key]
                )));
            }
        }
        return $rich;
    }
}
