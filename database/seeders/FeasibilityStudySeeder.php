<?php

namespace Database\Seeders;

use App\Models\FeasibilityStudy;
use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeasibilityStudySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title'   => 'دراسة جدوى مطعم متخصص في المأكولات الصحية',
                'sector'  => 'مطاعم',
                'spec'    => 'feasibility',
                'excerpt' => 'دراسة شاملة لمشروع مطعم متخصص في المأكولات الصحية بالرياض، تشمل تحليل السوق، التكاليف، وتوقعات الإيرادات لمدة 3 سنوات.',
                'description' => "تتضمن الدراسة:\n\n• تحليل السوق المحلي وحجم الطلب\n• تحديد الشريحة المستهدفة والتموضع التنافسي\n• التصميم الوظيفي والتكاليف الرأسمالية\n• قائمة معدات المطبخ والصالة والتكاليف التقديرية\n• هيكل التشغيل والموارد البشرية\n• التسويق ووسائل الحصول على العملاء\n• قوائم مالية توقعية لمدة 3 سنوات (نقدية، أرباح، ميزانية)\n• حساب فترة الاسترداد ونقطة التعادل\n• تحليل حساسية ودراسة المخاطر",
                'price'   => 750, 'pages_count' => 45, 'featured' => true,
                'cover'   => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=900&auto=format&fit=crop&q=60',
            ],
            [
                'title'   => 'دراسة جدوى مصنع تعبئة مياه',
                'sector'  => 'صناعة',
                'spec'    => 'feasibility',
                'excerpt' => 'دراسة تفصيلية لمشروع مصنع تعبئة وتغليف مياه شرب معدنية بطاقة 20,000 عبوة يومياً في المنطقة الشرقية.',
                'description' => "تحتوي الدراسة على:\n\n• دراسة السوق للمياه المعبأة في المنطقة\n• المتطلبات الرسمية والتراخيص من هيئة الغذاء والدواء\n• خطوط الإنتاج والمعدات الرئيسية\n• الطاقة الإنتاجية وسلاسل التوريد\n• هيكل التكاليف الثابتة والمتغيرة\n• توقعات المبيعات والأرباح لخمس سنوات\n• تحليل النقطة الحرجة والعائد على الاستثمار",
                'price'   => 1500, 'pages_count' => 68, 'featured' => true,
                'cover'   => 'https://images.unsplash.com/photo-1581873372796-635b67ca2008?w=900&auto=format&fit=crop&q=60',
            ],
            [
                'title'   => 'دراسة جدوى تطبيق توصيل ذكي',
                'sector'  => 'تقنية',
                'spec'    => 'tech',
                'excerpt' => 'دراسة جدوى لتطبيق توصيل مبتكر يستخدم الذكاء الاصطناعي لتحسين المسارات، مع نموذج عمل مرن للسوق السعودي.',
                'description' => "تتضمن الدراسة:\n\n• تحليل سوق تطبيقات التوصيل في السعودية\n• النموذج التقني والبنية التحتية\n• استراتيجية التسعير ونماذج الاشتراك\n• تكاليف التطوير والصيانة السنوية\n• خطة التوسع الجغرافي\n• قوائم مالية توقعية لثلاث سنوات\n• تحليل المنافسين وفرص التميّز",
                'price'   => 950, 'pages_count' => 52, 'featured' => false,
                'cover'   => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=900&auto=format&fit=crop&q=60',
            ],
            [
                'title'   => 'دراسة جدوى مركز تدريب تقني',
                'sector'  => 'تعليم',
                'spec'    => 'management',
                'excerpt' => 'مركز تدريب متخصص في المهارات التقنية الحديثة (بايثون، بيانات، AI) بمنهج معتمد ونموذج ربحي مستدام.',
                'description' => "الدراسة تشمل:\n\n• تحليل الطلب على التدريب التقني\n• المناهج والشهادات المعتمدة\n• متطلبات المكان والتجهيزات\n• رواتب المدربين والفريق الإداري\n• استراتيجية جذب المتدربين\n• توقعات الالتحاق والدخل\n• نقطة التعادل والعائد المتوقع",
                'price'   => 650, 'pages_count' => 38, 'featured' => false,
                'cover'   => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=900&auto=format&fit=crop&q=60',
            ],
            [
                'title'   => 'دليل مجاني: خطوات كتابة دراسة جدوى ناجحة',
                'sector'  => 'عام',
                'spec'    => 'feasibility',
                'excerpt' => 'دليل مبسّط مجاني يشرح خطوات إعداد دراسة جدوى احترافية لمشروعك الجديد، مع قوالب جاهزة.',
                'description' => "يغطي الدليل:\n\n• ما هي دراسة الجدوى ولماذا؟\n• عناصر الدراسة الرئيسية\n• مصادر البيانات وطرق التحليل\n• قوالب جداول مالية جاهزة\n• أمثلة عملية من مشاريع ناجحة",
                'price'   => 0, 'pages_count' => 22, 'featured' => true,
                'cover'   => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=900&auto=format&fit=crop&q=60',
            ],
        ];

        foreach ($items as $data) {
            $spec = Specialization::where('slug', $data['spec'])->first();
            FeasibilityStudy::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'user_id'           => null, // platform-owned
                    'specialization_id' => $spec?->id,
                    'title'             => $data['title'],
                    'excerpt'           => $data['excerpt'],
                    'description'       => $data['description'],
                    'sector'            => $data['sector'],
                    'pages_count'       => $data['pages_count'],
                    'price'             => $data['price'],
                    'is_free'           => $data['price'] === 0,
                    'is_featured'       => $data['featured'],
                    'cover_image'       => $data['cover'],
                    'language'          => 'ar',
                    'status'            => FeasibilityStudy::STATUS_APPROVED,
                    'reviewed_at'       => now(),
                    'reviewed_by'       => 1,
                    'views_count'       => rand(50, 500),
                    'purchases_count'   => rand(5, 80),
                ]
            );
        }
    }
}
