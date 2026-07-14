<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [ Actions\DeleteAction::make() ];
    }

    // Wrap plain-string arrays into {item: string} for the Repeater form
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['includes']     = $this->wrap($data['includes']     ?? []);
        $data['deliverables'] = $this->wrap($data['deliverables'] ?? []);
        if (is_array($data['rich_content'] ?? null)) {
            foreach (['target_audience', 'outcomes'] as $k) {
                $data['rich_content'][$k] = $this->wrap($data['rich_content'][$k] ?? []);
            }
        }
        return $data;
    }

    // Unwrap {item: string} back to plain strings before persisting
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['includes']     = $this->unwrap($data['includes']     ?? []);
        $data['deliverables'] = $this->unwrap($data['deliverables'] ?? []);
        if (is_array($data['rich_content'] ?? null)) {
            foreach (['target_audience', 'outcomes'] as $k) {
                $data['rich_content'][$k] = $this->unwrap($data['rich_content'][$k] ?? []);
            }
        }
        return $data;
    }

    private function wrap(mixed $arr): array
    {
        if (! is_array($arr)) return [];
        return array_map(fn ($x) => is_array($x) ? $x : ['item' => (string) $x], $arr);
    }

    private function unwrap(mixed $arr): array
    {
        if (! is_array($arr)) return [];
        return array_values(array_filter(array_map(
            fn ($x) => is_array($x) ? ($x['item'] ?? null) : $x, $arr
        )));
    }
}
