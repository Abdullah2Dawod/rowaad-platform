<x-rowaad-email>
    <span class="eyebrow">طلب دراسة جدوى</span>

    <h1>وصل طلب دراسة جدوى جديد</h1>

    <p>
        قدّم <strong>{{ $req->contact_name }}</strong> طلباً لدراسة جدوى مخصّصة. راجع التفاصيل في لوحة التحكم.
    </p>

    <div class="panel">
        <p class="panel-title">تفاصيل الطلب</p>
        <div class="kv-row"><span class="kv-key">المرجع:</span> <span class="kv-val">{{ $req->reference }}</span></div>
        <div class="kv-row"><span class="kv-key">المشروع:</span> <span class="kv-val">{{ $req->project_title }}</span></div>
        <div class="kv-row"><span class="kv-key">القطاع:</span> <span class="kv-val">{{ $req->sector }}</span></div>
        @if($req->city)
        <div class="kv-row"><span class="kv-key">المدينة:</span> <span class="kv-val">{{ $req->city }}</span></div>
        @endif
        <div class="kv-row"><span class="kv-key">الاستعجال:</span> <span class="kv-val">
            @switch($req->urgency)
                @case('urgent') عاجل @break
                @case('flexible') مرن @break
                @default طبيعي
            @endswitch
        </span></div>
        @if($req->estimated_budget)
        <div class="kv-row"><span class="kv-key">الميزانية المُقدَّرة:</span> <span class="kv-val">{{ number_format($req->estimated_budget, 0) }} ر.س</span></div>
        @endif
    </div>

    <div class="panel">
        <p class="panel-title">بيانات التواصل</p>
        <div class="kv-row"><span class="kv-key">الاسم:</span> <span class="kv-val">{{ $req->contact_name }}</span></div>
        <div class="kv-row"><span class="kv-key">الإيميل:</span> <span class="kv-val" dir="ltr">{{ $req->contact_email }}</span></div>
        <div class="kv-row"><span class="kv-key">الجوال:</span> <span class="kv-val" dir="ltr">{{ $req->contact_phone }}</span></div>
        @if($req->company_name)
        <div class="kv-row"><span class="kv-key">الشركة:</span> <span class="kv-val">{{ $req->company_name }}</span></div>
        @endif
    </div>

    <div class="btn-wrap">
        <a href="{{ $adminUrl }}" class="btn">فتح الطلب في لوحة التحكم</a>
    </div>
</x-rowaad-email>
