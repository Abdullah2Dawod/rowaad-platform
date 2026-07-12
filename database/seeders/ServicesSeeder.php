<?php

namespace Database\Seeders;

use App\Http\Controllers\ServiceRequestController;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        // Read from the original hardcoded catalog and persist.
        // Uses reflection so we can still call it even after catalog() moves to DB-backed.
        $ref     = new \ReflectionMethod(ServiceRequestController::class, 'legacyCatalog');
        $catalog = $ref->invoke(null);

        foreach ($catalog as $i => $s) {
            Service::updateOrCreate(
                ['slug' => $s['slug']],
                [
                    'title'        => $s['title'],
                    'tagline'      => $s['tagline'] ?? null,
                    'icon'         => $s['icon'] ?? null,
                    'hero_image'   => $s['hero_image'] ?? null,
                    'summary'      => $s['summary'] ?? null,
                    'includes'     => $s['includes'] ?? [],
                    'deliverables' => $s['deliverables'] ?? [],
                    'stats'        => $s['stats'] ?? [],
                    'featured'     => (bool) ($s['featured'] ?? false),
                    'is_active'    => true,
                    'sort_order'   => $i * 10,
                ]
            );
        }

        // ─── Digital Transformation ─── (new service)
        Service::updateOrCreate(
            ['slug' => 'digital-transformation'],
            [
                'title'      => 'التحوّل الرقمي',
                'tagline'    => 'رَقمن أعمالك بعقلية استراتيجية وحلول تطبيقية',
                'icon'       => 'code-2-bold-duotone',
                'hero_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1400&auto=format&fit=crop&q=70',
                'summary'    => 'استراتيجيات وتنفيذ متكامل للتحوّل الرقمي: أتمتة العمليات، بناء رحلات رقمية للعملاء، واختيار الأنظمة الملائمة (ERP/CRM/BI) مع خطة تنفيذ واقعية وقابلة للقياس.',
                'includes'   => [
                    ['item' => 'تشخيص النضج الرقمي وتحديد الفجوات'],
                    ['item' => 'استراتيجية التحوّل الرقمي وخارطة الطريق'],
                    ['item' => 'أتمتة العمليات (RPA / Workflows)'],
                    ['item' => 'اختيار وتقييم الأنظمة (ERP · CRM · BI)'],
                    ['item' => 'تصميم رحلة العميل الرقمية'],
                    ['item' => 'حوكمة البيانات وأمن المعلومات'],
                ],
                'deliverables' => [
                    ['item' => 'تقرير النضج الرقمي والفجوات'],
                    ['item' => 'خارطة طريق تنفيذية 24 شهراً'],
                    ['item' => 'مؤشرات قياس الأثر الرقمي (KPIs)'],
                    ['item' => 'ورش تدريب للفريق التنفيذي'],
                ],
                'stats' => [
                    ['label' => 'مشروع تحوّل رقمي', 'value' => '+55'],
                    ['label' => 'نسبة النجاح', 'value' => '92%'],
                    ['label' => 'مدة الإنجاز', 'value' => '8-16 أسبوع'],
                ],
                'featured'   => true,
                'is_active'  => true,
                'sort_order' => 25,
            ]
        );
    }
}
