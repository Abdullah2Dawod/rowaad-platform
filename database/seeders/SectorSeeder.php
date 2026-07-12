<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            [
                'slug' => 'real-estate', 'name_ar' => 'العقارات والتطوير', 'name_en' => 'Real Estate',
                'description_ar' => 'تطوير المشاريع العقارية السكنية والتجارية بمعايير رؤية 2030.',
                'icon' => 'buildings-3-bold-duotone', 'color' => '#3DAFB9',
                'hero_image' => 'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?w=1400&auto=format&fit=crop&q=70',
                'highlights' => [
                    ['label' => 'حجم السوق', 'value' => '1.3 ترليون'],
                    ['label' => 'النمو السنوي', 'value' => '+9%'],
                    ['label' => 'مشاريع نشطة', 'value' => '+250'],
                ],
                'opportunities' => ['مشاريع NEOM', 'إسكان الرياض', 'الوجهات السياحية'],
                'featured' => true, 'sort_order' => 10,
            ],
            [
                'slug' => 'energy', 'name_ar' => 'الطاقة والاستدامة', 'name_en' => 'Energy & Sustainability',
                'description_ar' => 'الطاقة المتجددة والهيدروجين الأخضر ومشاريع الاستدامة السعودية.',
                'icon' => 'sun-2-bold-duotone', 'color' => '#F59E0B',
                'hero_image' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=1400&auto=format&fit=crop&q=70',
                'highlights' => [
                    ['label' => 'هدف 2030', 'value' => '50% متجددة'],
                    ['label' => 'استثمارات', 'value' => '+380 مليار'],
                    ['label' => 'مشاريع', 'value' => '+45'],
                ],
                'opportunities' => ['الطاقة الشمسية', 'الهيدروجين الأخضر', 'كفاءة الطاقة'],
                'featured' => true, 'sort_order' => 20,
            ],
            [
                'slug' => 'tourism', 'name_ar' => 'السياحة والترفيه', 'name_en' => 'Tourism & Entertainment',
                'description_ar' => 'قطاع السياحة السعودي في نموّ استثنائي ضمن رؤية المملكة 2030.',
                'icon' => 'plane-bold-duotone', 'color' => '#059669',
                'hero_image' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?w=1400&auto=format&fit=crop&q=70',
                'highlights' => [
                    ['label' => 'زوار متوقعون', 'value' => '150 مليون'],
                    ['label' => 'وظائف جديدة', 'value' => '+1 مليون'],
                    ['label' => 'مشاريع كبرى', 'value' => '15+'],
                ],
                'opportunities' => ['البحر الأحمر', 'العلا', 'القدية'],
                'featured' => true, 'sort_order' => 30,
            ],
            [
                'slug' => 'technology', 'name_ar' => 'التقنية والذكاء الاصطناعي', 'name_en' => 'Technology & AI',
                'description_ar' => 'الاقتصاد الرقمي والذكاء الاصطناعي كركيزة أساسية للنمو.',
                'icon' => 'cpu-bold-duotone', 'color' => '#3B82F6',
                'hero_image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1400&auto=format&fit=crop&q=70',
                'highlights' => [
                    ['label' => 'اقتصاد رقمي', 'value' => '+50 مليار'],
                    ['label' => 'شركات ناشئة', 'value' => '+2500'],
                    ['label' => 'مسرّعات', 'value' => '35+'],
                ],
                'opportunities' => ['الذكاء الاصطناعي', 'Fintech', 'مدن ذكية'],
                'featured' => true, 'sort_order' => 40,
            ],
            [
                'slug' => 'healthcare', 'name_ar' => 'الرعاية الصحية', 'name_en' => 'Healthcare',
                'description_ar' => 'قطاع الصحة السعودي بفرص استثمارية ضخمة ومشاريع نوعية.',
                'icon' => 'health-bold-duotone', 'color' => '#EF4444',
                'hero_image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1400&auto=format&fit=crop&q=70',
                'highlights' => [
                    ['label' => 'الإنفاق الصحي', 'value' => '160 مليار'],
                    ['label' => 'مستشفيات جديدة', 'value' => '20+'],
                    ['label' => 'خصخصة', 'value' => 'مستمرة'],
                ],
                'opportunities' => ['السياحة العلاجية', 'التطبيب عن بُعد', 'الأدوية'],
                'featured' => false, 'sort_order' => 50,
            ],
            [
                'slug' => 'logistics', 'name_ar' => 'اللوجستيات والنقل', 'name_en' => 'Logistics & Transport',
                'description_ar' => 'مركز لوجستي عالمي يربط ثلاث قارات ضمن مبادرات النقل الحديث.',
                'icon' => 'delivery-bold-duotone', 'color' => '#8B5CF6',
                'hero_image' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=1400&auto=format&fit=crop&q=70',
                'highlights' => [
                    ['label' => 'موقع استراتيجي', 'value' => '3 قارات'],
                    ['label' => 'استثمارات', 'value' => '+45 مليار'],
                    ['label' => 'وظائف', 'value' => '+380 ألف'],
                ],
                'opportunities' => ['الشحن البحري', 'المطارات', 'سلاسل الإمداد'],
                'featured' => false, 'sort_order' => 60,
            ],
            [
                'slug' => 'manufacturing', 'name_ar' => 'الصناعة والتصنيع', 'name_en' => 'Manufacturing',
                'description_ar' => 'التصنيع المتقدّم والصناعات النوعية ضمن مبادرات "صنع في السعودية".',
                'icon' => 'settings-bold-duotone', 'color' => '#64748B',
                'hero_image' => 'https://images.unsplash.com/photo-1565043666747-69f6646db940?w=1400&auto=format&fit=crop&q=70',
                'highlights' => [
                    ['label' => 'حصة الناتج', 'value' => '24%'],
                    ['label' => 'استثمارات', 'value' => '+320 مليار'],
                    ['label' => 'مصانع', 'value' => '+11000'],
                ],
                'opportunities' => ['السيارات', 'الأدوية', 'البتروكيماويات'],
                'featured' => false, 'sort_order' => 70,
            ],
            [
                'slug' => 'agriculture', 'name_ar' => 'الأمن الغذائي والزراعة', 'name_en' => 'Agriculture',
                'description_ar' => 'الأمن الغذائي والزراعة المستدامة والتقنية الزراعية الحديثة.',
                'icon' => 'leaf-bold-duotone', 'color' => '#10B981',
                'hero_image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1400&auto=format&fit=crop&q=70',
                'highlights' => [
                    ['label' => 'استثمارات', 'value' => '+40 مليار'],
                    ['label' => 'اكتفاء ذاتي', 'value' => 'أهداف عالية'],
                    ['label' => 'مشاريع', 'value' => '+180'],
                ],
                'opportunities' => ['الزراعة المائية', 'الثروة السمكية', 'التقنية الزراعية'],
                'featured' => false, 'sort_order' => 80,
            ],
        ];

        foreach ($sectors as $s) {
            Sector::updateOrCreate(['slug' => $s['slug']], $s);
        }
    }
}
