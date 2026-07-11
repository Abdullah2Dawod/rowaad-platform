<x-rowaad-email>
    <span class="eyebrow">طلب مستشار جديد</span>

    <h1>وصلك طلب مستشار جديد للمراجعة</h1>

    <p>
        قدّم <strong>{{ $consultantName }}</strong> طلباً للانضمام كمستشار في المنصة.
        راجع التفاصيل واتخذ قرار الاعتماد من لوحة التحكم.
    </p>

    <div class="panel">
        <p class="panel-title">بيانات الطلب</p>
        <div class="kv-row"><span class="kv-key">الاسم:</span> <span class="kv-val">{{ $consultantName }}</span></div>
        <div class="kv-row"><span class="kv-key">البريد:</span> <span class="kv-val" dir="ltr">{{ $c->user?->email }}</span></div>
        @if($c->professional_title)
        <div class="kv-row"><span class="kv-key">المسمى:</span> <span class="kv-val">{{ $c->professional_title }}</span></div>
        @endif
        @if($c->city)
        <div class="kv-row"><span class="kv-key">المدينة:</span> <span class="kv-val">{{ $c->city }}</span></div>
        @endif
        @if($c->years_experience)
        <div class="kv-row"><span class="kv-key">سنوات الخبرة:</span> <span class="kv-val">{{ $c->years_experience }}</span></div>
        @endif
    </div>

    <div class="btn-wrap">
        <a href="{{ $url }}" class="btn">مراجعة الطلب</a>
    </div>
</x-rowaad-email>
