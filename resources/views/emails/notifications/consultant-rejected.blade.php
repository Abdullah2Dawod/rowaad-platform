<x-rowaad-email>
    <span class="eyebrow" style="color: #DC2626; background: rgba(239, 68, 68, 0.10); border-color: rgba(239, 68, 68, 0.30);">مراجعة الطلب</span>

    <h1>تحديث بشأن طلب انضمامك</h1>

    <p>
        مرحباً <strong>{{ $consultantName }}</strong>،<br>
        شكراً لاهتمامك بالانضمام إلى منصة رواد بلا حدود. بعد مراجعة طلبك بعناية، نأسف لإبلاغك أن الطلب
        لم يُعتمد في هذه المرحلة.
    </p>

    @if($reason)
    <div class="panel">
        <p class="panel-title">سبب عدم الاعتماد</p>
        <p style="margin: 0; color: #475569; font-size: 13.5px; line-height: 1.85;">
            {{ $reason }}
        </p>
    </div>
    @endif

    <div class="panel" style="background: linear-gradient(135deg, rgba(61, 175, 185, 0.06), rgba(45, 75, 126, 0.03));">
        <p class="panel-title">هل يمكنك إعادة التقديم؟</p>
        <p style="margin: 0; color: #475569; font-size: 13.5px; line-height: 1.85;">
            نعم — بعد معالجة النقاط المذكورة أعلاه، نرحّب بك لإعادة تقديم طلبك في أي وقت.
            فريقنا سيراجع الطلب المُحدَّث بعناية.
        </p>
    </div>

    <div class="btn-wrap">
        <a href="{{ url('/become-a-consultant') }}" class="btn">تقديم طلب جديد</a>
    </div>

    <hr class="divider">

    <p style="color: #64748B; font-size: 12px; text-align: center;">
        شكراً على تفهّمك — للاستفسارات:
        <a href="mailto:info@rowaad.org" style="color: #3DAFB9; font-weight: 600;">info@rowaad.org</a>
    </p>
</x-rowaad-email>
