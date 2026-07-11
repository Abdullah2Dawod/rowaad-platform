<x-rowaad-email>
    <span class="eyebrow" style="color: #D97706; background: rgba(245, 158, 11, 0.10); border-color: rgba(245, 158, 11, 0.30);">تم إلغاء الحجز</span>

    <h1>نأسف لإبلاغك بإلغاء استشارتك</h1>

    <p>
        مرحباً <strong>{{ $notifiableName }}</strong>،<br>
        نعتذر عن إبلاغك بأن حجزك مع المستشار <strong>{{ $consultantName }}</strong>
        (المرجع: <code style="background:#F1F5F9;padding:2px 8px;border-radius:6px;">{{ $b->reference }}</code>)
        قد أُلغي.
    </p>

    @if($b->cancellation_reason)
    <div class="panel">
        <p class="panel-title">سبب الإلغاء</p>
        <p style="margin: 0; color: #475569; font-size: 13.5px; line-height: 1.85;">
            {{ $b->cancellation_reason }}
        </p>
    </div>
    @endif

    <div class="panel" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(5, 150, 105, 0.03)); border-color: rgba(16, 185, 129, 0.2);">
        <p class="panel-title" style="color: #059669;">استرداد المبلغ</p>
        <p style="margin: 0; color: #475569; font-size: 13.5px; line-height: 1.85;">
            سيتم استرداد المبلغ بالكامل ({{ number_format($b->amount, 0) }} ر.س) إلى نفس وسيلة الدفع
            خلال <strong>5 أيام عمل</strong> كحدّ أقصى.
        </p>
    </div>

    <p>
        نرحّب بك لاختيار مستشار آخر يناسب احتياجاتك — لدينا نخبة من الخبراء الاقتصاديين المستعدين لمساعدتك.
    </p>

    <div class="btn-wrap">
        <a href="{{ url('/consultants') }}" class="btn">استعرض المستشارين</a>
    </div>

    <hr class="divider">

    <p style="color: #64748B; font-size: 12px; text-align: center;">
        نأسف على هذا الظرف — إذا احتجت أي مساعدة، تواصل معنا على
        <a href="mailto:info@rowaad.org" style="color: #3DAFB9; font-weight: 600;">info@rowaad.org</a>
    </p>
</x-rowaad-email>
