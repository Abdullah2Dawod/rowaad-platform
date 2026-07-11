<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     */
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Shared props available on every Inertia page.
     */
    public function share(Request $request): array
    {
        $locale    = App::getLocale();
        $direction = $locale === 'ar' ? 'rtl' : 'ltr';

        return [
            ...parent::share($request),

            // === i18n ===
            'locale'       => $locale,
            'direction'    => $direction,
            'translations' => fn () => $this->loadTranslations($locale),

            // === Auth ===
            'auth' => [
                'user' => $request->user()
                    ? [
                        'id'          => $request->user()->id,
                        'name'        => $request->user()->name,
                        'email'       => $request->user()->email,
                        'role'        => $request->user()->role ?? 'user',
                        'phone'       => $request->user()->phone,
                        'avatar_url'  => $request->user()->avatarUrl(),
                        'is_verified' => $request->user()->isVerified(),
                    ]
                    : null,
            ],

            // === Flash messages ===
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],

            // === Site-wide settings (from admin Settings page) ===
            'site' => fn () => [
                'name_ar'         => SiteSetting::get('site.name_ar', 'رواد بلا حدود'),
                'name_en'         => SiteSetting::get('site.name_en', 'Rowaad'),
                'tagline_ar'      => SiteSetting::get('site.tagline_ar'),
                'tagline_en'      => SiteSetting::get('site.tagline_en'),
                'logo'            => $this->assetUrl(SiteSetting::get('site.logo')),
                'logo_dark'       => $this->assetUrl(SiteSetting::get('site.logo_dark')),
                'favicon'         => $this->assetUrl(SiteSetting::get('site.favicon')),
                'contact_email'   => SiteSetting::get('site.contact_email'),
                'contact_phone'   => SiteSetting::get('site.contact_phone'),
                'contact_address' => SiteSetting::get('site.contact_address'),
                'menu_order'      => SiteSetting::get('site.menu_order', []),
                'social' => [
                    'twitter'   => SiteSetting::get('social.twitter'),
                    'linkedin'  => SiteSetting::get('social.linkedin'),
                    'instagram' => SiteSetting::get('social.instagram'),
                    'facebook'  => SiteSetting::get('social.facebook'),
                    'youtube'   => SiteSetting::get('social.youtube'),
                    'tiktok'    => SiteSetting::get('social.tiktok'),
                    'snapchat'  => SiteSetting::get('social.snapchat'),
                    'whatsapp'  => SiteSetting::get('social.whatsapp'),
                    'telegram'  => SiteSetting::get('social.telegram'),
                ],
                'seo' => [
                    'title'       => SiteSetting::get('marketing.seo_title'),
                    'description' => SiteSetting::get('marketing.seo_description'),
                    'keywords'    => SiteSetting::get('marketing.seo_keywords'),
                ],
            ],
        ];
    }

    /**
     * Load translations from lang/{locale}.json (falls back to empty array).
     */
    private function loadTranslations(string $locale): array
    {
        $path = base_path("lang/{$locale}.json");
        if (! is_file($path)) {
            return [];
        }
        return json_decode(file_get_contents($path), true) ?: [];
    }

    /**
     * Normalize a stored logo/favicon path to a URL the browser can fetch:
     * • absolute URLs pass through
     * • paths starting with "/" pass through (already root-relative)
     * • bare disk paths are prefixed with "/storage/"
     */
    private function assetUrl(?string $path): ?string
    {
        if (empty($path)) return null;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/'])) return $path;
        return '/storage/' . ltrim($path, '/');
    }
}
