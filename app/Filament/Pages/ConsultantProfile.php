<?php

namespace App\Filament\Pages;

use App\Models\Consultant;
use App\Models\Specialization;
use App\Models\User;
use App\Notifications\ConsultantProfileChangesSubmitted;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ConsultantProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'ملفّي الشخصي';
    protected static ?string $navigationGroup = 'نظرة عامة';
    protected static ?int    $navigationSort  = 10;
    protected static string  $view            = 'filament.pages.consultant-profile';
    protected static ?string $title           = 'ملفّي — المستشار';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'consultant';
    }

    public function mount(): void
    {
        $c = auth()->user()->consultant;
        if (! $c) return;

        $this->form->fill([
            // Avatar starts empty — current avatar is shown via image tag (no Filepond spin).
            'avatar_path'  => null,
            // Free fields
            'full_name_ar' => $c->full_name_ar,
            'full_name_en' => $c->full_name_en,
            'phone'        => $c->phone,
            'city'         => $c->city,
            'country'      => $c->country,
            'birth_date'   => $c->birth_date?->toDateString(),
            'languages'    => $c->languages ?? [],
            // Sensitive fields (edits queue for approval)
            'professional_title'   => $c->professional_title,
            'bio_ar'               => $c->bio_ar,
            'bio_en'               => $c->bio_en,
            'specialization_id'    => $c->specialization_id,
            'years_experience'     => $c->years_experience,
            'hourly_rate'          => $c->hourly_rate,
            'session_duration_min' => $c->session_duration_min,
            'linkedin_url'         => $c->linkedin_url,
            'website_url'          => $c->website_url,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('البيانات الأساسية — يمكنك تعديلها مباشرة')
                ->description('التغييرات هنا تُطبَّق فور الحفظ.')
                ->icon('heroicon-o-user-circle')
                ->schema([
                    // ── Avatar block (mirrors admin Settings pattern) ──
                    Forms\Components\Group::make([
                        Forms\Components\Placeholder::make('current_avatar_preview')
                            ->label('')
                            ->content(function () {
                                $c    = auth()->user()->consultant;
                                $url  = $c?->avatar_path ? asset('storage/'.$c->avatar_path) : null;
                                $name = $c?->full_name_ar ?: auth()->user()->name;
                                $initials = collect(explode(' ', (string) $name))
                                    ->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
                                return new HtmlString(view('filament.components.avatar-preview', [
                                    'url' => $url, 'initials' => $initials, 'name' => $name,
                                ])->render());
                            }),
                        Forms\Components\FileUpload::make('avatar_path')
                            ->label('اختر صورة جديدة (اختياري)')
                            ->helperText('PNG / JPG · حد أقصى 2MB')
                            ->image()
                            ->disk('public')
                            ->directory('consultants/avatars')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->imagePreviewHeight('80'),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('removeAvatar')
                                ->label('حذف الصورة الحالية')
                                ->icon('heroicon-o-trash')
                                ->color('danger')
                                ->size('sm')
                                ->visible(fn () => (bool) auth()->user()->consultant?->avatar_path)
                                ->requiresConfirmation()
                                ->action(function () {
                                    $c = auth()->user()->consultant;
                                    if ($c && $c->avatar_path && Storage::disk('public')->exists($c->avatar_path)) {
                                        Storage::disk('public')->delete($c->avatar_path);
                                    }
                                    if ($c) {
                                        $c->avatar_path = null;
                                        $c->save();
                                    }
                                    Notification::make()->title('تم حذف الصورة')->success()->send();
                                    return redirect(request()->header('Referer') ?: static::getUrl());
                                }),
                        ]),
                    ])->columnSpan(1),

                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('full_name_ar')->label('الاسم (عربي)')->required(),
                        Forms\Components\TextInput::make('full_name_en')->label('Name (English)'),
                        Forms\Components\TextInput::make('phone')->label('الجوال'),
                        Forms\Components\TextInput::make('city')->label('المدينة'),
                        Forms\Components\TextInput::make('country')->label('الدولة')->default('SA'),
                        Forms\Components\DatePicker::make('birth_date')->label('تاريخ الميلاد'),
                        Forms\Components\TagsInput::make('languages')->label('اللغات')->placeholder('ar, en'),
                    ])->columnSpan(1),
                ])->columns(2),

            Section::make('البيانات المهنية — تتطلب موافقة الأدمن')
                ->description('أي تعديل هنا يُرسَل للأدمن للاعتماد قبل تطبيقه على ملفّك العام.')
                ->icon('heroicon-o-shield-check')
                ->schema([
                    Forms\Components\TextInput::make('professional_title')->label('المسمى المهني'),
                    Forms\Components\Select::make('specialization_id')->label('التخصص الأساسي')
                        ->options(Specialization::pluck('name_ar', 'id'))->searchable(),
                    Forms\Components\Textarea::make('bio_ar')->label('نبذة (عربي)')->rows(4)->columnSpanFull(),
                    Forms\Components\Textarea::make('bio_en')->label('Bio (English)')->rows(4)->columnSpanFull(),
                    Forms\Components\TextInput::make('years_experience')->label('سنوات الخبرة')->numeric(),
                    Forms\Components\TextInput::make('hourly_rate')->label('السعر بالساعة (ر.س)')->numeric(),
                    Forms\Components\Select::make('session_duration_min')->label('مدة الجلسة الافتراضية')
                        ->options([30=>'30 د',45=>'45 د',60=>'60 د',90=>'90 د',120=>'120 د']),
                    Forms\Components\TextInput::make('linkedin_url')->label('LinkedIn')->url(),
                    Forms\Components\TextInput::make('website_url')->label('الموقع الشخصي')->url(),
                ])->columns(2),
        ])->statePath('data');
    }

    public function save()
    {
        $c = auth()->user()->consultant;
        abort_unless($c, 404);

        $data = $this->form->getState();

        // Avatar is handled specially: only replace when a NEW file was uploaded.
        $avatarValue = $data['avatar_path'] ?? null;
        unset($data['avatar_path']);
        $newAvatar = is_array($avatarValue) ? (array_values($avatarValue)[0] ?? null) : $avatarValue;
        if (! empty($newAvatar) && $newAvatar !== $c->avatar_path) {
            $old = $c->avatar_path;
            $c->avatar_path = $newAvatar;
            if ($old && Storage::disk('public')->exists($old)) {
                try { Storage::disk('public')->delete($old); } catch (\Throwable $e) {}
            }
            $c->save();
        }

        $result = $c->submitProfileUpdate($data);

        // Notify admins when sensitive fields were queued.
        if (! empty($result['pending'])) {
            foreach (User::where('role', 'admin')->get() as $admin) {
                try {
                    $admin->notify(new ConsultantProfileChangesSubmitted($c, $result['pending']));
                    usleep(1_100_000); // Mailtrap 1/sec sandbox limit
                } catch (\Throwable $e) {
                    Log::warning('[Consultant changes notify] failed: ' . $e->getMessage());
                }
            }
        }

        $lines = [];
        if (! empty($result['applied'])) $lines[] = 'تم تطبيق ' . count($result['applied']) . ' حقلاً مباشرة.';
        if (! empty($result['pending'])) $lines[] = count($result['pending']) . ' حقلاً حسّاساً بانتظار موافقة الأدمن.';

        Notification::make()
            ->title('تم إرسال التحديث')
            ->body(implode(' · ', $lines) ?: 'لا تغييرات جديدة.')
            ->success()->send();

        return redirect(request()->header('Referer') ?: static::getUrl());
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')->label('حفظ / إرسال للاعتماد')->submit('save'),
        ];
    }

    public function getViewData(): array
    {
        return ['pending' => auth()->user()->consultant?->pending_changes ?? []];
    }
}
