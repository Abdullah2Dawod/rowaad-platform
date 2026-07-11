@php $unsubscribeUrl = $unsubscribeUrl ?? null; @endphp
<x-rowaad-email :unsubscribeUrl="$unsubscribeUrl">
    <span class="eyebrow">تأكيد الاشتراك</span>

    <h1>مرحباً بك في رواد بلا حدود</h1>

    <p>
        شكراً لاشتراكك في نشرتنا الدورية. للتأكيد وبدء استلام أحدث الاستشارات والفرص الاستثمارية،
        اضغط الزر أدناه:
    </p>

    <div class="btn-wrap">
        <a href="{{ $confirmUrl }}" class="btn">تأكيد الاشتراك</a>
    </div>

    <div class="panel">
        <p class="panel-title">ماذا ستستقبل؟</p>
        <p style="margin: 0; color: #475569; font-size: 13.5px; line-height: 1.85;">
            • تحليلات اقتصادية أسبوعية من خبراء رواد<br>
            • فرص استثمارية مدروسة قبل الجميع<br>
            • دراسات جدوى جديدة ومحدّثة<br>
            • دعوات لجلسات استشارية مجانية
        </p>
    </div>

    <hr class="divider">

    <p style="color: #64748B; font-size: 12.5px; text-align: center;">
        إذا لم تشترك من قبل، تجاهل هذه الرسالة — لن نُرسل شيئاً ما لم تؤكّد.
    </p>
</x-rowaad-email>
