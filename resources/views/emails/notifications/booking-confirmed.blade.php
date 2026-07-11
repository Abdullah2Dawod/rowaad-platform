<x-rowaad-email>
    <span class="eyebrow">تأكيد الحجز</span>

    <h1>تم تأكيد استشارتك ✓</h1>

    <p>
        مرحباً <strong>{{ $notifiableName }}</strong>،<br>
        يسرّنا إبلاغك بأن المستشار <strong>{{ $consultantName }}</strong> قد أكّد حجز استشارتك.
        سنراك في الموعد المحدّد.
    </p>

    <div class="panel">
        <p class="panel-title">تفاصيل الجلسة</p>
        <div class="kv-row"><span class="kv-key">المرجع:</span> <span class="kv-val">{{ $b->reference }}</span></div>
        <div class="kv-row"><span class="kv-key">المستشار:</span> <span class="kv-val">{{ $consultantName }}</span></div>
        <div class="kv-row"><span class="kv-key">التاريخ:</span> <span class="kv-val">{{ $b->preferred_date?->format('Y-m-d') }}</span></div>
        <div class="kv-row"><span class="kv-key">الوقت:</span> <span class="kv-val">{{ $b->preferred_time }}</span></div>
        <div class="kv-row"><span class="kv-key">المدة:</span> <span class="kv-val">{{ $b->duration_min }} دقيقة</span></div>
        @if($b->service_title)
        <div class="kv-row"><span class="kv-key">الخدمة:</span> <span class="kv-val">{{ $b->service_title }}</span></div>
        @endif
    </div>

    <div class="highlight">
        <p class="highlight-label">رابط الجلسة</p>
        <p style="margin: 0 0 12px; color: #E2E8F0; font-size: 13px; line-height: 1.7;">
            افتح الرابط في الموعد المحدّد للانضمام مباشرة.
        </p>
        <a href="{{ $b->meeting_url }}" style="color: #6BC8D2 !important; text-decoration: none; font-size: 13px; font-weight: 600; word-break: break-all;" dir="ltr">
            {{ $b->meeting_url }}
        </a>
    </div>

    <div class="btn-wrap">
        <a href="{{ $b->meeting_url }}" class="btn">الانضمام إلى الجلسة</a>
    </div>

    <div class="panel" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.06), rgba(217, 119, 6, 0.03)); border-color: rgba(245, 158, 11, 0.2);">
        <p class="panel-title" style="color: #D97706;">نصائح للجلسة</p>
        <p style="margin: 0; color: #475569; font-size: 13px; line-height: 1.85;">
            • احرص على الاتصال قبل الموعد بـ 5 دقائق للتأكد من الصوت والاتصال<br>
            • جهّز أسئلتك مسبقاً للاستفادة القصوى من الوقت<br>
            • اختر مكاناً هادئاً للجلسة
        </p>
    </div>

    <hr class="divider">

    <p style="color: #64748B; font-size: 12px; text-align: center;">
        نتمنى لك جلسة موفّقة ونتائج تُثري رحلتك الاستشارية.
    </p>
</x-rowaad-email>
