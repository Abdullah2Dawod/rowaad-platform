<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ConsultantSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name'        => 'د. خالد الشمري',
                'name_en'     => 'Dr. Khalid Al-Shammari',
                'email'       => 'khalid@rowaad.org',
                'title'       => 'مستشار اقتصادي أول',
                'spec'        => 'economic',
                'city'        => 'الرياض',
                'exp'         => 15,
                'rate'        => 400,
                'bio'         => 'خبرة تزيد عن 15 عاماً في تقديم الاستشارات الاقتصادية للمؤسسات الحكومية والشركات الكبرى، مع تخصص عميق في تحليل الأسواق ودراسات الجدوى المالية.',
                'avatar'      => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop&q=70',
                'featured'    => true,
                'rating'      => 4.9,  'ratings' => 87, 'sessions' => 124,
                'langs'       => ['ar','en'],
            ],
            [
                'name'        => 'د. سارة العتيبي',
                'name_en'     => 'Dr. Sarah Al-Otaibi',
                'email'       => 'sarah@rowaad.org',
                'title'       => 'مستشارة استراتيجية وتخطيط',
                'spec'        => 'strategy',
                'city'        => 'جدة',
                'exp'         => 12,
                'rate'        => 350,
                'bio'         => 'مستشارة استراتيجية معتمدة، ساعدت أكثر من 40 مؤسسة على صياغة خططها الاستراتيجية طويلة المدى وتحديد أولوياتها التنفيذية.',
                'avatar'      => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop&q=70',
                'featured'    => true,
                'rating'      => 4.8,  'ratings' => 62, 'sessions' => 98,
                'langs'       => ['ar','en'],
            ],
            [
                'name'        => 'أ. محمد الغامدي',
                'name_en'     => 'Mohammed Al-Ghamdi',
                'email'       => 'mohammed@rowaad.org',
                'title'       => 'خبير دراسات الجدوى',
                'spec'        => 'feasibility',
                'city'        => 'الرياض',
                'exp'         => 10,
                'rate'        => 300,
                'bio'         => 'متخصص في إعداد دراسات الجدوى الاقتصادية والفنية لمشاريع رواد الأعمال، مع سجل حافل بأكثر من 150 دراسة معتمدة.',
                'avatar'      => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop&q=70',
                'featured'    => false,
                'rating'      => 4.7,  'ratings' => 41, 'sessions' => 76,
                'langs'       => ['ar'],
            ],
            [
                'name'        => 'د. نورة القحطاني',
                'name_en'     => 'Dr. Noura Al-Qahtani',
                'email'       => 'noura@rowaad.org',
                'title'       => 'مستشارة الحوكمة والامتثال',
                'spec'        => 'governance',
                'city'        => 'الرياض',
                'exp'         => 14,
                'rate'        => 450,
                'bio'         => 'خبيرة معتمدة في أنظمة الحوكمة المؤسسية والامتثال، مع خبرة دولية مع شركات القطاع المالي في المنطقة.',
                'avatar'      => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&h=400&fit=crop&q=70',
                'featured'    => true,
                'rating'      => 5.0,  'ratings' => 53, 'sessions' => 89,
                'langs'       => ['ar','en'],
            ],
            [
                'name'        => 'أ. عبدالعزيز الدوسري',
                'name_en'     => 'Abdulaziz Al-Dosari',
                'email'       => 'aziz@rowaad.org',
                'title'       => 'استشاري التسويق الرقمي',
                'spec'        => 'marketing',
                'city'        => 'الدمام',
                'exp'         => 8,
                'rate'        => 280,
                'bio'         => 'مختص بالتسويق الرقمي وبناء العلامات التجارية، ساعد أكثر من 60 علامة تجارية سعودية على النمو الرقمي المستدام.',
                'avatar'      => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop&q=70',
                'featured'    => false,
                'rating'      => 4.6,  'ratings' => 38, 'sessions' => 64,
                'langs'       => ['ar','en'],
            ],
            [
                'name'        => 'د. فيصل المطيري',
                'name_en'     => 'Dr. Faisal Al-Mutairi',
                'email'       => 'faisal@rowaad.org',
                'title'       => 'مستشار مالي واستثماري',
                'spec'        => 'finance',
                'city'        => 'الرياض',
                'exp'         => 18,
                'rate'        => 500,
                'bio'         => 'خبير مالي معتمد (CFA) بخبرة تزيد عن 18 عاماً في إدارة المحافظ الاستثمارية وتقييم الشركات في القطاع الخاص.',
                'avatar'      => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&h=400&fit=crop&q=70',
                'featured'    => true,
                'rating'      => 4.9,  'ratings' => 71, 'sessions' => 112,
                'langs'       => ['ar','en'],
            ],
            [
                'name'        => 'أ. ريم السبيعي',
                'name_en'     => 'Reem Al-Subaie',
                'email'       => 'reem@rowaad.org',
                'title'       => 'مستشارة الموارد البشرية',
                'spec'        => 'hr',
                'city'        => 'جدة',
                'exp'         => 9,
                'rate'        => 260,
                'bio'         => 'متخصصة في تطوير سياسات الموارد البشرية وبناء أنظمة إدارة الأداء، مع خبرة عملية في قطاعات متعددة.',
                'avatar'      => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=400&h=400&fit=crop&q=70',
                'featured'    => false,
                'rating'      => 4.7,  'ratings' => 29, 'sessions' => 47,
                'langs'       => ['ar','en'],
            ],
            [
                'name'        => 'أ. أحمد الزهراني',
                'name_en'     => 'Ahmed Al-Zahrani',
                'email'       => 'ahmed@rowaad.org',
                'title'       => 'مستشار تقني وتحول رقمي',
                'spec'        => 'tech',
                'city'        => 'الرياض',
                'exp'         => 11,
                'rate'        => 380,
                'bio'         => 'يقود مبادرات التحول الرقمي للمؤسسات، متخصص في هندسة الأنظمة وحلول الذكاء الاصطناعي التطبيقية.',
                'avatar'      => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop&q=70',
                'featured'    => false,
                'rating'      => 4.8,  'ratings' => 44, 'sessions' => 68,
                'langs'       => ['ar','en'],
            ],

            // ─── Tech Consultants (added) ───
            [
                'name'        => 'م. سلطان الغامدي',
                'name_en'     => 'Eng. Sultan Al-Ghamdi',
                'email'       => 'sultan.tech@rowaad.org',
                'title'       => 'مستشار تقني · مهندس أنظمة',
                'spec'        => 'tech',
                'city'        => 'الرياض',
                'exp'         => 12,
                'rate'        => 420,
                'bio'         => 'مهندس أنظمة معتمد (CISSP, PMP, AWS Solutions Architect). خبير في التحول الرقمي وبناء الحلول التقنية للمؤسسات — أنظمة ERP، منصات SaaS، والذكاء الاصطناعي التطبيقي.',
                'avatar'      => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&h=400&fit=crop&q=70',
                'featured'    => true,
                'rating'      => 4.9, 'ratings' => 62, 'sessions' => 95,
                'langs'       => ['ar','en'],
            ],
            [
                'name'        => 'د. ندى الحربي',
                'name_en'     => 'Dr. Nada Al-Harbi',
                'email'       => 'nada.tech@rowaad.org',
                'title'       => 'مستشارة تقنية · متخصصة بيانات وذكاء اصطناعي',
                'spec'        => 'tech',
                'city'        => 'جدة',
                'exp'         => 9,
                'rate'        => 400,
                'bio'         => 'دكتوراه في علوم الحاسب من جامعة KAUST. استشارية متخصّصة في تصميم استراتيجيات البيانات، وبناء نماذج الذكاء الاصطناعي التطبيقية للشركات الناشئة والمؤسسات.',
                'avatar'      => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop&q=70',
                'featured'    => true,
                'rating'      => 4.85, 'ratings' => 38, 'sessions' => 51,
                'langs'       => ['ar','en'],
            ],
            [
                'name'        => 'م. عمر الزهراني',
                'name_en'     => 'Eng. Omar Al-Zahrani',
                'email'       => 'omar.tech@rowaad.org',
                'title'       => 'مستشار تقني · هندسة برمجيات وسحابة',
                'spec'        => 'tech',
                'city'        => 'الخبر',
                'exp'         => 8,
                'rate'        => 360,
                'bio'         => 'مهندس برمجيات أول مع خبرة عميقة في الحوسبة السحابية (AWS, Azure) وأمن المعلومات. يدعم الشركات في تصميم بنى تحتية سحابية آمنة وقابلة للتوسّع.',
                'avatar'      => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop&q=70',
                'featured'    => false,
                'rating'      => 4.7, 'ratings' => 29, 'sessions' => 41,
                'langs'       => ['ar','en'],
            ],
        ];

        foreach ($items as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'role'              => 'consultant',
                    'password'          => bcrypt('password'),
                    'email_verified_at' => now(),
                    'phone'             => '+9665' . rand(10000000, 99999999),
                    'locale'            => 'ar',
                ]
            );

            $spec = Specialization::where('slug', $data['spec'])->first();

            Consultant::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'status'              => Consultant::STATUS_APPROVED,
                    'completed_step'      => 3,
                    'submitted_at'        => now()->subDays(rand(30, 120)),
                    'approved_at'         => now()->subDays(rand(1, 30)),
                    'reviewed_by'         => 1,
                    'full_name_ar'        => $data['name'],
                    'full_name_en'        => $data['name_en'],
                    'professional_title'  => $data['title'],
                    'city'                => $data['city'],
                    'country'             => 'SA',
                    'nationality'         => 'سعودي',
                    'bio_ar'              => $data['bio'],
                    'avatar_path'         => $data['avatar'],
                    'specialization_id'   => $spec?->id,
                    'years_experience'    => $data['exp'],
                    'hourly_rate'         => $data['rate'],
                    'session_duration_min' => 60,
                    'languages'           => $data['langs'],
                    'is_featured'         => $data['featured'],
                    'rating_avg'          => $data['rating'],
                    'rating_count'        => $data['ratings'],
                    'sessions_completed'  => $data['sessions'],
                    'services'            => [
                        ['title' => 'استشارة فردية', 'duration_min' => 60],
                        ['title' => 'مراجعة خطة عمل', 'duration_min' => 90],
                    ],
                ]
            );
        }
    }
}
