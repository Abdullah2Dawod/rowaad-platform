<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // General
            ['key' => 'site.name_ar',      'value' => 'رواد بلا حدود',              'group' => 'general', 'type' => 'string'],
            ['key' => 'site.name_en',      'value' => 'Rowaad Bila Hodoud',         'group' => 'general', 'type' => 'string'],
            ['key' => 'site.tagline_ar',   'value' => 'استشارات اقتصادية بمعايير عالمية', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site.tagline_en',   'value' => 'World-class economic consulting', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site.logo',         'value' => '/images/rowaad-logo-symbol.png', 'group' => 'general', 'type' => 'file'],
            ['key' => 'site.logo_dark',    'value' => '/images/rowaad-logo-symbol-dark.png', 'group' => 'general', 'type' => 'file'],
            ['key' => 'site.favicon',      'value' => '/images/rowaad-logo-symbol.png', 'group' => 'general', 'type' => 'file'],
            ['key' => 'site.contact_email', 'value' => 'info@rowaad.org',            'group' => 'general', 'type' => 'string'],
            ['key' => 'site.contact_phone', 'value' => '+966 55 000 0000',            'group' => 'general', 'type' => 'string'],
            ['key' => 'site.contact_address', 'value' => 'الرياض، المملكة العربية السعودية', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site.menu_order',   'value' => json_encode(['home','services','consultants','feasibility','about','contact']), 'group' => 'general', 'type' => 'json'],

            // Social
            ['key' => 'social.twitter',    'value' => 'https://x.com/rowaad',         'group' => 'social', 'type' => 'string'],
            ['key' => 'social.linkedin',   'value' => 'https://linkedin.com/company/rowaad', 'group' => 'social', 'type' => 'string'],
            ['key' => 'social.instagram',  'value' => 'https://instagram.com/rowaad', 'group' => 'social', 'type' => 'string'],
            ['key' => 'social.facebook',   'value' => '',                             'group' => 'social', 'type' => 'string'],
            ['key' => 'social.youtube',    'value' => '',                             'group' => 'social', 'type' => 'string'],
            ['key' => 'social.tiktok',     'value' => '',                             'group' => 'social', 'type' => 'string'],
            ['key' => 'social.snapchat',   'value' => '',                             'group' => 'social', 'type' => 'string'],
            ['key' => 'social.whatsapp',   'value' => '',                             'group' => 'social', 'type' => 'string'],
            ['key' => 'social.telegram',   'value' => '',                             'group' => 'social', 'type' => 'string'],

            // Marketing
            ['key' => 'marketing.gtm_id',       'value' => '', 'group' => 'marketing', 'type' => 'string'],
            ['key' => 'marketing.ga4_id',       'value' => '', 'group' => 'marketing', 'type' => 'string'],
            ['key' => 'marketing.meta_pixel',   'value' => '', 'group' => 'marketing', 'type' => 'string'],
            ['key' => 'marketing.tiktok_pixel', 'value' => '', 'group' => 'marketing', 'type' => 'string'],
            ['key' => 'marketing.snap_pixel',   'value' => '', 'group' => 'marketing', 'type' => 'string'],
            ['key' => 'marketing.hotjar_id',    'value' => '', 'group' => 'marketing', 'type' => 'string'],
            ['key' => 'marketing.tawk_id',      'value' => '', 'group' => 'marketing', 'type' => 'string'],
            ['key' => 'marketing.seo_title',    'value' => 'رواد بلا حدود — استشارات اقتصادية', 'group' => 'marketing', 'type' => 'string'],
            ['key' => 'marketing.seo_description', 'value' => 'منصة رواد بلا حدود للاستشارات الاقتصادية ودراسات الجدوى والفرص الاستثمارية', 'group' => 'marketing', 'type' => 'string'],
            ['key' => 'marketing.seo_keywords', 'value' => 'استشارات, اقتصاد, دراسات جدوى, استثمار, رواد', 'group' => 'marketing', 'type' => 'string'],
        ];

        foreach ($defaults as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
