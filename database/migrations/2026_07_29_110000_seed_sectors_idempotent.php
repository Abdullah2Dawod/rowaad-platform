<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $sectors = [
            ['slug' => 'real-estate', 'name_ar' => 'العقارات', 'name_en' => 'Real Estate',    'icon' => 'buildings-2-bold-duotone',      'description_ar' => 'التطوير العقاري والاستثمار طويل الأمد وفق رؤية 2030.',   'color' => '#3DAFB9', 'sort_order' => 1],
            ['slug' => 'industry',    'name_ar' => 'الصناعة', 'name_en' => 'Industry',       'icon' => 'city-bold-duotone',              'description_ar' => 'الصناعات التحويلية والصناعات المتقدمة والتصنيع الذكي.',  'color' => '#2D4B7E', 'sort_order' => 2],
            ['slug' => 'retail',      'name_ar' => 'التجزئة', 'name_en' => 'Retail',         'icon' => 'cart-large-2-bold-duotone',      'description_ar' => 'التجزئة الحديثة والتجارة الإلكترونية والعلامات المحلية.', 'color' => '#3DAFB9', 'sort_order' => 3],
            ['slug' => 'healthcare',  'name_ar' => 'الصحة',   'name_en' => 'Healthcare',     'icon' => 'health-bold-duotone',            'description_ar' => 'الخدمات الصحية والتقنيات الطبية والعناية المتخصصة.',    'color' => '#2D4B7E', 'sort_order' => 4],
            ['slug' => 'education',   'name_ar' => 'التعليم', 'name_en' => 'Education',      'icon' => 'square-academic-cap-bold-duotone','description_ar' => 'التعليم النوعي والتدريب المهني والمهارات الرقمية.',    'color' => '#3DAFB9', 'sort_order' => 5],
            ['slug' => 'technology',  'name_ar' => 'التقنية', 'name_en' => 'Technology',     'icon' => 'server-square-cloud-bold-duotone','description_ar' => 'الذكاء الاصطناعي والحوسبة السحابية والتحوّل الرقمي.',   'color' => '#2D4B7E', 'sort_order' => 6],
            ['slug' => 'agriculture', 'name_ar' => 'الزراعة', 'name_en' => 'Agriculture',    'icon' => 'leaf-bold-duotone',              'description_ar' => 'الأمن الغذائي والزراعة العضوية والاستدامة.',           'color' => '#3DAFB9', 'sort_order' => 7],
            ['slug' => 'tourism',     'name_ar' => 'السياحة', 'name_en' => 'Tourism',        'icon' => 'suitcase-tag-bold-duotone',      'description_ar' => 'السياحة والضيافة والفعاليات الترفيهية والثقافية.',      'color' => '#2D4B7E', 'sort_order' => 8],
        ];

        foreach ($sectors as $s) {
            DB::table('sectors')->updateOrInsert(
                ['slug' => $s['slug']],
                array_merge($s, [
                    'is_active'  => true,
                    'featured'   => false,
                    'updated_at' => now(),
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ])
            );
        }
    }

    public function down(): void
    {
        // Do not delete — keeps operator-added sectors safe.
    }
};
