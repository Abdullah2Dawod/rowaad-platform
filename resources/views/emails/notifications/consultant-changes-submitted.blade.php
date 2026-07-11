<x-rowaad-email>
    <span class="eyebrow" style="color: #D97706; background: rgba(245, 158, 11, 0.10); border-color: rgba(245, 158, 11, 0.30);">تعديل مستشار</span>

    <h1>وصلك طلب تعديل ملف مستشار</h1>

    <p>
        قدّم المستشار <strong>{{ $consultantName }}</strong> طلب تعديل على بيانات حسّاسة في ملفه الشخصي،
        وهي بحاجة لمراجعتك واعتمادها قبل أن تظهر للعملاء.
    </p>

    @if(!empty($fields))
    <div class="panel">
        <p class="panel-title">الحقول المُعدَّلة ({{ count($fields) }})</p>
        <p style="margin: 0; font-size: 13.5px; color: #475569; line-height: 1.9;">
            @php
                $labels = [
                    'professional_title' => 'المسمى المهني',
                    'bio_ar' => 'النبذة (عربي)',
                    'bio_en' => 'النبذة (إنجليزي)',
                    'specialization_id' => 'التخصص الأساسي',
                    'secondary_specializations' => 'التخصصات الفرعية',
                    'years_experience' => 'سنوات الخبرة',
                    'hourly_rate' => 'السعر بالساعة',
                    'session_duration_min' => 'مدة الجلسة',
                    'services' => 'الخدمات',
                    'certificates' => 'الشهادات',
                    'linkedin_url' => 'LinkedIn',
                    'website_url' => 'الموقع الشخصي',
                ];
            @endphp
            @foreach($fields as $key => $val)
                • {{ $labels[$key] ?? $key }}<br>
            @endforeach
        </p>
    </div>
    @endif

    <div class="btn-wrap">
        <a href="{{ $url }}" class="btn">مراجعة التعديلات</a>
    </div>

    <hr class="divider">

    <p style="color: #64748B; font-size: 12px; text-align: center;">
        بعد المراجعة يمكنك اعتماد التعديلات أو رفضها من لوحة إدارة المستشارين.
    </p>
</x-rowaad-email>
