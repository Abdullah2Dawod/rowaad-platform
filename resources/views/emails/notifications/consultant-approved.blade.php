<x-rowaad-email>
    <span class="eyebrow" style="color: #059669; background: rgba(16, 185, 129, 0.10); border-color: rgba(16, 185, 129, 0.30);">اعتماد رسمي</span>

    <h1>🎉 مرحباً بك مستشاراً في رواد</h1>

    <p>
        مرحباً <strong>{{ $consultantName }}</strong>،<br>
        يسرّنا إبلاغك بأنه تم اعتماد طلب انضمامك رسمياً كمستشار معتمد في منصة رواد بلا حدود.
        نتطلع لرحلة مثمرة معك.
    </p>

    <div class="highlight">
        <p class="highlight-label">بيانات الدخول إلى لوحة التحكم</p>
        <div style="margin-top: 8px;">
            <div style="margin-bottom: 8px;">
                <div style="font-size: 10.5px; color: #6BC8D2; letter-spacing: 0.15em; margin-bottom: 4px;">البريد الإلكتروني</div>
                <code>{{ $email }}</code>
            </div>
            <div>
                <div style="font-size: 10.5px; color: #6BC8D2; letter-spacing: 0.15em; margin-bottom: 4px;">كلمة المرور المؤقّتة</div>
                <code>{{ $password }}</code>
            </div>
        </div>
    </div>

    <div class="panel" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.06), rgba(217, 119, 6, 0.03)); border-color: rgba(245, 158, 11, 0.2);">
        <p class="panel-title" style="color: #D97706;">مهم جداً</p>
        <p style="margin: 0; color: #475569; font-size: 13.5px; line-height: 1.85;">
            يُرجى تغيير كلمة المرور فور تسجيل دخولك الأول من إعدادات الحساب لأسباب أمنية.
        </p>
    </div>

    <div class="btn-wrap">
        <a href="{{ url('/login') }}" class="btn">تسجيل الدخول إلى لوحة التحكم</a>
    </div>

    <div class="panel">
        <p class="panel-title">ماذا تستطيع أن تفعل الآن؟</p>
        <p style="margin: 0; color: #475569; font-size: 13.5px; line-height: 1.85;">
            • استقبال حجوزات الاستشارات من العملاء<br>
            • تأكيد الحجوزات وإرسال روابط الجلسات<br>
            • متابعة أرباحك في محفظتك الخاصة<br>
            • تحديث ملفّك الشخصي وخدماتك المُقدَّمة
        </p>
    </div>

    <hr class="divider">

    <p style="color: #64748B; font-size: 12px; text-align: center;">
        فريق رواد يُرحّب بك — نتمنى لك النجاح والتوفيق.
    </p>
</x-rowaad-email>
