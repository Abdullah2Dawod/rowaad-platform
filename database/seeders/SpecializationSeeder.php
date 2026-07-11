<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['slug' => 'economic',    'name_ar' => 'الاستشارات الاقتصادية',    'name_en' => 'Economic Consulting',    'icon' => 'graph-up-bold-duotone'],
            ['slug' => 'management',  'name_ar' => 'الاستشارات الإدارية',       'name_en' => 'Management Consulting',  'icon' => 'buildings-2-bold-duotone'],
            ['slug' => 'feasibility', 'name_ar' => 'دراسات الجدوى',            'name_en' => 'Feasibility Studies',    'icon' => 'clipboard-list-bold-duotone'],
            ['slug' => 'marketing',   'name_ar' => 'التسويق',                  'name_en' => 'Marketing',              'icon' => 'megaphone-bold-duotone'],
            ['slug' => 'governance',  'name_ar' => 'الحوكمة والامتثال',        'name_en' => 'Governance & Compliance','icon' => 'scale-bold-duotone'],
            ['slug' => 'finance',     'name_ar' => 'الاستشارات المالية',       'name_en' => 'Financial Consulting',   'icon' => 'wallet-money-bold-duotone'],
            ['slug' => 'strategy',    'name_ar' => 'التخطيط الاستراتيجي',      'name_en' => 'Strategic Planning',     'icon' => 'target-bold-duotone'],
            ['slug' => 'tech',        'name_ar' => 'الاستشارات التقنية',       'name_en' => 'Technology Consulting',  'icon' => 'code-square-bold-duotone'],
            ['slug' => 'hr',          'name_ar' => 'الموارد البشرية',          'name_en' => 'Human Resources',        'icon' => 'users-group-two-rounded-bold-duotone'],
            ['slug' => 'legal',       'name_ar' => 'الاستشارات القانونية',     'name_en' => 'Legal Consulting',       'icon' => 'gavel-bold-duotone'],
        ];
        foreach ($items as $i => $data) {
            Specialization::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['sort_order' => $i + 1, 'is_active' => true])
            );
        }
    }
}
