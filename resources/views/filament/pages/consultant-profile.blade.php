@php
    $labels = [
        'professional_title'        => 'المسمى المهني',
        'bio_ar'                    => 'النبذة (عربي)',
        'bio_en'                    => 'النبذة (إنجليزي)',
        'specialization_id'         => 'التخصص الأساسي',
        'secondary_specializations' => 'التخصصات الفرعية',
        'years_experience'          => 'سنوات الخبرة',
        'hourly_rate'               => 'السعر بالساعة',
        'session_duration_min'      => 'مدة الجلسة (د)',
        'services'                  => 'الخدمات المُقدَّمة',
        'certificates'              => 'الشهادات',
        'linkedin_url'              => 'LinkedIn',
        'website_url'               => 'الموقع الإلكتروني',
    ];
@endphp

<x-filament-panels::page>
    @if(!empty($pending))
        <div style="
            margin-bottom: 20px;
            padding: 16px 18px;
            background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(217,119,6,0.03));
            border: 1px solid rgba(245,158,11,0.3);
            border-radius: 14px;
            display: flex; gap: 14px; align-items: flex-start;
        ">
            <div style="
                flex-shrink:0; width:42px; height:42px; border-radius:9999px;
                display:flex; align-items:center; justify-content:center;
                background: linear-gradient(135deg,#F59E0B,#D97706); color:#fff;
                box-shadow: 0 4px 12px -2px rgba(245,158,11,0.4);
            ">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div style="flex:1; min-width:0;">
                <div style="font-size:15px; font-weight:900; color:#92400E;">
                    {{ count($pending) }} تعديل قيد المراجعة من قِبل الأدمن
                </div>
                <div style="font-size:12.5px; color:#78350F; margin-top:4px; line-height:1.7;">
                    ستُطبَّق هذه التعديلات على ملفّك العام فور اعتمادها. يمكنك تحديثها بإرسال تعديل جديد.
                </div>
                <div style="margin-top:10px; display:flex; flex-wrap:wrap; gap:6px;">
                    @foreach($pending as $key => $val)
                        <span style="
                            display:inline-flex; align-items:center; gap:5px;
                            padding:4px 10px;
                            background: rgba(245,158,11,0.15);
                            border: 1px solid rgba(217,119,6,0.3);
                            border-radius: 999px;
                            font-size: 11.5px; font-weight: 700; color: #92400E;
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $labels[$key] ?? $key }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
            <x-filament::button type="submit" size="lg" icon="heroicon-o-check">
                حفظ / إرسال للاعتماد
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
