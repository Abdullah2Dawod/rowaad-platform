<?php

namespace Database\Seeders;

use App\Models\InvestmentOpportunity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InvestmentOpportunitySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title'    => 'مصنع تجميع سيارات كهربائية · الرياض',
                'subtitle' => 'أول خط تجميع سعودي مخصّص للسيارات الكهربائية بطاقة 12,000 سيارة/سنة',
                'summary'  => 'فرصة B2B لتأسيس مصنع تجميع مركبات كهربائية بشراكة استراتيجية مع مصنّعين آسيويين، ضمن مبادرات صندوق الاستثمارات العامة نحو التنقّل الكهربائي.',
                'sector'   => 'صناعة',
                'city'     => 'الرياض',
                'region'   => 'الوسطى',
                'investment_min' => 250_000_000, 'investment_max' => 400_000_000,
                'expected_roi'   => 22.5, 'payback_months' => 42, 'duration_years' => 10,
                'risk_level'     => 'medium', 'is_featured' => true,
                'cover_image'    => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=1200&auto=format&fit=crop&q=70',
                'source'         => 'gov_api', 'source_name' => 'المركز الوطني للاستثمار',
                'highlights'     => [
                    ['title' => 'الطاقة الإنتاجية', 'value' => '12,000 سيارة/سنة', 'icon' => 'chart-square-bold-duotone'],
                    ['title' => 'الوظائف المتوقّعة', 'value' => '+850 وظيفة',        'icon' => 'users-group-two-rounded-bold-duotone'],
                    ['title' => 'حجم السوق',        'value' => '5.8 مليار ر.س',    'icon' => 'wallet-money-bold-duotone'],
                    ['title' => 'دعم حكومي',        'value' => 'تحفيزات ضريبية',   'icon' => 'medal-star-bold-duotone'],
                ],
            ],
            [
                'title'    => 'مصنع تعبئة مياه معدنية · الشرقية',
                'subtitle' => 'خط إنتاج مياه معبأة بطاقة 20,000 عبوة يومياً',
                'summary'  => 'استثمار في قطاع نمو مستمر — الطلب على المياه المعبأة في السعودية يزيد 8% سنوياً. المشروع يخدم قطاع HORECA وعملاء التجزئة.',
                'sector'   => 'صناعة',
                'city'     => 'الدمام',
                'region'   => 'الشرقية',
                'investment_min' => 45_000_000, 'investment_max' => 75_000_000,
                'expected_roi'   => 18.2, 'payback_months' => 36, 'duration_years' => 8,
                'risk_level'     => 'low', 'is_featured' => true,
                'cover_image'    => 'https://images.unsplash.com/photo-1581873372796-635b67ca2008?w=1200&auto=format&fit=crop&q=70',
                'source'         => 'internal',
                'highlights'     => [
                    ['title' => 'الطاقة اليومية', 'value' => '20,000 عبوة',   'icon' => 'chart-square-bold-duotone'],
                    ['title' => 'هامش الربح',    'value' => '32%',             'icon' => 'graph-up-bold-duotone'],
                    ['title' => 'العائد المتوقع', 'value' => '18.2% سنوياً',    'icon' => 'medal-star-bold-duotone'],
                    ['title' => 'قنوات التوزيع', 'value' => 'B2B + التجزئة',   'icon' => 'delivery-bold-duotone'],
                ],
            ],
            [
                'title'    => 'منتجع سياحي فاخر · العُلا',
                'subtitle' => 'منتجع بيئي بطاقة 120 غرفة مطلّ على وادي الحمرا',
                'summary'  => 'العُلا واحدة من أسرع الوجهات السياحية نمواً في العالم. الفرصة مدعومة من الهيئة الملكية للعُلا ضمن رؤية 2030 السياحية.',
                'sector'   => 'سياحة',
                'city'     => 'العُلا',
                'region'   => 'المدينة المنوّرة',
                'investment_min' => 380_000_000, 'investment_max' => 550_000_000,
                'expected_roi'   => 15.8, 'payback_months' => 60, 'duration_years' => 20,
                'risk_level'     => 'medium', 'is_featured' => true,
                'cover_image'    => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&auto=format&fit=crop&q=70',
                'source'         => 'gov_api', 'source_name' => 'الهيئة الملكية للعُلا',
                'highlights'     => [
                    ['title' => 'عدد الغرف',      'value' => '120 غرفة',       'icon' => 'buildings-2-bold-duotone'],
                    ['title' => 'الإشغال المتوقع', 'value' => '72% سنوياً',      'icon' => 'chart-square-bold-duotone'],
                    ['title' => 'تحفيزات مالية',  'value' => 'إعفاء 5 سنوات',   'icon' => 'medal-star-bold-duotone'],
                    ['title' => 'الوظائف',        'value' => '+320 وظيفة',      'icon' => 'users-group-two-rounded-bold-duotone'],
                ],
            ],
            [
                'title'    => 'منصة لوجستية ذكية للتجارة الإلكترونية',
                'subtitle' => 'مركز فرز وتوزيع بتقنية الذكاء الاصطناعي',
                'summary'  => 'مشروع تقني — لوجستي يخدم قطاع التجارة الإلكترونية المتنامي بمعدّل 32% سنوياً. يشمل حلول last-mile ذكية.',
                'sector'   => 'تقنية',
                'city'     => 'جدة',
                'region'   => 'مكة المكرمة',
                'investment_min' => 60_000_000, 'investment_max' => 100_000_000,
                'expected_roi'   => 28.5, 'payback_months' => 30, 'duration_years' => 7,
                'risk_level'     => 'medium', 'is_featured' => false,
                'cover_image'    => 'https://images.unsplash.com/photo-1553413077-190dd305871c?w=1200&auto=format&fit=crop&q=70',
                'source'         => 'internal',
                'highlights'     => [
                    ['title' => 'العائد المتوقّع', 'value' => '28.5% سنوياً',    'icon' => 'graph-up-bold-duotone'],
                    ['title' => 'نمو القطاع',     'value' => '32% CAGR',        'icon' => 'chart-square-bold-duotone'],
                    ['title' => 'الشراكات',        'value' => '3 عملاء مؤكّدون',  'icon' => 'handshake-bold-duotone'],
                    ['title' => 'التقنية',         'value' => 'AI + IoT',        'icon' => 'code-square-bold-duotone'],
                ],
            ],
            [
                'title'    => 'مركز طبي متخصّص في التجميل والجلدية',
                'subtitle' => 'مركز طبي متكامل في حيّ الملقا بالرياض',
                'summary'  => 'قطاع الرعاية الصحية يشهد طلباً استثنائياً في المملكة. الفرصة تتضمّن ترخيصاً جاهزاً من هيئة الصحة العامة.',
                'sector'   => 'صحة',
                'city'     => 'الرياض',
                'region'   => 'الوسطى',
                'investment_min' => 25_000_000, 'investment_max' => 40_000_000,
                'expected_roi'   => 24.0, 'payback_months' => 30, 'duration_years' => 10,
                'risk_level'     => 'low', 'is_featured' => false,
                'cover_image'    => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1200&auto=format&fit=crop&q=70',
                'source'         => 'internal',
                'highlights'     => [
                    ['title' => 'الترخيص',        'value' => 'جاهز',            'icon' => 'shield-check-bold-duotone'],
                    ['title' => 'العائد المتوقّع', 'value' => '24% سنوياً',      'icon' => 'medal-star-bold-duotone'],
                    ['title' => 'حجم السوق',       'value' => '4.2 مليار ر.س',   'icon' => 'wallet-money-bold-duotone'],
                    ['title' => 'الإشغال المتوقع',  'value' => '85% سنة أولى',    'icon' => 'chart-square-bold-duotone'],
                ],
            ],
            [
                'title'    => 'مزرعة أعلاف وثروة حيوانية · القصيم',
                'subtitle' => 'مشروع زراعي متكامل بتقنية الري الحديث',
                'summary'  => 'يخدم الأمن الغذائي السعودي ضمن أولويات وزارة البيئة والزراعة. المشروع يشمل معالجة وتصدير المنتجات.',
                'sector'   => 'زراعة',
                'city'     => 'بريدة',
                'region'   => 'القصيم',
                'investment_min' => 80_000_000, 'investment_max' => 120_000_000,
                'expected_roi'   => 14.5, 'payback_months' => 54, 'duration_years' => 15,
                'risk_level'     => 'low', 'is_featured' => false,
                'cover_image'    => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1200&auto=format&fit=crop&q=70',
                'source'         => 'gov_api', 'source_name' => 'وزارة البيئة والمياه والزراعة',
                'highlights'     => [
                    ['title' => 'الأولوية',       'value' => 'أمن غذائي',       'icon' => 'shield-check-bold-duotone'],
                    ['title' => 'دعم حكومي',      'value' => 'حتى 50%',          'icon' => 'medal-star-bold-duotone'],
                    ['title' => 'التقنية',        'value' => 'ري ذكي',           'icon' => 'code-square-bold-duotone'],
                    ['title' => 'الوظائف',        'value' => '+180 وظيفة',       'icon' => 'users-group-two-rounded-bold-duotone'],
                ],
            ],
        ];

        foreach ($items as $data) {
            $slug = Str::slug($data['title'], '-', 'ar') . '-' . strtolower(Str::random(4));

            InvestmentOpportunity::updateOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'slug'          => $slug,
                    'description'   => $this->makeDescription($data),
                    'status'        => 'open',
                    'published_at'  => now()->subDays(rand(1, 30)),
                    'deadline_at'   => now()->addDays(rand(45, 120)),
                    'views_count'   => rand(120, 800),
                    'applications_count' => rand(2, 25),
                ])
            );
        }
    }

    private function makeDescription(array $data): string
    {
        return "## نظرة عامة على الفرصة\n\n"
             . "{$data['summary']}\n\n"
             . "## الموقع الاستراتيجي\n\n"
             . "يقع المشروع في {$data['city']}، {$data['region']}، وهي منطقة تشهد نمواً اقتصادياً ملحوظاً "
             . "ضمن مبادرات رؤية المملكة 2030 لتنويع الاقتصاد وتوطين الصناعات النوعية.\n\n"
             . "## الجدوى المالية\n\n"
             . "* **قيمة الاستثمار:** " . number_format($data['investment_min']) . " – " . number_format($data['investment_max']) . " ر.س\n"
             . "* **العائد المتوقّع:** {$data['expected_roi']}% سنوياً\n"
             . "* **فترة الاسترداد:** {$data['payback_months']} شهراً\n"
             . "* **مدة المشروع:** {$data['duration_years']} سنوات\n\n"
             . "## المخاطر والضمانات\n\n"
             . "يُصنَّف مستوى المخاطر لهذه الفرصة كـ **" . ['low' => 'منخفض', 'medium' => 'متوسط', 'high' => 'مرتفع'][$data['risk_level']] . "**، "
             . "وتشمل ضمانات المشروع دراسة جدوى معتمدة، تحليل حساسية شامل، وخطة إدارة مخاطر متكاملة.\n\n"
             . "## الشراكة مع رواد\n\n"
             . "تقدّم منصة رواد بلا حدود دعماً شاملاً للمستثمر، يشمل دراسة الجدوى المُحدَّثة، "
             . "التسهيلات التنظيمية، إعداد خطة التنفيذ، ومتابعة المشروع في مراحله الأولى.";
    }
}
