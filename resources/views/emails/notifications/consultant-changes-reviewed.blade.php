@php
    $labels = [
        'professional_title' => 'المسمى المهني',
        'bio_ar' => 'النبذة (عربي)',
        'bio_en' => 'النبذة (English)',
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
    $approved = $decision === 'approved';
@endphp

<x-rowaad-email>
    @if($approved)
        <span class="eyebrow" style="color:#059669;background:rgba(16,185,129,.10);border-color:rgba(16,185,129,.30);">اعتماد ناجح</span>
        <h1>تم اعتماد تعديلات ملفّك ✓</h1>
    @else
        <span class="eyebrow" style="color:#DC2626;background:rgba(239,68,68,.10);border-color:rgba(239,68,68,.30);">مراجعة الطلب</span>
        <h1>تحديث بشأن تعديلات ملفّك</h1>
    @endif

    <p>
        مرحباً <strong>{{ $consultantName }}</strong>،<br>
        @if($approved)
            راجع الأدمن التعديلات التي طلبتها على ملفّك الشخصي، و<strong>تم اعتمادها</strong>.
            التعديلات مطبَّقة الآن على ملفّك العام ومرئية للعملاء.
        @else
            راجع الأدمن التعديلات التي طلبتها على ملفّك الشخصي، وللأسف <strong>لم يتم اعتمادها</strong> هذه المرة.
        @endif
    </p>

    @if(!empty($fields))
    <div class="panel">
        <p class="panel-title">الحقول {{ $approved ? 'المُعتمَدة' : 'التي لم تُعتمد' }} ({{ count($fields) }})</p>
        <p style="margin:0;font-size:13.5px;color:#475569;line-height:1.9;">
            @foreach($fields as $key => $val)
                • {{ $labels[$key] ?? $key }}<br>
            @endforeach
        </p>
    </div>
    @endif

    @if(! $approved && $reason)
    <div class="panel" style="background:linear-gradient(135deg,rgba(239,68,68,.06),rgba(220,38,38,.03));border-color:rgba(239,68,68,.2);">
        <p class="panel-title" style="color:#DC2626;">سبب عدم الاعتماد</p>
        <p style="margin:0;color:#475569;font-size:13.5px;line-height:1.85;">{{ $reason }}</p>
    </div>
    @endif

    <div class="btn-wrap">
        <a href="{{ $url }}" class="btn">{{ $approved ? 'عرض ملفّي' : 'تحديث ملفّي' }}</a>
    </div>

    <hr class="divider">

    <p style="color:#64748B;font-size:12px;text-align:center;">
        @if($approved)
            شكراً لالتزامك بتحديث بياناتك — نتمنى لك مزيداً من النجاح.
        @else
            يمكنك تحديث البيانات وإعادة إرسالها للاعتماد في أي وقت.
        @endif
    </p>
</x-rowaad-email>
