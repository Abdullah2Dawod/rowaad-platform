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
        $specs = Specialization::pluck('id', 'slug');

        $studies = [
            ['title' => 'دراسة جدوى مشروع مطعم أطعمة صحية في الرياض', 'excerpt' => 'تحليل شامل لمشروع مطعم يقدم وجبات صحية عضوية في العاصمة الرياض، يتضمن دراسة السوق، خطة التسويق، والتوقعات المالية لخمس سنوات.', 'sector' => 'الأغذية والمشروبات', 'spec' => 'business-consulting', 'pages' => 82, 'price' => 199, 'is_featured' => true],
            ['title' => 'دراسة جدوى مشروع مغسلة سيارات ذاتية الخدمة', 'excerpt' => 'دراسة مفصلة لإنشاء مغسلة سيارات ذاتية الخدمة بأحدث التقنيات في المدن الرئيسية، مع تحليل التكاليف والعائد الاستثماري.', 'sector' => 'الخدمات', 'spec' => 'business-consulting', 'pages' => 64, 'price' => 149],
            ['title' => 'دراسة جدوى مصنع صغير لتعبئة العسل', 'excerpt' => 'مشروع صناعي متوسط لتعبئة وتغليف العسل الطبيعي محلياً، يشمل خطوط الإنتاج، متطلبات الجودة، والتراخيص المطلوبة.', 'sector' => 'التصنيع', 'spec' => 'business-consulting', 'pages' => 96, 'price' => 249, 'is_featured' => true],
            ['title' => 'دراسة جدوى تطبيق خدمات منزلية عند الطلب', 'excerpt' => 'منصة رقمية تربط أصحاب المنازل بمقدمي الخدمات (سباكة، كهرباء، تنظيف). دراسة تشمل التقنية، نموذج الإيرادات، والتوسع.', 'sector' => 'التقنية', 'spec' => 'digital-transformation', 'pages' => 78, 'price' => 299],
            ['title' => 'دراسة جدوى مركز تدريب رقمي متخصص', 'excerpt' => 'إنشاء مركز تدريب متخصص في المهارات الرقمية والتحول الرقمي، مع دراسة الفئات المستهدفة والبرامج التدريبية المطلوبة.', 'sector' => 'التعليم', 'spec' => 'digital-transformation', 'pages' => 70, 'price' => 179],
            ['title' => 'دراسة جدوى محل تجزئة للمنتجات المستدامة', 'excerpt' => 'متجر متخصص في المنتجات الصديقة للبيئة والمستدامة، دراسة سلوك المستهلك السعودي وتوقعات نمو هذه الفئة.', 'sector' => 'التجزئة', 'spec' => 'marketing-strategy', 'pages' => 58, 'price' => 0],
        ];

        foreach ($studies as $s) {
            $slug = Str::slug($s['title']) . '-' . strtolower(Str::random(4));
            FeasibilityStudy::updateOrCreate(
                ['title' => $s['title']],
                [
                    'user_id'           => null,
                    'specialization_id' => $specs[$s['spec']] ?? null,
                    'slug'              => $slug,
                    'excerpt'           => $s['excerpt'],
                    'description'       => $s['excerpt'] . "\n\n" . str_repeat('محتوى تفصيلي للدراسة يشمل تحليل السوق، الجدوى الاقتصادية، والتوقعات المالية. ', 8),
                    'sector'            => $s['sector'],
                    'pages_count'       => $s['pages'],
                    'language'          => 'ar',
                    'price'             => $s['price'],
                    'is_free'           => $s['price'] == 0,
                    'status'            => 'approved',
                    'is_featured'       => $s['is_featured'] ?? false,
                    'reviewed_at'       => now(),
                ]
            );
        }
    }
}
