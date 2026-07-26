<?php

namespace App\Support;

/**
 * Media path helpers — resolves any stored image path to a browser-loadable URL.
 *
 * Handles three cases:
 *   1. Full URL (http/https/data:)  → returned as-is (legacy hardcoded Unsplash + user avatars).
 *   2. Relative storage path        → prefixed with "/storage/" (Filament FileUpload disk=public).
 *   3. Empty / null                 → returns the provided fallback (or null).
 */
class Media
{
    public static function url(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) return $fallback;
        if (preg_match('#^(https?:|data:|/)#i', $path)) return $path;
        return '/storage/' . ltrim($path, '/');
    }

    /** Default placeholders keyed by domain — used when a field is empty. */
    public static function servicePlaceholder(): string
    {
        return '/images/why-us/data-analysis.svg';
    }

    public static function investmentPlaceholder(): string
    {
        return '/images/why-us/precision-target.svg';
    }

    public static function feasibilityPlaceholder(): string
    {
        return '/images/why-us/trust-shield.svg';
    }

    public static function consultantPlaceholder(): string
    {
        return '/images/rowaad-logo-symbol.png';
    }
}
