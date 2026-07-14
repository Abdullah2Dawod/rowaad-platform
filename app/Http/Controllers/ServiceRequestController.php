<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ServiceRequestController extends Controller
{
    /**
     * Catalog of all services — reads DB-managed services (admin-editable).
     * Falls back to the built-in legacy list on empty DB (first-run safety).
     */
    public static function catalog(): array
    {
        $records = Service::active()->ordered()->get();
        if ($records->isEmpty()) {
            return self::legacyCatalog();
        }
        return $records->map(fn (Service $s) => [
            'slug'         => $s->slug,
            'title'        => $s->title,
            'tagline'      => $s->tagline,
            'icon'         => $s->icon,
            'hero_image'   => $s->hero_image,
            'summary'      => $s->summary,
            'includes'     => $s->includes ?? [],
            'deliverables' => $s->deliverables ?? [],
            'stats'        => $s->stats ?? [],
            'rich_content' => $s->rich_content ?? [],
            'featured'     => (bool) $s->featured,
        ])->all();
    }

    /** Legacy hardcoded catalog (seed source + fallback). */
    public static function legacyCatalog(): array
    {
        return [
            [
                'slug'  => 'economic-consulting',
                'title' => 'الاستشارات الاقتصادية',
                'tagline' => 'قراءات معمّقة للأسواق مدعومة بالبيانات',
                'icon'  => 'graph-up-bold-duotone',
                'featured' => true,
                'hero_image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1400&auto=format&fit=crop&q=70',
                'summary' => 'خدمات استشارية اقتصادية متكاملة للشركات والمؤسسات (B2B) — تحليل اقتصادي كلي وجزئي، تقييم فرص السوق، وصياغة توصيات استراتيجية مبنية على بيانات موثوقة.',
                'includes' => [
                    'تحليل السوق والمنافسين', 'دراسات القطاع', 'تقييم فرص التوسّع',
                    'تحليل جدوى القرارات الاقتصادية', 'تقارير دورية للسوق السعودي',
                ],
                'deliverables' => [
                    'تقرير تحليلي مفصّل (60-120 صفحة)', 'ملخّص تنفيذي', 'خرائط ورسوم توضيحية',
                    'ورشة عرض للنتائج', 'استشارة متابعة لمدة 30 يوماً',
                ],
                'stats' => [
                    ['label' => 'مشروع منجز', 'value' => '+180'],
                    ['label' => 'دقة التنبؤات', 'value' => '94%'],
                    ['label' => 'مدة التنفيذ',   'value' => '4-8 أسابيع'],
                ],
            ],
            [
                'slug'  => 'feasibility-studies',
                'title' => 'دراسات الجدوى الاقتصادية',
                'tagline' => 'دراسات جدوى معتمدة تُقنع الممولين',
                'icon'  => 'clipboard-list-bold-duotone',
                'featured' => true,
                'hero_image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=1400&auto=format&fit=crop&q=70',
                'summary' => 'دراسات جدوى شاملة (فنية، مالية، تسويقية، قانونية) وفق أعلى المعايير — تشمل الدراسات الاقتصادية المتخصّصة للمشاريع النوعية والصناعات المتخصّصة.',
                'includes' => [
                    'الدراسة السوقية والتنافسية', 'الدراسة الفنية والتشغيلية',
                    'الدراسة المالية (5-10 سنوات)', 'تحليل الحساسية والمخاطر',
                    'دراسات اقتصادية متخصّصة لقطاعات محدّدة',
                ],
                'deliverables' => [
                    'دراسة جدوى شاملة معتمدة', 'قوائم مالية توقعية Excel',
                    'خطة استرداد رأس المال', 'تقييم المخاطر وخطة إدارتها',
                ],
                'stats' => [
                    ['label' => 'دراسة معتمدة',  'value' => '+320'],
                    ['label' => 'نسبة الاعتماد', 'value' => '97%'],
                    ['label' => 'مدة الإنجاز',   'value' => '6-10 أسابيع'],
                ],
            ],
            [
                'slug'  => 'strategic-planning',
                'title' => 'التخطيط الاستراتيجي',
                'tagline' => 'خارطة طريق واضحة بأهداف قابلة للقياس',
                'icon'  => 'target-bold-duotone',
                'featured' => true,
                'hero_image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1400&auto=format&fit=crop&q=70',
                'summary' => 'بناء خطط استراتيجية متكاملة لمؤسّستك مع مؤشرات أداء قابلة للقياس، ودعم شامل لرواد الأعمال من الفكرة إلى التنفيذ.',
                'includes' => [
                    'تحليل SWOT و PESTLE',
                    'صياغة الرؤية والرسالة والقيم',
                    'خرائط الطريق التنفيذية',
                    'مؤشرات الأداء الرئيسية KPIs',
                    'دعم رواد الأعمال — من الفكرة إلى الإطلاق',
                ],
                'deliverables' => [
                    'وثيقة الخطة الاستراتيجية', 'لوحة KPIs تفاعلية',
                    'خطة التنفيذ السنوية', 'ورش تدريبية للفريق التنفيذي',
                ],
                'stats' => [
                    ['label' => 'خطة معتمدة', 'value' => '+140'],
                    ['label' => 'رواد أعمال مدعومون', 'value' => '+65'],
                    ['label' => 'مدة الإنجاز', 'value' => '6-12 أسبوعاً'],
                ],
            ],
            [
                'slug'  => 'foreign-investment',
                'title' => 'تأسيس الشركات الأجنبية والاستثمار الأجنبي',
                'tagline' => 'بوابتك القانونية لدخول السوق السعودي',
                'icon'  => 'buildings-2-bold-duotone',
                'featured' => true,
                'hero_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1400&auto=format&fit=crop&q=70',
                'summary' => 'خدمات متكاملة لتأسيس الشركات الأجنبية في السعودية عبر ترخيص الاستثمار من وزارة الاستثمار — من التخطيط الأولي إلى التسجيل التجاري والتشغيل الفعلي.',
                'includes' => [
                    'دراسة الجدوى للاستثمار الأجنبي',
                    'الحصول على ترخيص وزارة الاستثمار',
                    'التسجيل التجاري والضريبي',
                    'فتح الحسابات البنكية للشركة',
                    'إعداد العقود التأسيسية',
                    'دعم استقدام الكوادر',
                ],
                'deliverables' => [
                    'ترخيص وزارة الاستثمار', 'سجل تجاري كامل',
                    'ملف امتثال ضريبي', 'دليل التشغيل التنظيمي',
                ],
                'stats' => [
                    ['label' => 'شركة أجنبية مؤسّسة', 'value' => '+45'],
                    ['label' => 'نسبة النجاح', 'value' => '100%'],
                    ['label' => 'مدة التأسيس', 'value' => '4-8 أسابيع'],
                ],
            ],
            [
                'slug'  => 'governance-compliance',
                'title' => 'الحوكمة والامتثال المؤسسي',
                'tagline' => 'أنظمة حوكمة معتمدة تحمي مؤسّستك',
                'icon'  => 'scale-bold-duotone',
                'featured' => true,
                'hero_image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=1400&auto=format&fit=crop&q=70',
                'summary' => 'بناء أنظمة الحوكمة المؤسسية وضمان الامتثال للأنظمة والتشريعات السعودية — لائحة حوكمة، مجالس الإدارة، لجان التدقيق، وسياسات إدارة المخاطر.',
                'includes' => [
                    'صياغة لائحة الحوكمة',
                    'هيكلة مجلس الإدارة واللجان',
                    'سياسات الإفصاح والشفافية',
                    'الامتثال لأنظمة هيئة السوق المالية',
                    'إدارة تعارض المصالح',
                ],
                'deliverables' => [
                    'دليل الحوكمة المؤسسية', 'لوائح اللجان الفرعية',
                    'سياسات المخاطر والامتثال', 'تدريب أعضاء المجلس',
                ],
                'stats' => [
                    ['label' => 'مؤسسة معتمدة', 'value' => '+90'],
                    ['label' => 'الامتثال الكامل', 'value' => '100%'],
                    ['label' => 'مدة التنفيذ', 'value' => '8-14 أسبوعاً'],
                ],
            ],
            [
                'slug'  => 'training',
                'title' => 'التدريب المؤسسي التخصّصي',
                'tagline' => 'برامج تدريب تحوّل فرقك إلى محترفين',
                'icon'  => 'diploma-verified-bold-duotone',
                'featured' => false,
                'hero_image' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1400&auto=format&fit=crop&q=70',
                'summary' => 'برامج تدريبية متخصّصة للكوادر الإدارية والقيادية — مصمّمة خصيصاً لاحتياجات مؤسّستك، بمدرّبين معتمدين وشهادات دولية.',
                'includes' => [
                    'برامج القيادة التنفيذية',
                    'التخطيط الاستراتيجي التطبيقي',
                    'إدارة المشاريع (PMP)',
                    'التحول الرقمي',
                    'ورش عمل مخصّصة داخل الشركة',
                ],
                'deliverables' => [
                    'برنامج تدريبي مصمّم خصيصاً', 'مواد تدريبية عربية/إنجليزية',
                    'شهادات معتمدة للمشاركين', 'تقييم أثر التدريب',
                ],
                'stats' => [
                    ['label' => 'متدرّب سنوياً', 'value' => '+1,200'],
                    ['label' => 'برامج معتمدة', 'value' => '35+'],
                    ['label' => 'رضا المتدرّبين', 'value' => '96%'],
                ],
            ],
            [
                'slug'  => 'business-development',
                'title' => 'تطوير الأعمال والتوسّع',
                'tagline' => 'حلول مبتكرة لتوسيع نطاق أعمالك',
                'icon'  => 'rocket-bold-duotone',
                'featured' => false,
                'hero_image' => 'https://images.unsplash.com/photo-1552581234-26160f608093?w=1400&auto=format&fit=crop&q=70',
                'summary' => 'استشارات تطوير الأعمال للشركات الطموحة — بناء نماذج تشغيل قابلة للتوسّع، تحديد فرص الشراكات، وتحسين الكفاءة التشغيلية.',
                'includes' => [
                    'تصميم نماذج الأعمال', 'استراتيجيات التوسّع',
                    'ربط الشراكات B2B', 'تحسين العمليات', 'رقمنة العمليات',
                ],
                'deliverables' => [
                    'استراتيجية توسّع متكاملة', 'خطة تشغيلية 12 شهراً',
                    'دليل عمليات محدَّث', 'مؤشرات قياس النمو',
                ],
                'stats' => [
                    ['label' => 'مشروع نمو', 'value' => '+70'],
                    ['label' => 'زيادة الإيرادات', 'value' => 'متوسّط 35%'],
                    ['label' => 'مدة الإنجاز', 'value' => '3-6 أشهر'],
                ],
            ],
            [
                'slug'  => 'financial-consulting',
                'title' => 'الاستشارات المالية',
                'tagline' => 'إدارة مالية احترافية لتحسين أدائك',
                'icon'  => 'wallet-money-bold-duotone',
                'featured' => false,
                'hero_image' => 'https://images.unsplash.com/photo-1579621970588-a35d0e7ab9b6?w=1400&auto=format&fit=crop&q=70',
                'summary' => 'إعادة هيكلة مالية، تحليل الأداء المالي، إعداد الموازنات، وتقييم الشركات — بأيدي خبراء ماليين معتمدين (CFA / CPA).',
                'includes' => [
                    'تقييم الشركات', 'إعادة الهيكلة المالية',
                    'إعداد الموازنات التقديرية', 'تحليل الأداء المالي',
                    'دراسات التمويل والاندماج',
                ],
                'deliverables' => [
                    'تقرير التقييم الشامل', 'خطة مالية 5 سنوات',
                    'موازنة تشغيلية تفصيلية', 'مؤشرات مالية دورية',
                ],
                'stats' => [
                    ['label' => 'شركة مقيّمة', 'value' => '+110'],
                    ['label' => 'خبراء معتمدون', 'value' => '15+'],
                    ['label' => 'مدة الإنجاز', 'value' => '3-6 أسابيع'],
                ],
            ],
        ];
    }

    public function show(string $slug): Response
    {
        $catalog = self::catalog();
        $service = collect($catalog)->firstWhere('slug', $slug);
        abort_if(! $service, 404);

        $related = collect($catalog)
            ->where('slug', '!=', $slug)
            ->shuffle()
            ->take(3)
            ->values()
            ->all();

        return Inertia::render('Services/Show', [
            'service' => $service,
            'related' => $related,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'service_slug'  => ['required', 'string', 'max:100'],
            'service_title' => ['required', 'string', 'max:150'],
            'company_name'  => ['required', 'string', 'max:150'],
            'contact_name'  => ['required', 'string', 'max:120'],
            'contact_email' => ['required', 'email', 'max:150'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'company_size'  => ['nullable', 'string', 'max:40'],
            'budget'        => ['nullable', 'numeric', 'min:0'],
            'timeline'      => ['nullable', 'string', 'max:40'],
            'project_brief' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            ServiceRequest::create($data + [
                'user_id' => $request->user()?->id,
                'status'  => 'new',
            ]);
        } catch (\Throwable $e) {
            Log::warning('[Service request] failed: ' . $e->getMessage());
        }

        return back()->with('success', 'تم استلام طلبك بنجاح. سيتواصل معك فريق رواد خلال 24 ساعة.');
    }
}
