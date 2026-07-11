@php
    $isPaid     = $r->status === 'paid';
    $isApproved = $r->status === 'approved';
    $isRejected = $r->status === 'rejected';
@endphp

<x-rowaad-email>
    @if($isPaid)
        <span class="eyebrow" style="color:#059669;background:rgba(16,185,129,.10);border-color:rgba(16,185,129,.30);">تحويل ناجح</span>
        <h1>💸 تم تحويل رصيدك بنجاح</h1>
    @elseif($isApproved)
        <span class="eyebrow">اعتماد الطلب</span>
        <h1>✓ تم اعتماد طلب السحب</h1>
    @elseif($isRejected)
        <span class="eyebrow" style="color:#DC2626;background:rgba(239,68,68,.10);border-color:rgba(239,68,68,.30);">مراجعة الطلب</span>
        <h1>تحديث بشأن طلب السحب</h1>
    @endif

    <p>
        مرحباً <strong>{{ $name }}</strong>،<br>
        @if($isPaid)
            يسرّنا إبلاغك بأنه تم تحويل رصيدك إلى حسابك البنكي المسجّل.
        @elseif($isApproved)
            تم اعتماد طلب سحبك من قبل الإدارة، وسيُحوَّل المبلغ خلال 3-5 أيام عمل.
        @elseif($isRejected)
            نأسف لإبلاغك بأن طلب السحب لم يُعتمد في هذه المرة، وقد أُعيد المبلغ إلى رصيدك المتاح.
        @endif
    </p>

    <div class="panel">
        <p class="panel-title">تفاصيل الطلب</p>
        <div class="kv-row"><span class="kv-key">المرجع:</span> <span class="kv-val">{{ $r->reference }}</span></div>
        <div class="kv-row"><span class="kv-key">المبلغ:</span> <span class="kv-val">{{ number_format($r->amount, 2) }} ر.س</span></div>
        <div class="kv-row"><span class="kv-key">البنك:</span> <span class="kv-val">{{ $r->bank_name }}</span></div>
        <div class="kv-row"><span class="kv-key">IBAN:</span> <span class="kv-val" dir="ltr">{{ $r->iban }}</span></div>
        @if($isPaid && $r->payment_reference)
            <div class="kv-row"><span class="kv-key">مرجع التحويل:</span> <span class="kv-val" dir="ltr">{{ $r->payment_reference }}</span></div>
            <div class="kv-row"><span class="kv-key">تاريخ التحويل:</span> <span class="kv-val">{{ $r->paid_at?->format('Y-m-d H:i') }}</span></div>
        @endif
    </div>

    @if($isRejected && $r->admin_notes)
    <div class="panel" style="background:linear-gradient(135deg,rgba(239,68,68,.06),rgba(220,38,38,.03));border-color:rgba(239,68,68,.2);">
        <p class="panel-title" style="color:#DC2626;">سبب عدم الاعتماد</p>
        <p style="margin:0;color:#475569;font-size:13.5px;line-height:1.85;">{{ $r->admin_notes }}</p>
    </div>
    @endif

    <div class="btn-wrap">
        <a href="{{ url('/admin/wallet') }}" class="btn">فتح محفظتي</a>
    </div>

    <hr class="divider">

    <p style="color:#64748B;font-size:12px;text-align:center;">
        @if($isPaid) شكراً لثقتك بمنصة رواد.
        @elseif($isRejected) يمكنك تقديم طلب سحب جديد في أي وقت.
        @else سيصلك إشعار آخر فور اكتمال التحويل.
        @endif
    </p>
</x-rowaad-email>
