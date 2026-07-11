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
