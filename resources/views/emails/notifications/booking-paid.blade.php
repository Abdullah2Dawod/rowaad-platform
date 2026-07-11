<x-rowaad-email>
    <span class="eyebrow">حجز جديد · بانتظار التأكيد</span>

    <h1>@if($isConsultant)وصلك حجز جديد @else حجز جديد مدفوع @endif</h1>

    <p>
        @if($isConsultant)
            مرحباً <strong>{{ $consultantName }}</strong>،<br>
            وصلك حجز جديد مدفوع بانتظار تأكيدك. راجع التفاصيل أدناه ثم أكّد الحجز مع إضافة رابط الجلسة من لوحة التحكم.
        @else
            تم استلام حجز جديد ودفعه بنجاح في المنصة. راجع التفاصيل في لوحة الإدارة.
        @endif
    </p>

    <div class="panel">
        <p class="panel-title">تفاصيل الحجز</p>
        <div class="kv-row"><span class="kv-key">المرجع:</span> <span class="kv-val">{{ $b->reference }}</span></div>
        <div class="kv-row"><span class="kv-key">العميل:</span> <span class="kv-val">{{ $b->user?->name }}</span></div>
        <div class="kv-row"><span class="kv-key">البريد:</span> <span class="kv-val" dir="ltr">{{ $b->user?->email }}</span></div>
        <div class="kv-row"><span class="kv-key">التاريخ:</span> <span class="kv-val">{{ $b->preferred_date?->format('Y-m-d') }}</span></div>
        <div class="kv-row"><span class="kv-key">الوقت:</span> <span class="kv-val">{{ $b->preferred_time }}</span></div>
        <div class="kv-row"><span class="kv-key">المدة:</span> <span class="kv-val">{{ $b->duration_min }} دقيقة</span></div>
        @if($b->service_title)
        <div class="kv-row"><span class="kv-key">الخدمة:</span> <span class="kv-val">{{ $b->service_title }}</span></div>
        @endif
    </div>

    @if($b->notes)
    <div class="panel">
        <p class="panel-title">ملاحظات العميل</p>
        <p style="margin: 0; font-size: 13.5px; color: #475569; line-height: 1.85;">{{ $b->notes }}</p>
    </div>
    @endif

    <div class="highlight">
        <p class="highlight-label">المبلغ المدفوع</p>
        <div style="font-size: 22px; font-weight: 800; color: #FFFFFF;">
            {{ number_format($b->amount, 0) }} <span style="font-size: 13px; font-weight: 400; color: #6BC8D2;">ر.س</span>
        </div>
    </div>

    <div class="btn-wrap">
        <a href="{{ $url }}" class="btn">فتح الحجز في لوحة التحكم</a>
    </div>

    <hr class="divider">

    <p style="color: #64748B; font-size: 12px; text-align: center;">
        @if($isConsultant)
            يُرجى تأكيد الحجز في أقرب وقت. سيصل رابط الجلسة للعميل تلقائياً بعد التأكيد.
        @else
            بانتظار تأكيد المستشار وإرسال رابط الجلسة للعميل.
        @endif
    </p>
</x-rowaad-email>
