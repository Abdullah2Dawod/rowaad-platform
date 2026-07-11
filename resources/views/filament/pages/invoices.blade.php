<x-filament-panels::page>
    @php
        $fmt = fn ($v) => number_format((float) $v, 2);
        $fmtShort = fn ($v) => $v >= 1000 ? number_format($v/1000, 1).'k' : number_format($v);
    @endphp

    <div class="rw-inv" dir="rtl">

        {{-- ═════════ HERO KPI ROW ═════════ --}}
        <div class="rw-inv-kpi">
            <div class="rw-inv-card rw-inv-card--hero">
                <div class="rw-inv-halo"></div>
                <div class="rw-inv-head">
                    <div class="rw-inv-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="rw-inv-chip">إجمالي</span>
                </div>
                <div class="rw-inv-lbl">إجمالي الفواتير</div>
                <div class="rw-inv-val">{{ number_format($totals->count ?? 0) }}</div>
                <div class="rw-inv-sub">فاتورة صادرة</div>
            </div>

            <div class="rw-inv-card">
                <div class="rw-inv-head">
                    <div class="rw-inv-icon" style="background:linear-gradient(135deg,#6BC8D2,#3DAFB9);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="rw-inv-chip rw-inv-chip--teal">شامل الزكاة</span>
                </div>
                <div class="rw-inv-lbl">الإيرادات</div>
                <div class="rw-inv-val" style="color:#2D4B7E;">{{ $fmt($totals->gross ?? 0) }}</div>
                <div class="rw-inv-sub">ر.س</div>
            </div>

            <div class="rw-inv-card rw-inv-card--emerald">
                <div class="rw-inv-head">
                    <div class="rw-inv-icon" style="background:linear-gradient(135deg,#10B981,#059669);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="rw-inv-chip rw-inv-chip--emerald">50%</span>
                </div>
                <div class="rw-inv-lbl">حصة المستشارين</div>
                <div class="rw-inv-val" style="color:#059669;">{{ $fmt($totals->consultants ?? 0) }}</div>
                <div class="rw-inv-sub">ر.س</div>
            </div>

            <div class="rw-inv-card rw-inv-card--blue">
                <div class="rw-inv-head">
                    <div class="rw-inv-icon" style="background:linear-gradient(135deg,#3B82F6,#1D4ED8);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    </div>
                    <span class="rw-inv-chip rw-inv-chip--blue">صافي</span>
                </div>
                <div class="rw-inv-lbl">حصة المنصة</div>
                <div class="rw-inv-val" style="color:#3B82F6;">{{ $fmt($totals->platform ?? 0) }}</div>
                <div class="rw-inv-sub">ر.س</div>
            </div>

            <div class="rw-inv-card rw-inv-card--amber">
                <div class="rw-inv-head">
                    <div class="rw-inv-icon" style="background:linear-gradient(135deg,#F59E0B,#D97706);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="rw-inv-chip rw-inv-chip--amber">15%</span>
                </div>
                <div class="rw-inv-lbl">وعاء الزكاة</div>
                <div class="rw-inv-val" style="color:#D97706;">{{ $fmt($totals->zakat ?? 0) }}</div>
                <div class="rw-inv-sub">ر.س</div>
            </div>
        </div>

        {{-- ═════════ FILTER SEGMENTED CONTROL ═════════ --}}
        <div class="rw-inv-toolbar">
            <div class="rw-inv-seg">
                @foreach(['all'=>['الكل',$totals->count ?? 0],'paid'=>['مدفوع',null],'confirmed'=>['مؤكّد',null],'completed'=>['مكتمل',null]] as $k=>[$l, $count])
                    <button type="button" wire:click="$set('filter','{{ $k }}')"
                        class="rw-inv-seg-btn {{ $filter === $k ? 'is-active' : '' }}">
                        {{ $l }}
                        @if($count !== null)<span class="rw-inv-count">{{ $count }}</span>@endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- ═════════ INVOICES TABLE ═════════ --}}
        <div class="rw-inv-table-wrap">
            @if($invoices->isEmpty())
                <div class="rw-inv-empty">
                    <div class="rw-inv-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="rw-inv-empty-title">لا توجد فواتير مطابقة</div>
                    <div class="rw-inv-empty-sub">جرّب تغيير الفلتر أعلاه.</div>
                </div>
            @else
                <div class="rw-inv-table">
                    <div class="rw-inv-thead">
                        <div class="rw-inv-thead-cell">المرجع</div>
                        <div class="rw-inv-thead-cell">التاريخ</div>
                        <div class="rw-inv-thead-cell">العميل / المستشار</div>
                        <div class="rw-inv-thead-cell rw-inv-num">الأساسي</div>
                        <div class="rw-inv-thead-cell rw-inv-num">الزكاة</div>
                        <div class="rw-inv-thead-cell rw-inv-num">الإجمالي</div>
                        <div class="rw-inv-thead-cell rw-inv-num">التوزيع</div>
                        <div class="rw-inv-thead-cell rw-inv-center">الحالة</div>
                        <div class="rw-inv-thead-cell rw-inv-center"></div>
                    </div>

                    @foreach($invoices as $inv)
                        @php
                            $base = $inv->consultant_share + $inv->platform_share;
                            $statusMap = [
                                'paid'      => ['مدفوع', 'blue'],
                                'confirmed' => ['مؤكّد', 'amber'],
                                'completed' => ['مكتمل', 'emerald'],
                            ];
                            [$statusLbl, $statusColor] = $statusMap[$inv->status] ?? [$inv->status, 'gray'];
                        @endphp
                        <div class="rw-inv-row">
                            {{-- Reference --}}
                            <div class="rw-inv-cell">
                                <div class="rw-inv-ref">
                                    <div class="rw-inv-ref-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="rw-inv-ref-body">
                                        <div class="rw-inv-ref-code" dir="ltr">{{ $inv->reference }}</div>
                                        <div class="rw-inv-ref-sub">{{ $inv->duration_min }} دقيقة</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Date --}}
                            <div class="rw-inv-cell">
                                <div class="rw-inv-date">
                                    <div class="rw-inv-date-main">{{ $inv->paid_at?->format('Y-m-d') ?? '—' }}</div>
                                    <div class="rw-inv-date-sub">{{ $inv->paid_at?->format('H:i') }}</div>
                                </div>
                            </div>

                            {{-- Client / Consultant --}}
                            <div class="rw-inv-cell">
                                <div class="rw-inv-people">
                                    <div class="rw-inv-person">
                                        <span class="rw-inv-person-lbl">العميل</span>
                                        <span class="rw-inv-person-name">{{ $inv->user?->name ?? '—' }}</span>
                                    </div>
                                    <div class="rw-inv-person">
                                        <span class="rw-inv-person-lbl">المستشار</span>
                                        <span class="rw-inv-person-name">{{ $inv->consultant?->full_name_ar ?: $inv->consultant?->user?->name ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Base --}}
                            <div class="rw-inv-cell rw-inv-num">
                                <div class="rw-inv-money">{{ $fmt($base) }}</div>
                            </div>

                            {{-- Zakat --}}
                            <div class="rw-inv-cell rw-inv-num">
                                <div class="rw-inv-money rw-inv-money--amber">+ {{ $fmt($inv->zakat_amount) }}</div>
                            </div>

                            {{-- Total --}}
                            <div class="rw-inv-cell rw-inv-num">
                                <div class="rw-inv-money rw-inv-money--total">{{ $fmt($inv->amount) }}</div>
                                <div class="rw-inv-cur">ر.س</div>
                            </div>

                            {{-- Distribution mini bar --}}
                            <div class="rw-inv-cell rw-inv-num">
                                <div class="rw-inv-split">
                                    <div class="rw-inv-split-bar">
                                        <span class="rw-inv-split-seg" style="width:{{ ($inv->consultant_share / $inv->amount) * 100 }}%; background:#10B981;" title="مستشار"></span>
                                        <span class="rw-inv-split-seg" style="width:{{ ($inv->platform_share / $inv->amount) * 100 }}%; background:#3B82F6;" title="منصة"></span>
                                        <span class="rw-inv-split-seg" style="width:{{ ($inv->zakat_amount / $inv->amount) * 100 }}%; background:#F59E0B;" title="زكاة"></span>
                                    </div>
                                    <div class="rw-inv-split-legend">
                                        <span style="color:#059669;">{{ $fmt($inv->consultant_share) }}</span>
                                        <span style="color:#3B82F6;">{{ $fmt($inv->platform_share) }}</span>
                                        <span style="color:#D97706;">{{ $fmt($inv->zakat_amount) }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="rw-inv-cell rw-inv-center">
                                <span class="rw-inv-status rw-inv-status--{{ $statusColor }}">
                                    <span class="rw-inv-status-dot"></span>
                                    {{ $statusLbl }}
                                </span>
                            </div>

                            {{-- Action --}}
                            <div class="rw-inv-cell rw-inv-center">
                                <a href="{{ url('/invoices/'.$inv->id) }}" target="_blank" class="rw-inv-action" title="عرض الفاتورة">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    فاتورة
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="rw-inv-pagi">{{ $invoices->links() }}</div>
            @endif
        </div>
    </div>

    <style>
        .rw-inv { --brand-navy:#2D4B7E; --brand-teal:#3DAFB9; --brand-teal-soft:#6BC8D2; --ink:#0F172A; --sub:#64748B; --bg:#FFFFFF; --line:rgba(15,23,42,.08); }
        .dark .rw-inv { --ink:#F1F5F9; --sub:#94A3B8; --bg:rgba(18,36,64,.65); --line:rgba(107,200,210,.12); }

        /* ─── KPI row ─── */
        .rw-inv-kpi { display:grid; grid-template-columns:1.4fr repeat(4,1fr); gap:14px; margin-bottom:24px; }
        @media (max-width:1200px){ .rw-inv-kpi { grid-template-columns:repeat(3,1fr); } .rw-inv-card--hero { grid-column:span 3; } }
        @media (max-width:640px){ .rw-inv-kpi { grid-template-columns:repeat(2,1fr); } .rw-inv-card--hero { grid-column:span 2; } }

        .rw-inv-card { position:relative; background:var(--bg); border:1px solid var(--line); border-radius:16px; padding:18px; overflow:hidden; transition:all 250ms cubic-bezier(0.16,1,0.3,1); }
        .rw-inv-card:hover { transform:translateY(-2px); box-shadow:0 12px 32px -12px rgba(45,75,126,.18); }
        .rw-inv-card--hero { background:linear-gradient(135deg,#0A1729 0%,#122440 55%,#1A2F50 100%); color:#fff; border-color:rgba(107,200,210,.25); }
        .rw-inv-card--hero .rw-inv-lbl { color:#6BC8D2; }
        .rw-inv-card--hero .rw-inv-val { color:#fff; }
        .rw-inv-card--hero .rw-inv-sub { color:rgba(255,255,255,.55); }
        .rw-inv-card--hero .rw-inv-chip { background:rgba(107,200,210,.18); color:#C2EBEF; border-color:rgba(107,200,210,.25); }
        .rw-inv-halo { position:absolute; inset:0; background:radial-gradient(circle at top right, rgba(107,200,210,.2), transparent 60%); pointer-events:none; }
        .rw-inv-card--emerald { border-color:rgba(16,185,129,.2); background:linear-gradient(180deg, rgba(236,253,245,.5), var(--bg)); }
        .rw-inv-card--blue    { border-color:rgba(59,130,246,.2); background:linear-gradient(180deg, rgba(239,246,255,.5), var(--bg)); }
        .rw-inv-card--amber   { border-color:rgba(245,158,11,.25); background:linear-gradient(180deg, rgba(255,251,235,.6), var(--bg)); }
        .dark .rw-inv-card--emerald,.dark .rw-inv-card--blue,.dark .rw-inv-card--amber { background:var(--bg); }

        .rw-inv-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; position:relative; }
        .rw-inv-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; background:linear-gradient(135deg,#2D4B7E,#3DAFB9); box-shadow:0 4px 12px -3px rgba(61,175,185,.4); }
        .rw-inv-icon svg { width:18px; height:18px; }
        .rw-inv-chip { padding:3px 8px; border-radius:999px; font-size:9.5px; font-weight:800; letter-spacing:.05em; background:rgba(15,23,42,.05); color:var(--sub); border:1px solid transparent; }
        .rw-inv-chip--teal    { background:rgba(61,175,185,.1);  color:#3DAFB9; border-color:rgba(61,175,185,.2); }
        .rw-inv-chip--emerald { background:rgba(16,185,129,.1);  color:#059669; border-color:rgba(16,185,129,.2); }
        .rw-inv-chip--blue    { background:rgba(59,130,246,.1);  color:#3B82F6; border-color:rgba(59,130,246,.2); }
        .rw-inv-chip--amber   { background:rgba(245,158,11,.12); color:#D97706; border-color:rgba(245,158,11,.25); }
        .rw-inv-lbl { font-size:11px; font-weight:700; color:var(--sub); letter-spacing:.05em; margin-bottom:6px; position:relative; }
        .rw-inv-val { font-size:22px; font-weight:900; color:var(--ink); line-height:1.1; letter-spacing:-0.02em; font-variant-numeric:tabular-nums; position:relative; }
        .rw-inv-card--hero .rw-inv-val { font-size:32px; }
        .rw-inv-sub { font-size:10.5px; color:var(--sub); font-weight:600; margin-top:6px; position:relative; }

        /* ─── Toolbar ─── */
        .rw-inv-toolbar { display:flex; justify-content:flex-start; margin-bottom:20px; }
        .rw-inv-seg { display:inline-flex; padding:4px; background:rgba(15,23,42,.04); border-radius:14px; gap:2px; }
        .dark .rw-inv-seg { background:rgba(107,200,210,.06); }
        .rw-inv-seg-btn { padding:8px 16px; border-radius:10px; font-size:12.5px; font-weight:700; color:var(--sub); background:transparent; border:0; cursor:pointer; transition:all 200ms cubic-bezier(0.16,1,0.3,1); font-family:inherit; display:inline-flex; align-items:center; gap:6px; }
        .rw-inv-seg-btn:hover { color:var(--ink); }
        .rw-inv-seg-btn.is-active { background:linear-gradient(135deg,#2D4B7E,#3DAFB9); color:#fff; box-shadow:0 4px 12px -4px rgba(61,175,185,.5); }
        .rw-inv-count { display:inline-flex; align-items:center; justify-content:center; min-width:20px; height:18px; padding:0 6px; border-radius:6px; background:rgba(15,23,42,.08); font-size:10px; font-weight:800; }
        .rw-inv-seg-btn.is-active .rw-inv-count { background:rgba(255,255,255,.25); }

        /* ─── Table ─── */
        .rw-inv-table-wrap { background:var(--bg); border:1px solid var(--line); border-radius:18px; overflow:hidden; }
        .rw-inv-table { display:flex; flex-direction:column; }
        .rw-inv-thead { display:grid; grid-template-columns: 160px 90px 1.6fr 90px 100px 110px 1.2fr 100px 90px; gap:12px; padding:14px 20px; background:linear-gradient(180deg, rgba(15,23,42,.03), rgba(15,23,42,.01)); border-bottom:1px solid var(--line); font-size:10px; font-weight:800; letter-spacing:.15em; text-transform:uppercase; color:var(--sub); }
        .dark .rw-inv-thead { background:linear-gradient(180deg, rgba(107,200,210,.04), rgba(107,200,210,.02)); }
        .rw-inv-row { display:grid; grid-template-columns: 160px 90px 1.6fr 90px 100px 110px 1.2fr 100px 90px; gap:12px; padding:16px 20px; border-bottom:1px solid var(--line); transition:background-color 150ms ease; align-items:center; }
        .rw-inv-row:hover { background:rgba(61,175,185,.03); }
        .rw-inv-row:last-child { border-bottom:0; }
        .rw-inv-cell { min-width:0; }
        .rw-inv-num { text-align:end; font-variant-numeric:tabular-nums; }
        .rw-inv-center { text-align:center; display:flex; align-items:center; justify-content:center; }

        .rw-inv-ref { display:flex; align-items:center; gap:10px; }
        .rw-inv-ref-icon { flex-shrink:0; width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,rgba(61,175,185,.15),rgba(45,75,126,.1)); color:#3DAFB9; display:flex; align-items:center; justify-content:center; }
        .rw-inv-ref-icon svg { width:16px; height:16px; }
        .rw-inv-ref-code { font-family:'Courier New',monospace; font-size:11.5px; font-weight:800; color:var(--ink); }
        .rw-inv-ref-sub { font-size:10px; color:var(--sub); margin-top:1px; }

        .rw-inv-date-main { font-size:12px; font-weight:700; color:var(--ink); font-variant-numeric:tabular-nums; }
        .rw-inv-date-sub { font-size:10.5px; color:var(--sub); margin-top:1px; font-variant-numeric:tabular-nums; }

        .rw-inv-people { display:flex; flex-direction:column; gap:4px; min-width:0; }
        .rw-inv-person { display:flex; align-items:baseline; gap:8px; min-width:0; }
        .rw-inv-person-lbl { flex-shrink:0; font-size:9.5px; font-weight:800; color:var(--sub); letter-spacing:.08em; text-transform:uppercase; width:42px; }
        .rw-inv-person-name { font-size:12px; font-weight:700; color:var(--ink); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

        .rw-inv-money { font-size:13px; font-weight:800; color:var(--ink); font-variant-numeric:tabular-nums; }
        .rw-inv-money--amber { color:#D97706; font-weight:700; }
        .rw-inv-money--total { font-size:15px; color:#2D4B7E; font-weight:900; }
        .dark .rw-inv-money--total { color:#6BC8D2; }
        .rw-inv-cur { font-size:9.5px; color:var(--sub); font-weight:700; }

        .rw-inv-split { display:flex; flex-direction:column; gap:5px; }
        .rw-inv-split-bar { display:flex; height:6px; border-radius:999px; overflow:hidden; background:rgba(15,23,42,.06); }
        .dark .rw-inv-split-bar { background:rgba(107,200,210,.08); }
        .rw-inv-split-seg { display:block; height:100%; transition:filter 200ms; }
        .rw-inv-split-seg:hover { filter:brightness(1.15); }
        .rw-inv-split-legend { display:flex; justify-content:space-between; font-size:10px; font-weight:800; font-variant-numeric:tabular-nums; }

        .rw-inv-status { display:inline-flex; align-items:center; gap:6px; padding:5px 11px; border-radius:999px; font-size:10.5px; font-weight:800; }
        .rw-inv-status-dot { width:6px; height:6px; border-radius:50%; }
        .rw-inv-status--blue    { background:rgba(59,130,246,.1);  color:#3B82F6; }
        .rw-inv-status--blue .rw-inv-status-dot    { background:#3B82F6; box-shadow:0 0 0 3px rgba(59,130,246,.15); }
        .rw-inv-status--amber   { background:rgba(245,158,11,.12); color:#D97706; }
        .rw-inv-status--amber .rw-inv-status-dot   { background:#F59E0B; box-shadow:0 0 0 3px rgba(245,158,11,.15); }
        .rw-inv-status--emerald { background:rgba(16,185,129,.1);  color:#059669; }
        .rw-inv-status--emerald .rw-inv-status-dot { background:#10B981; box-shadow:0 0 0 3px rgba(16,185,129,.15); }
        .rw-inv-status--gray    { background:rgba(100,116,139,.1); color:#64748B; }

        .rw-inv-action { display:inline-flex; align-items:center; gap:5px; padding:7px 12px; border-radius:10px; background:linear-gradient(135deg,#2D4B7E,#3DAFB9); color:#fff; font-size:11px; font-weight:800; text-decoration:none; transition:all 200ms ease; box-shadow:0 4px 12px -4px rgba(61,175,185,.4); }
        .rw-inv-action svg { width:12px; height:12px; }
        .rw-inv-action:hover { transform:translateY(-1px); box-shadow:0 6px 16px -4px rgba(61,175,185,.55); }

        .rw-inv-pagi { padding:14px 20px; border-top:1px solid var(--line); }

        /* ─── Empty state ─── */
        .rw-inv-empty { padding:60px 20px; text-align:center; }
        .rw-inv-empty-icon { width:64px; height:64px; margin:0 auto 16px; border-radius:20px; background:linear-gradient(135deg,rgba(61,175,185,.12),rgba(45,75,126,.06)); color:#3DAFB9; display:flex; align-items:center; justify-content:center; }
        .rw-inv-empty-icon svg { width:28px; height:28px; }
        .rw-inv-empty-title { font-size:15px; font-weight:800; color:var(--ink); margin-bottom:6px; }
        .rw-inv-empty-sub { font-size:12.5px; color:var(--sub); }

        /* ─── Responsive: card layout on tablet/mobile ─── */
        @media (max-width:1200px) {
            .rw-inv-thead { display:none; }
            .rw-inv-row {
                grid-template-columns: 1fr 1fr;
                grid-template-areas:
                    "ref date"
                    "people people"
                    "base zakat"
                    "total split"
                    "status action";
                gap:10px 12px; padding:16px; border-radius:14px; border:1px solid var(--line); margin:12px; background:var(--bg);
            }
            .rw-inv-cell { min-width:0; }
            .rw-inv-cell:nth-child(1){ grid-area:ref; }
            .rw-inv-cell:nth-child(2){ grid-area:date; text-align:end; }
            .rw-inv-cell:nth-child(3){ grid-area:people; border-top:1px dashed var(--line); border-bottom:1px dashed var(--line); padding:10px 0; }
            .rw-inv-cell:nth-child(4){ grid-area:base; text-align:start; }
            .rw-inv-cell:nth-child(5){ grid-area:zakat; }
            .rw-inv-cell:nth-child(6){ grid-area:total; text-align:start; }
            .rw-inv-cell:nth-child(7){ grid-area:split; }
            .rw-inv-cell:nth-child(8){ grid-area:status; justify-content:flex-start; }
            .rw-inv-cell:nth-child(9){ grid-area:action; justify-content:flex-end; }
        }
    </style>
</x-filament-panels::page>
