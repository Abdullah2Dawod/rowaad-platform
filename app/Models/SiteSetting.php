<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings.all'));
        static::deleted(fn () => Cache::forget('site_settings.all'));
    }

    public static function cached(): array
    {
        return Cache::rememberForever('site_settings.all', function () {
            return static::query()->pluck('value', 'key')->all();
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $val = static::cached()[$key] ?? null;
        if ($val === null) return $default;
        $decoded = json_decode((string) $val, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $val;
    }

    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string'): void
    {
        $encoded = is_scalar($value) || $value === null ? $value : json_encode($value, JSON_UNESCAPED_UNICODE);
        static::updateOrCreate(['key' => $key], ['value' => $encoded, 'group' => $group, 'type' => $type]);
    }
}
