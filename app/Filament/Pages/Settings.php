<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'الإعدادات';
    protected static ?string $navigationGroup = 'إدارة المستخدمين';
    protected static ?int    $navigationSort  = 99;
    protected static string  $view            = 'filament.pages.settings';
    protected static ?string $title           = 'إعدادات المنصة';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public function mount(): void
    {
        $user = auth()->user();

        $this->form->fill([
            // Image fields start EMPTY so Filepond never enters "waiting" state.
            // Current images are shown as plain <img> previews below each field.
            'profile_avatar' => null,
            'profile' => [
                'name'   => $user->name,
                'email'  => $user->email,
                'new_password' => null,
                'new_password_confirmation' => null,
            ],
            // General — image fields (logo_upload, logo_dark_upload, favicon_upload) stay null
            'general' => [
                'name_ar'          => SiteSetting::get('site.name_ar'),
                'name_en'          => SiteSetting::get('site.name_en'),
                'tagline_ar'       => SiteSetting::get('site.tagline_ar'),
                'tagline_en'       => SiteSetting::get('site.tagline_en'),
                'logo_upload'      => null,
                'logo_dark_upload' => null,
                'favicon_upload'   => null,
                'contact_email'    => SiteSetting::get('site.contact_email'),
                'contact_phone'    => SiteSetting::get('site.contact_phone'),
                'contact_address'  => SiteSetting::get('site.contact_address'),
                'menu_order'       => SiteSetting::get('site.menu_order', []),
            ],
            // Social / Marketing
            'social' => [
                'twitter'   => SiteSetting::get('social.twitter'),
                'linkedin'  => SiteSetting::get('social.linkedin'),
                'instagram' => SiteSetting::get('social.instagram'),
                'facebook'  => SiteSetting::get('social.facebook'),
                'youtube'   => SiteSetting::get('social.youtube'),
                'tiktok'    => SiteSetting::get('social.tiktok'),
                'snapchat'  => SiteSetting::get('social.snapchat'),
                'whatsapp'  => SiteSetting::get('social.whatsapp'),
                'telegram'  => SiteSetting::get('social.telegram'),
            ],
            'marketing' => [
                'gtm_id'          => SiteSetting::get('marketing.gtm_id'),
                'ga4_id'          => SiteSetting::get('marketing.ga4_id'),
                'meta_pixel'      => SiteSetting::get('marketing.meta_pixel'),
                'tiktok_pixel'    => SiteSetting::get('marketing.tiktok_pixel'),
                'snap_pixel'      => SiteSetting::get('marketing.snap_pixel'),
                'hotjar_id'       => SiteSetting::get('marketing.hotjar_id'),
                'tawk_id'         => SiteSetting::get('marketing.tawk_id'),
                'seo_title'       => SiteSetting::get('marketing.seo_title'),
                'seo_description' => SiteSetting::get('marketing.seo_description'),
                'seo_keywords'    => SiteSetting::get('marketing.seo_keywords'),
            ],
            'about' => [
                'hero_image_upload'        => null,
                'partnership_image_upload' => null,
                'hero_eyebrow'             => SiteSetting::get('about.hero_eyebrow', 'من نحن · قصتنا'),
                'hero_title_line1'         => SiteSetting::get('about.hero_title_line1', 'نسعى لأن نكون'),
                'hero_title_highlight'     => SiteSetting::get('about.hero_title_highlight', 'أيقونة رائدة'),
                'hero_title_line2'         => SiteSetting::get('about.hero_title_line2', 'في قطاع الاستشارات الاقتصادية'),
                'hero_description'         => SiteSetting::get('about.hero_description', 'منذ تأسيسنا عام 2016 بترخيص رسمي من وزارة التجارة السعودية، ونحن نقدّم الاستشارات الاقتصادية والإدارية ودراسات الجدوى لرواد الأعمال والمؤسسات، بخبرة محلية عميقة ومعايير عالمية.'),
                'license_number'           => SiteSetting::get('about.license_number', '#12047'),
                'locations'                => SiteSetting::get('about.locations', 'الرياض · لندن'),
                'years_experience'         => SiteSetting::get('about.years_experience', '+9 سنوات خبرة'),
                'vision_title'             => SiteSetting::get('about.vision_title', "خدمات مبتكرة ومميزة\nتصنع تطوراً ونمواً مستداماً"),
                'vision_body'              => SiteSetting::get('about.vision_body', 'نطمح أن نكون الخيار الأول لرواد الأعمال والمؤسسات في المنطقة، عبر تقديم خدمات استشارية تُحدث فرقاً حقيقياً وتقود نحو نموٍّ مستدام.'),
                'mission_title'            => SiteSetting::get('about.mission_title', "أفضل الأساليب الإدارية\nوالتدريبية المساندة للأعمال"),
                'mission_body'             => SiteSetting::get('about.mission_body', 'إيجاد وتطوير أفضل الطرق والمنهجيات التي تدعم أعمال عملائنا وتُمكّنهم من اتخاذ قرارات واثقة.'),
                'values_title'             => SiteSetting::get('about.values_title', 'مبادئ لا نساوم عليها'),
                'values'                   => SiteSetting::get('about.values', []),
                'advantages_title'         => SiteSetting::get('about.advantages_title', 'مزايا تجعلنا شريكك الأمثل'),
                'advantages'               => SiteSetting::get('about.advantages', []),
            ],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('settings_tabs')->tabs([
                // ===== TAB 1: Personal Profile =====
                Tab::make('الملف الشخصي')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Forms\Components\Section::make('بياناتي الشخصية')
                            ->description('يتحكم الأدمن ببياناته الشخصية من هنا.')
                            ->schema([
                                // ── Avatar block: current preview + upload + remove ──
                                Forms\Components\Group::make([
                                    Forms\Components\Placeholder::make('current_avatar_preview')
                                        ->label('')
                                        ->content(function () {
                                            $user = auth()->user();
                                            $url  = $user->avatar ? asset('storage/'.$user->avatar) : null;
                                            $initials = collect(explode(' ', (string) $user->name))
                                                ->map(fn ($p) => mb_substr($p, 0, 1))
                                                ->take(2)->implode('');
                                            return new HtmlString(view('filament.components.avatar-preview', [
                                                'url' => $url, 'initials' => $initials, 'name' => $user->name,
                                            ])->render());
                                        }),
                                    Forms\Components\FileUpload::make('profile_avatar')
                                        ->label('اختر صورة جديدة (اختياري)')
                                        ->helperText('PNG / JPG · حد أقصى 2MB · تُدوَّر تلقائياً')
                                        ->image()
                                        ->disk('public')
                                        ->directory('avatars')
                                        ->visibility('public')
                                        ->maxSize(2048)
                                        ->imagePreviewHeight('80'),
                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('removeAvatar')
                                            ->label('حذف الصورة الحالية')
                                            ->icon('heroicon-o-trash')
                                            ->color('danger')
                                            ->size('sm')
                                            ->visible(fn () => (bool) auth()->user()->avatar)
                                            ->requiresConfirmation()
                                            ->action(function () {
                                                $user = auth()->user();
                                                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                                                    Storage::disk('public')->delete($user->avatar);
                                                }
                                                $user->avatar = null;
                                                $user->save();
                                                Notification::make()->title('تم حذف الصورة')->success()->send();
                                                return redirect(request()->header('Referer') ?: static::getUrl());
                                            }),
                                    ]),
                                ])->columnSpan(1),

                                Forms\Components\Group::make([
                                    Forms\Components\TextInput::make('profile.name')
                                        ->label('الاسم الكامل')
                                        ->required()
                                        ->maxLength(120),
                                    Forms\Components\TextInput::make('profile.email')
                                        ->label('البريد الإلكتروني')
                                        ->email()
                                        ->required()
                                        ->maxLength(150),
                                ])->columnSpan(1),
                            ])->columns(2),
                        Forms\Components\Section::make('تغيير كلمة المرور')
                            ->description('اتركها فارغة إذا لم ترغب في التغيير.')
                            ->schema([
                                Forms\Components\TextInput::make('profile.new_password')
                                    ->label('كلمة المرور الجديدة')
                                    ->password()
                                    ->minLength(8)
                                    ->confirmed()
                                    ->nullable(),
                                Forms\Components\TextInput::make('profile.new_password_confirmation')
                                    ->label('تأكيد كلمة المرور')
                                    ->password()
                                    ->nullable(),
                            ])->columns(2)->collapsed(),
                    ]),

                // ===== TAB 2: General site settings =====
                Tab::make('إعدادات النظام')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        Forms\Components\Section::make('هوية المنصة')->schema([
                            Forms\Components\TextInput::make('general.name_ar')->label('اسم الموقع (عربي)')->required(),
                            Forms\Components\TextInput::make('general.name_en')->label('اسم الموقع (English)')->required(),
                            Forms\Components\TextInput::make('general.tagline_ar')->label('الشعار الجانبي (عربي)'),
                            Forms\Components\TextInput::make('general.tagline_en')->label('الشعار الجانبي (English)'),
                        ])->columns(2),

                        Forms\Components\Section::make('الشعار والأيقونات')
                            ->description('ارفع صورة جديدة لتحلّ محلّ الحالية. الصورة الحالية تظهر أسفل كل حقل.')
                            ->schema([
                                // Light logo ───────────────────────────
                                Forms\Components\Group::make([
                                    Forms\Components\Placeholder::make('logo_current')
                                        ->label('')
                                        ->content(fn () => new HtmlString(view('filament.components.setting-image-preview', [
                                            'label' => 'اللوجو الحالي (فاتح)',
                                            'url'   => SiteSetting::get('site.logo') ? '/storage/'.ltrim(SiteSetting::get('site.logo'),'/') : null,
                                            'bg'    => '#F8FAFC',
                                        ])->render())),
                                    Forms\Components\FileUpload::make('general.logo_upload')
                                        ->label('اختر صورة جديدة (اختياري)')
                                        ->helperText('PNG/SVG · حد أقصى 2MB')
                                        ->image()->disk('public')->directory('site')->maxSize(2048),
                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('removeLogoLight')
                                            ->label('حذف اللوجو الحالي')->icon('heroicon-o-trash')->color('danger')->size('sm')
                                            ->visible(fn () => (bool) SiteSetting::get('site.logo'))
                                            ->requiresConfirmation()
                                            ->action(fn () => $this->clearImageSetting('site.logo')),
                                    ]),
                                ])->columnSpan(1),

                                // Dark logo ───────────────────────────
                                Forms\Components\Group::make([
                                    Forms\Components\Placeholder::make('logo_dark_current')
                                        ->label('')
                                        ->content(fn () => new HtmlString(view('filament.components.setting-image-preview', [
                                            'label' => 'اللوجو الحالي (ليلي)',
                                            'url'   => SiteSetting::get('site.logo_dark') ? '/storage/'.ltrim(SiteSetting::get('site.logo_dark'),'/') : null,
                                            'bg'    => '#0A1729',
                                            'dark'  => true,
                                        ])->render())),
                                    Forms\Components\FileUpload::make('general.logo_dark_upload')
                                        ->label('اختر صورة جديدة (اختياري)')
                                        ->helperText('PNG/SVG · حد أقصى 2MB')
                                        ->image()->disk('public')->directory('site')->maxSize(2048),
                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('removeLogoDark')
                                            ->label('حذف اللوجو الليلي')->icon('heroicon-o-trash')->color('danger')->size('sm')
                                            ->visible(fn () => (bool) SiteSetting::get('site.logo_dark'))
                                            ->requiresConfirmation()
                                            ->action(fn () => $this->clearImageSetting('site.logo_dark')),
                                    ]),
                                ])->columnSpan(1),

                                // Favicon ───────────────────────────
                                Forms\Components\Group::make([
                                    Forms\Components\Placeholder::make('favicon_current')
                                        ->label('')
                                        ->content(fn () => new HtmlString(view('filament.components.setting-image-preview', [
                                            'label' => 'الأيقونة الحالية',
                                            'url'   => SiteSetting::get('site.favicon') ? '/storage/'.ltrim(SiteSetting::get('site.favicon'),'/') : null,
                                            'bg'    => '#FFFFFF',
                                            'small' => true,
                                        ])->render())),
                                    Forms\Components\FileUpload::make('general.favicon_upload')
                                        ->label('اختر أيقونة جديدة')
                                        ->helperText('PNG مربّعة · حد أقصى 2MB')
                                        ->image()->disk('public')->directory('site')->maxSize(2048),
                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('removeFavicon')
                                            ->label('حذف الأيقونة')->icon('heroicon-o-trash')->color('danger')->size('sm')
                                            ->visible(fn () => (bool) SiteSetting::get('site.favicon'))
                                            ->requiresConfirmation()
                                            ->action(fn () => $this->clearImageSetting('site.favicon')),
                                    ]),
                                ])->columnSpan(1),
                            ])->columns(3),

                        Forms\Components\Section::make('معلومات التواصل')->schema([
                            Forms\Components\TextInput::make('general.contact_email')->label('بريد التواصل')->email(),
                            Forms\Components\TextInput::make('general.contact_phone')->label('رقم الجوال'),
                            Forms\Components\Textarea::make('general.contact_address')->label('العنوان')->rows(2)->columnSpanFull(),
                        ])->columns(2),

                        Forms\Components\Section::make('ترتيب القوائم')
                            ->description('اسحب لإعادة ترتيب عناصر القائمة الرئيسية للموقع.')
                            ->schema([
                                Forms\Components\Repeater::make('general.menu_order')
                                    ->label('عناصر القائمة')
                                    ->simple(
                                        Forms\Components\TextInput::make('slug')->required()->placeholder('services')
                                    )
                                    ->reorderable()
                                    ->reorderableWithDragAndDrop()
                                    ->addActionLabel('إضافة عنصر')
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                            ]),
                    ]),

                // ===== TAB 3: Social & Marketing =====
                Tab::make('التواصل الاجتماعي والتسويق')
                    ->icon('heroicon-o-megaphone')
                    ->schema([
                        Forms\Components\Section::make('روابط التواصل الاجتماعي')
                            ->description('تُستخدم في فوتر الموقع وصفحات الاتصال — للمسوّق لاحقاً.')
                            ->schema([
                                Forms\Components\TextInput::make('social.twitter')->label('X / Twitter')->url()->prefix('🐦')->placeholder('https://x.com/…'),
                                Forms\Components\TextInput::make('social.linkedin')->label('LinkedIn')->url()->prefix('💼')->placeholder('https://linkedin.com/…'),
                                Forms\Components\TextInput::make('social.instagram')->label('Instagram')->url()->prefix('📷')->placeholder('https://instagram.com/…'),
                                Forms\Components\TextInput::make('social.facebook')->label('Facebook')->url()->prefix('📘')->placeholder('https://facebook.com/…'),
                                Forms\Components\TextInput::make('social.youtube')->label('YouTube')->url()->prefix('▶️')->placeholder('https://youtube.com/…'),
                                Forms\Components\TextInput::make('social.tiktok')->label('TikTok')->url()->prefix('🎵')->placeholder('https://tiktok.com/…'),
                                Forms\Components\TextInput::make('social.snapchat')->label('Snapchat')->url()->prefix('👻')->placeholder('https://snapchat.com/…'),
                                Forms\Components\TextInput::make('social.whatsapp')->label('WhatsApp')->prefix('💬')->placeholder('+9665…'),
                                Forms\Components\TextInput::make('social.telegram')->label('Telegram')->prefix('✈️')->placeholder('@handle'),
                            ])->columns(2),

                        Forms\Components\Section::make('أدوات التحليل والتسويق')
                            ->description('معرّفات Pixels وأدوات التحليل — تُحقن في `<head>` تلقائياً.')
                            ->schema([
                                Forms\Components\TextInput::make('marketing.gtm_id')->label('Google Tag Manager')->placeholder('GTM-XXXXXX'),
                                Forms\Components\TextInput::make('marketing.ga4_id')->label('Google Analytics 4')->placeholder('G-XXXXXXXX'),
                                Forms\Components\TextInput::make('marketing.meta_pixel')->label('Meta Pixel ID'),
                                Forms\Components\TextInput::make('marketing.tiktok_pixel')->label('TikTok Pixel ID'),
                                Forms\Components\TextInput::make('marketing.snap_pixel')->label('Snapchat Pixel ID'),
                                Forms\Components\TextInput::make('marketing.hotjar_id')->label('Hotjar Site ID'),
                                Forms\Components\TextInput::make('marketing.tawk_id')->label('Tawk.to Widget ID'),
                            ])->columns(2)->collapsed(),

                        Forms\Components\Section::make('SEO ووصف الموقع')->schema([
                            Forms\Components\TextInput::make('marketing.seo_title')->label('عنوان SEO الافتراضي')->columnSpanFull(),
                            Forms\Components\Textarea::make('marketing.seo_description')->label('وصف SEO')->rows(3)->columnSpanFull(),
                            Forms\Components\TextInput::make('marketing.seo_keywords')->label('الكلمات المفتاحية')->columnSpanFull(),
                        ]),
                    ]),
            Tab::make('صفحة من نحن')
                ->icon('heroicon-o-photo')
                ->schema([
                    Forms\Components\Section::make('قسم الهيرو (أعلى الصفحة)')
                        ->description('العنوان الرئيسي والنص التعريفي في قمة صفحة "من نحن".')
                        ->schema([
                            Forms\Components\TextInput::make('about.hero_eyebrow')->label('العنوان الفوقي (الشريط الصغير)')->maxLength(80),
                            Forms\Components\TextInput::make('about.hero_title_line1')->label('السطر الأول من العنوان')->maxLength(120),
                            Forms\Components\TextInput::make('about.hero_title_highlight')->label('الكلمة المميّزة (بلون العلامة)')->maxLength(80),
                            Forms\Components\TextInput::make('about.hero_title_line2')->label('السطر الثاني من العنوان')->maxLength(120),
                            Forms\Components\Textarea::make('about.hero_description')->label('النص التعريفي')->rows(4)->columnSpanFull(),
                            Forms\Components\TextInput::make('about.license_number')->label('رقم الترخيص'),
                            Forms\Components\TextInput::make('about.locations')->label('المواقع')->placeholder('الرياض · لندن'),
                            Forms\Components\TextInput::make('about.years_experience')->label('سنوات الخبرة')->placeholder('+9 سنوات خبرة'),
                        ])->columns(2)->collapsible(),

                    Forms\Components\Section::make('الرؤية والرسالة')
                        ->description('البطاقتان الكبيرتان بعد الهيرو مباشرة.')
                        ->schema([
                            Forms\Components\Textarea::make('about.vision_title')->label('عنوان الرؤية')->rows(2)->helperText('اضغط Enter لسطر جديد.'),
                            Forms\Components\Textarea::make('about.vision_body')->label('نص الرؤية')->rows(3),
                            Forms\Components\Textarea::make('about.mission_title')->label('عنوان الرسالة')->rows(2)->helperText('اضغط Enter لسطر جديد.'),
                            Forms\Components\Textarea::make('about.mission_body')->label('نص الرسالة')->rows(3),
                        ])->columns(2)->collapsible()->collapsed(),

                    Forms\Components\Section::make('القيم (مبادئ لا نساوم عليها)')
                        ->schema([
                            Forms\Components\TextInput::make('about.values_title')->label('عنوان القسم'),
                            Forms\Components\Repeater::make('about.values')
                                ->label('قائمة القيم')
                                ->schema([
                                    Forms\Components\TextInput::make('icon')->label('أيقونة Solar')->placeholder('shield-check-bold-duotone'),
                                    Forms\Components\TextInput::make('title')->label('العنوان')->required(),
                                    Forms\Components\Textarea::make('desc')->label('الوصف')->rows(2)->required()->columnSpanFull(),
                                ])
                                ->columns(2)->collapsed()->reorderable()
                                ->itemLabel(fn (?array $s = []) => data_get($s, 'title') ?: 'قيمة')
                                ->addActionLabel('إضافة قيمة')
                                ->columnSpanFull(),
                        ])->collapsible()->collapsed(),

                    Forms\Components\Section::make('المزايا (لماذا رواد؟)')
                        ->schema([
                            Forms\Components\TextInput::make('about.advantages_title')->label('عنوان القسم'),
                            Forms\Components\Repeater::make('about.advantages')
                                ->label('قائمة المزايا')
                                ->schema([
                                    Forms\Components\TextInput::make('icon')->label('أيقونة Solar')->placeholder('star-bold-duotone'),
                                    Forms\Components\TextInput::make('title')->label('العنوان')->required(),
                                    Forms\Components\Textarea::make('desc')->label('الوصف')->rows(2)->required()->columnSpanFull(),
                                ])
                                ->columns(2)->collapsed()->reorderable()
                                ->itemLabel(fn (?array $s = []) => data_get($s, 'title') ?: 'ميزة')
                                ->addActionLabel('إضافة ميزة')
                                ->columnSpanFull(),
                        ])->collapsible()->collapsed(),

                    Forms\Components\Section::make('صور صفحة "من نحن"')
                        ->description('استبدل الصور التوضيحية في صفحة "من نحن" الخارجية. إذا لم ترفع صورة، تظهر الصورة الافتراضية.')
                        ->schema([
                            Forms\Components\Group::make([
                                Forms\Components\Placeholder::make('about_hero_current')
                                    ->label('')
                                    ->content(fn () => new HtmlString(view('filament.components.setting-image-preview', [
                                        'label' => 'صورة "قصتنا" الحالية',
                                        'url'   => SiteSetting::get('about.hero_image') ? '/storage/'.ltrim(SiteSetting::get('about.hero_image'),'/') : '/images/about/team-consultation.svg',
                                        'bg'    => '#F8FAFC',
                                    ])->render())),
                                Forms\Components\FileUpload::make('about.hero_image_upload')
                                    ->label('اختر صورة جديدة لقسم "قصتنا"')
                                    ->helperText('JPG/PNG/SVG · حد أقصى 4MB · تظهر بجوار عنوان "نسعى لأن نكون آيقونة رائدة"')
                                    ->image()->disk('public')->directory('about')->maxSize(4096),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('removeAboutHero')
                                        ->label('حذف الصورة (استخدم الافتراضية)')->icon('heroicon-o-trash')->color('danger')->size('sm')
                                        ->visible(fn () => (bool) SiteSetting::get('about.hero_image'))
                                        ->requiresConfirmation()
                                        ->action(fn () => $this->clearImageSetting('about.hero_image')),
                                ]),
                            ])->columnSpan(1),

                            Forms\Components\Group::make([
                                Forms\Components\Placeholder::make('about_partnership_current')
                                    ->label('')
                                    ->content(fn () => new HtmlString(view('filament.components.setting-image-preview', [
                                        'label' => 'صورة "الشراكة" الحالية',
                                        'url'   => SiteSetting::get('about.partnership_image') ? '/storage/'.ltrim(SiteSetting::get('about.partnership_image'),'/') : '/images/about/partnership.svg',
                                        'bg'    => '#F8FAFC',
                                    ])->render())),
                                Forms\Components\FileUpload::make('about.partnership_image_upload')
                                    ->label('اختر صورة جديدة لقسم "الشراكة"')
                                    ->helperText('JPG/PNG/SVG · حد أقصى 4MB · تظهر بجوار قسم "الشراكة/رؤيتنا"')
                                    ->image()->disk('public')->directory('about')->maxSize(4096),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('removeAboutPartnership')
                                        ->label('حذف الصورة (استخدم الافتراضية)')->icon('heroicon-o-trash')->color('danger')->size('sm')
                                        ->visible(fn () => (bool) SiteSetting::get('about.partnership_image'))
                                        ->requiresConfirmation()
                                        ->action(fn () => $this->clearImageSetting('about.partnership_image')),
                                ]),
                            ])->columnSpan(1),
                        ])->columns(2),
                ]),
            ])->persistTabInQueryString('tab')->columnSpanFull(),
        ])->statePath('data');
    }

    public function save()
    {
        $data = $this->form->getState();
        $user = auth()->user();

        // Save profile — avatar at top-level 'profile_avatar'
        $p = $data['profile'] ?? [];
        $user->name  = $p['name']  ?? $user->name;
        $user->email = $p['email'] ?? $user->email;

        // Only replace avatar if a NEW one was uploaded (field starts empty on mount).
        // Removal is handled by the dedicated "Remove" action button.
        $avatarValue = $data['profile_avatar'] ?? null;
        $newPath = is_array($avatarValue) ? (array_values($avatarValue)[0] ?? null) : $avatarValue;
        if (! empty($newPath) && $newPath !== $user->avatar) {
            // Clean up previous avatar file (best-effort)
            $old = $user->avatar;
            $user->avatar = $newPath;
            if ($old && Storage::disk('public')->exists($old)) {
                try { Storage::disk('public')->delete($old); } catch (\Throwable $e) {}
            }
        }

        if (! empty($p['new_password'])) $user->password = Hash::make($p['new_password']);
        $user->save();

        // Save general
        // Special handling for image upload fields (only replace when a new file was picked)
        $imageMap = [
            'logo_upload'      => 'logo',
            'logo_dark_upload' => 'logo_dark',
            'favicon_upload'   => 'favicon',
        ];

        foreach ($data['general'] ?? [] as $k => $v) {
            // Skip empty upload fields — user didn't pick a new file
            if (array_key_exists($k, $imageMap)) {
                $newImg = is_array($v) ? (array_values($v)[0] ?? null) : $v;
                if (! empty($newImg)) {
                    $targetKey = $imageMap[$k];
                    $old = SiteSetting::get("site.{$targetKey}");
                    if ($old && ! str_starts_with($old, 'http') && Storage::disk('public')->exists(ltrim($old, '/'))) {
                        try { Storage::disk('public')->delete(ltrim($old, '/')); } catch (\Throwable $e) {}
                    }
                    SiteSetting::set("site.{$targetKey}", $newImg, 'general', 'file');
                }
                continue;
            }
            $type  = $k === 'menu_order' ? 'json' : 'string';
            SiteSetting::set("site.{$k}", $v, 'general', $type);
        }
        // Save social
        foreach ($data['social'] ?? [] as $k => $v) {
            SiteSetting::set("social.{$k}", $v, 'social', 'string');
        }
        // Save marketing
        foreach ($data['marketing'] ?? [] as $k => $v) {
            SiteSetting::set("marketing.{$k}", $v, 'marketing', 'string');
        }
        // Save about-page content + images (only replace image when new file picked)
        $aboutImageMap = [
            'hero_image_upload'        => 'hero_image',
            'partnership_image_upload' => 'partnership_image',
        ];
        $arrayKeys = ['values', 'advantages'];
        foreach ($data['about'] ?? [] as $k => $v) {
            // Image upload fields — skip when empty (no new file)
            if (array_key_exists($k, $aboutImageMap)) {
                $newImg = is_array($v) ? (array_values($v)[0] ?? null) : $v;
                if (! empty($newImg)) {
                    $targetKey = $aboutImageMap[$k];
                    $old = SiteSetting::get("about.{$targetKey}");
                    if ($old && ! str_starts_with($old, 'http') && Storage::disk('public')->exists(ltrim($old, '/'))) {
                        try { Storage::disk('public')->delete(ltrim($old, '/')); } catch (\Throwable $e) {}
                    }
                    SiteSetting::set("about.{$targetKey}", $newImg, 'about', 'file');
                }
                continue;
            }
            $type = in_array($k, $arrayKeys, true) ? 'json' : 'string';
            SiteSetting::set("about.{$k}", $v, 'about', $type);
        }

        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();

        // Full reload to reset any stuck Livewire file-upload state and re-fill form
        return redirect(request()->header('Referer') ?: static::getUrl());
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('حفظ الإعدادات')
                ->submit('save'),
        ];
    }

    /**
     * Delete an image setting (logo, logo_dark, favicon) and its backing file.
     * Called from the "Remove" action next to each image field.
     */
    protected function clearImageSetting(string $settingKey): void
    {
        $current = SiteSetting::get($settingKey);
        if ($current && ! str_starts_with($current, 'http') && Storage::disk('public')->exists(ltrim($current, '/'))) {
            try { Storage::disk('public')->delete(ltrim($current, '/')); } catch (\Throwable $e) {}
        }
        SiteSetting::set($settingKey, null, 'general', 'file');
        Notification::make()->title('تم حذف الصورة')->success()->send();
        // Full reload so the placeholder rerenders with the new state
        $this->redirect(request()->header('Referer') ?: static::getUrl());
    }
}
