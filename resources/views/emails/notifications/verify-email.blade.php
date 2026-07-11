<x-rowaad-email>
    <span class="eyebrow">تفعيل البريد الإلكتروني</span>

    <h1>خطوة أخيرة — وثّق بريدك</h1>

    <p>
        @if($name) مرحباً <strong>{{ $name }}</strong>،<br> @endif
        شكراً لانضمامك إلى منصة رواد بلا حدود.
        قبل أن تتمكّن من حجز أي استشارة، نحتاج التحقّق من صحّة بريدك الإلكتروني.
    </p>

    <div class="btn-wrap">
        <a href="{{ $url }}" class="btn">تأكيد البريد الإلكتروني</a>
    </div>

    <div class="panel">
        <p class="panel-title">لماذا التوثيق؟</p>
        <p style="margin: 0; font-size: 13.5px; color: #475569; line-height: 1.85;">
            • ضمان وصول إشعارات الحجوزات إليك<br>
            • حماية حسابك من الاستخدام غير المصرّح به<br>
            • تلقّي روابط الجلسات وتأكيدات الدفع
        </p>
    </div>

    <hr class="divider">

    <p style="color: #64748B; font-size: 12px; text-align: center; line-height: 1.8;">
        إذا لم تُنشئ هذا الحساب — تجاهل الرسالة، لن يُنشأ حسابك دون التأكيد.
    </p>

    <p style="color: #94A3B8; font-size: 11px; text-align: center; line-height: 1.8; margin-top: 16px;">
        إن لم يعمل الزرّ، انسخ الرابط التالي والصقه في المتصفح:<br>
        <a href="{{ $url }}" style="color: #3DAFB9; word-break: break-all; font-size: 10.5px;" dir="ltr">{{ $url }}</a>
    </p>
</x-rowaad-email>
