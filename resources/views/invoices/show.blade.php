<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة {{ $b->reference }} — {{ $siteName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400;600;700;800&family=Almarai:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Almarai','Segoe UI',sans-serif;background:#F0F4FA;color:#1A2F50;padding:32px 16px;direction:rtl;}
        .invoice{max-width:820px;margin:0 auto;background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 12px 40px -8px rgba(45,75,126,.2);}
        .strip{height:6px;background:linear-gradient(90deg,#2D4B7E,#3DAFB9,#6BC8D2);}
        .head{padding:32px 40px;display:flex;justify-content:space-between;align-items:flex-start;gap:24px;border-bottom:1px solid #E5F1F2;}
        .brand{font-family:'Alexandria',sans-serif;}
        .brand-name{font-size:22px;font-weight:800;color:#2D4B7E;}
        .brand-tag{font-size:11px;color:#3DAFB9;letter-spacing:.2em;text-transform:uppercase;margin-top:4px;}
        .brand-meta{font-size:11.5px;color:#64748B;margin-top:8px;line-height:1.7;}
        .doc-title{text-align:left;}
        .doc-title h1{font-family:'Alexandria',sans-serif;font-size:26px;font-weight:800;color:#2D4B7E;}
        .doc-title .ref{font-family:'Courier New',monospace;background:#F1F5F9;padding:4px 10px;border-radius:8px;font-size:12px;color:#334155;margin-top:6px;display:inline-block;}
        .doc-title .date{font-size:11px;color:#64748B;margin-top:6px;}
        .parties{padding:24px 40px;display:grid;grid-template-columns:1fr 1fr;gap:20px;}
        .party{background:linear-gradient(135deg,rgba(61,175,185,.05),rgba(45,75,126,.02));border:1px solid rgba(61,175,185,.15);border-radius:12px;padding:16px 18px;}
        .party-label{font-size:10.5px;font-weight:800;color:#3DAFB9;letter-spacing:.2em;text-transform:uppercase;margin-bottom:8px;}
        .party-name{font-size:14px;font-weight:800;color:#1A2F50;}
        .party-detail{font-size:12px;color:#475569;margin-top:4px;line-height:1.8;}
        .items{padding:0 40px 8px;}
        table{width:100%;border-collapse:collapse;}
        thead{background:#F8FAFC;}
        th{text-align:right;font-size:10.5px;font-weight:800;color:#64748B;letter-spacing:.15em;text-transform:uppercase;padding:12px 14px;border-bottom:2px solid #E2E8F0;}
        td{padding:16px 14px;font-size:13.5px;color:#1A2F50;border-bottom:1px dashed #E2E8F0;}
        td.num{font-weight:700;text-align:left;font-variant-numeric:tabular-nums;}
        .totals{padding:16px 40px 8px;display:flex;justify-content:flex-start;}
        .totals-box{min-width:320px;}
        .totals-row{display:flex;justify-content:space-between;padding:8px 0;font-size:13.5px;color:#475569;}
        .totals-row.grand{border-top:2px solid #2D4B7E;margin-top:8px;padding-top:14px;font-size:16px;color:#1A2F50;font-weight:800;}
        .totals-row .val{font-weight:700;font-variant-numeric:tabular-nums;}
        .split{padding:16px 40px 24px;}
        .split-title{font-size:10.5px;font-weight:800;color:#3DAFB9;letter-spacing:.2em;text-transform:uppercase;margin-bottom:10px;}
        .split-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
        .split-card{border-radius:12px;padding:14px;text-align:center;}
        .split-card .lbl{font-size:10.5px;font-weight:800;letter-spacing:.15em;text-transform:uppercase;}
        .split-card .val{font-size:18px;font-weight:800;margin-top:6px;}
        .split-consultant{background:#ECFDF5;border:1px solid #A7F3D0;color:#065F46;}
        .split-platform{background:#EFF6FF;border:1px solid #BFDBFE;color:#1E40AF;}
        .split-zakat{background:#FFFBEB;border:1px solid #FDE68A;color:#92400E;}
        .foot{padding:20px 40px 28px;background:#F8FAFC;border-top:1px solid #E5F1F2;text-align:center;font-size:11px;color:#64748B;}
        .foot a{color:#3DAFB9;font-weight:700;text-decoration:none;}
        .actions{max-width:820px;margin:0 auto 16px;text-align:left;}
        .btn{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#2D4B7E,#3DAFB9);color:#fff;padding:10px 20px;border-radius:999px;font-weight:800;font-size:13px;text-decoration:none;box-shadow:0 8px 20px -6px rgba(61,175,185,.5);border:0;cursor:pointer;font-family:inherit;}
        .btn.ghost{background:#fff;color:#2D4B7E;border:1px solid #CBD5E1;box-shadow:none;}
        @media print{
            body{background:#fff;padding:0;}
            .invoice{box-shadow:none;border-radius:0;}
            .actions{display:none;}
        }
    </style>
</head>
<body>
    <div class="actions">
        <a href="{{ url('/admin/invoices') }}" class="btn ghost">← رجوع</a>
        <button class="btn" onclick="window.print()">🖨️ طباعة / حفظ PDF</button>
    </div>

    <div class="invoice">
        <div class="strip"></div>

        <div class="head">
            <div class="brand">
                <div class="brand-name">{{ $siteName }}</div>
                <div class="brand-tag">للاستشارات الاقتصادية</div>
                <div class="brand-meta">
                    @if($siteAddress) {{ $siteAddress }}<br> @endif
                    @if($sitePhone) هاتف: <span dir="ltr">{{ $sitePhone }}</span><br> @endif
                    @if($siteEmail) البريد: <span dir="ltr">{{ $siteEmail }}</span> @endif
                </div>
            </div>
            <div class="doc-title">
                <h1>فاتورة ضريبية</h1>
                <div class="ref">{{ $b->reference }}</div>
                <div class="date">تاريخ الإصدار: {{ optional($b->paid_at)->format('Y-m-d') ?? '—' }}</div>
                <div class="date">حالة الحجز:
                    @php $l = ['paid'=>'مدفوع','confirmed'=>'مؤكّد','completed'=>'مكتمل'][$b->status] ?? $b->status; @endphp
                    <strong>{{ $l }}</strong>
                </div>
            </div>
        </div>

        <div class="parties">
            <div class="party">
                <div class="party-label">فوترة إلى / العميل</div>
                <div class="party-name">{{ $b->user?->name ?? '—' }}</div>
                <div class="party-detail">
                    <span dir="ltr">{{ $b->user?->email }}</span><br>
                    @if($b->user?->phone) <span dir="ltr">{{ $b->user->phone }}</span> @endif
                </div>
            </div>
            <div class="party">
                <div class="party-label">مقدّم الخدمة / المستشار</div>
                <div class="party-name">{{ $b->consultant?->full_name_ar ?: $b->consultant?->user?->name ?? '—' }}</div>
                <div class="party-detail">
                    {{ $b->consultant?->professional_title }}<br>
                    <span dir="ltr">{{ $b->consultant?->user?->email }}</span>
                </div>
            </div>
        </div>

        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>البيان</th>
                        <th>التاريخ والوقت</th>
                        <th>المدة</th>
                        <th style="text-align:left;">المبلغ (ر.س)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $b->service_title ?: 'استشارة اقتصادية' }}</strong>
                            @if($b->notes)<div style="font-size:11px;color:#64748B;margin-top:4px;">{{ \Illuminate\Support\Str::limit($b->notes, 120) }}</div>@endif
                        </td>
                        <td>{{ $b->preferred_date?->format('Y-m-d') }} · {{ $b->preferred_time }}</td>
                        <td>{{ $b->duration_min }} دقيقة</td>
                        <td class="num">{{ number_format($base, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="totals">
            <div class="totals-box">
                <div class="totals-row"><span>المبلغ الأساسي</span><span class="val">{{ number_format($base, 2) }} ر.س</span></div>
                <div class="totals-row"><span>ضريبة الزكاة (15%)</span><span class="val">+ {{ number_format($zakat, 2) }} ر.س</span></div>
                <div class="totals-row grand"><span>الإجمالي المدفوع</span><span class="val">{{ number_format($total, 2) }} ر.س</span></div>
            </div>
        </div>

        <div class="split">
            <div class="split-title">توزيع المبلغ بعد التحصيل</div>
            <div class="split-grid">
                <div class="split-card split-consultant">
                    <div class="lbl">حصة المستشار</div>
                    <div class="val">{{ number_format($b->consultant_share, 2) }}</div>
                </div>
                <div class="split-card split-platform">
                    <div class="lbl">حصة المنصة</div>
                    <div class="val">{{ number_format($b->platform_share, 2) }}</div>
                </div>
                <div class="split-card split-zakat">
                    <div class="lbl">وعاء الزكاة</div>
                    <div class="val">{{ number_format($zakat, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="foot">
            <div>وسيلة الدفع: <strong>{{ strtoupper($b->payment_method ?? '—') }}</strong>
                · مرجع الدفع: <span dir="ltr" style="font-family:monospace;">{{ $b->payment_ref ?? '—' }}</span></div>
            <div style="margin-top:8px;">
                {{ $siteName }} · © {{ date('Y') }} — جميع الحقوق محفوظة
                @if($siteEmail) · <a href="mailto:{{ $siteEmail }}">{{ $siteEmail }}</a> @endif
            </div>
        </div>
    </div>
</body>
</html>
