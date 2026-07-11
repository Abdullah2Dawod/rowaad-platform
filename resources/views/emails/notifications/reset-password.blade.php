<x-rowaad-email>
    <span class="eyebrow" style="color: #D97706; background: rgba(245, 158, 11, 0.10); border-color: rgba(245, 158, 11, 0.30);">إعادة تعيين كلمة المرور</span>

    <h1>طلب إعادة تعيين كلمة المرور</h1>

    <p>
        @if($name) مرحباً <strong>{{ $name }}</strong>،<br> @endif
        تلقّينا طلباً لإعادة تعيين كلمة المرور لحسابك في منصة رواد بلا حدود.
        اضغط الزرّ أدناه لإنشاء كلمة مرور جديدة.
    </p>

    <div class="btn-wrap">
        <a href="{{ $url }}" class="btn">إعادة تعيين كلمة المرور</a>
    </div>

    <div class="panel" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.06), rgba(217, 119, 6, 0.03)); border-color: rgba(245, 158, 11, 0.2);">
        <p class="panel-title" style="color: #D97706;">مهمّ — لأمانك</p>
        <p style="margin: 0; font-size: 13.5px; color: #475569; line-height: 1.85;">
            • هذا الرابط صالح لمدة <strong>{{ $expireMinutes ?? 60 }} دقيقة</strong> فقط<br>
            • إن لم تطلب إعادة تعيين — تجاهل الرسالة وكلمة مرورك لن تتغيّر<br>
            • لا تُشارك هذا الرابط مع أحد
        </p>
    </div>

    <hr class="divider">

    <p style="color: #94A3B8; font-size: 11px; text-align: center; line-height: 1.8;">
        إن لم يعمل الزرّ، انسخ الرابط التالي والصقه في المتصفح:<br>
        <a href="{{ $url }}" style="color: #3DAFB9; word-break: break-all; font-size: 10.5px;" dir="ltr">{{ $url }}</a>
    </p>
</x-rowaad-email>
