<x-filament-panels::page>
    @php
        $fmt = fn ($v) => number_format((float) $v, 2);
        $fmtShort = fn ($v) => $v >= 1000 ? number_format($v/1000, 1).'k' : number_format($v);
        $currency = 'ر.س';
    @endphp

    <div class="rw-fin" dir="rtl">

        {{-- ═════════ Period selector — pill segmented ═════════ --}}
        <div class="rw-seg-wrap">
            <div class="rw-seg" role="tablist">
                @foreach(['30d'=>'30 يوم','90d'=>'90 يوم','12m'=>'12 شهر','ytd'=>'منذ بداية السنة','all'=>'كل الوقت'] as $k=>$l)
                    <button type="button" wire:click="$set('range','{{ $k }}')" role="tab"
                        class="rw-seg-btn {{ $range === $k ? 'is-active' : '' }}">
                        {{ $l }}
                    </button>
                @endforeach
            </div>
            <div class="rw-seg-badge">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/>
                </svg>
                آخر تحديث الآن
            </div>
        </div>

        {{-- ═════════ HERO KPI GRID (4 premium cards) ═════════ --}}
        <div class="rw-kpi-grid">
            {{-- Revenue --}}
            <div class="rw-kpi rw-kpi--hero">
                <div class="rw-kpi-glow"></div>
                <div class="rw-kpi-head">
                    <div class="rw-kpi-icon" style="background:linear-gradient(135deg,#6BC8D2,#3DAFB9);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="rw-kpi-trend rw-kpi-trend--up">▲ إجمالي</div>
                </div>
                <div class="rw-kpi-label">إجمالي الإيرادات</div>
                <div class="rw-kpi-value">{{ $fmt($totals->gross ?? 0) }} <span class="rw-kpi-cur">{{ $currency }}</span></div>
                <div class="rw-kpi-foot">
                    <span class="rw-kpi-badge">{{ number_format($totals->bookings ?? 0) }} حجز</span>
                    <span class="rw-kpi-badge rw-kpi-badge--soft">شامل الزكاة</span>
                </div>
            </div>

            {{-- Consultants share --}}
            <div class="rw-kpi rw-kpi--emerald">
                <div class="rw-kpi-head">
                    <div class="rw-kpi-icon" style="background:linear-gradient(135deg,#10B981,#059669);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="rw-kpi-trend rw-kpi-trend--emerald">▲ 50%</div>
                </div>
                <div class="rw-kpi-label">حصص المستشارين</div>
                <div class="rw-kpi-value">{{ $fmt($totals->consultants ?? 0) }} <span class="rw-kpi-cur">{{ $currency }}</span></div>
                <div class="rw-kpi-foot">
                    <span class="rw-kpi-badge">{{ $consultantsCount }} مستشار نشط</span>
                    <span class="rw-kpi-badge rw-kpi-badge--soft">بلا خصم</span>
                </div>
            </div>

            {{-- Platform net --}}
            <div class="rw-kpi rw-kpi--blue">
                <div class="rw-kpi-head">
                    <div class="rw-kpi-icon" style="background:linear-gradient(135deg,#3B82F6,#1D4ED8);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="rw-kpi-trend rw-kpi-trend--blue">50%</div>
                </div>
                <div class="rw-kpi-label">صافي المنصة</div>
                <div class="rw-kpi-value">{{ $fmt($totals->platform ?? 0) }} <span class="rw-kpi-cur">{{ $currency }}</span></div>
                <div class="rw-kpi-foot">
                    <span class="rw-kpi-badge">حصة ثابتة 50%</span>
                    <span class="rw-kpi-badge rw-kpi-badge--soft">من كل حجز</span>
                </div>
            </div>

            {{-- Zakat pool --}}
            <div class="rw-kpi rw-kpi--amber">
                <div class="rw-kpi-head">
                    <div class="rw-kpi-icon" style="background:linear-gradient(135deg,#F59E0B,#D97706);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="rw-kpi-trend rw-kpi-trend--amber">15%</div>
                </div>
                <div class="rw-kpi-label">وعاء الزكاة</div>
                <div class="rw-kpi-value">{{ $fmt($totals->zakat ?? 0) }} <span class="rw-kpi-cur">{{ $currency }}</span></div>
                <div class="rw-kpi-foot">
                    <span class="rw-kpi-badge rw-kpi-badge--amber">متاح: {{ $fmt($zakatAvailable) }}</span>
                </div>
            </div>
        </div>

        {{-- ═════════ MONTHLY REVENUE CHART (SVG) ═════════ --}}
        @php
            $chartMax = max($monthly->max('gross') ?? 1, 1);
            $chartHeight = 220;
            $chartWidth = 100; // percentage
            $barCount = $monthly->count() ?: 1;
            $gap = 2;
            $barW = ($chartWidth - ($gap * ($barCount - 1))) / $barCount;
        @endphp
        <div class="rw-card">
            <div class="rw-card-head">
                <div>
                    <div class="rw-card-title">
                        <span class="rw-icon-chip" style="background:rgba(61,175,185,.12); color:#3DAFB9;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </span>
                        الإيرادات الشهرية
                    </div>
                    <div class="rw-card-sub">إجمالي المبلغ المُحصَّل شهرياً — آخر 12 شهر (شامل الزكاة)</div>
                </div>
                <div class="rw-card-legend">
                    <span class="rw-legend-dot" style="background:linear-gradient(180deg,#6BC8D2,#3DAFB9);"></span>
                    <span>الإيرادات</span>
                </div>
            </div>

            <div class="rw-chart-frame">
                {{-- Grid lines --}}
                <div class="rw-chart-grid">
                    @for($i = 0; $i <= 4; $i++)
                        <div class="rw-chart-gridline" style="bottom: {{ ($i / 4) * 100 }}%;"></div>
                    @endfor
                </div>
                {{-- Y-axis labels --}}
                <div class="rw-chart-yaxis">
                    @for($i = 4; $i >= 0; $i--)
                        <div>{{ $fmtShort($chartMax * ($i / 4)) }}</div>
                    @endfor
                </div>
                {{-- Bars --}}
                <div class="rw-chart-bars">
                    @foreach($monthly as $m)
                        @php $h = $chartMax > 0 ? max(1, ($m['gross'] / $chartMax) * 100) : 1; @endphp
                        <div class="rw-bar-wrap">
                            <div class="rw-bar-track">
                                <div class="rw-bar-tooltip">
                                    <div class="rw-bar-tooltip-val">{{ $fmt($m['gross']) }} ر.س</div>
                                    <div class="rw-bar-tooltip-lbl">{{ $m['label'] }}</div>
                                </div>
                                <div class="rw-bar" style="height:{{ $h }}%;"
                                     data-value="{{ $m['gross'] }}"></div>
                            </div>
                            <div class="rw-bar-label">{{ $m['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═════════ TWO-COLUMN: Top consultants + Services split ═════════ --}}
        <div class="rw-two-col">
            {{-- Top consultants --}}
            <div class="rw-card">
                <div class="rw-card-head">
                    <div>
                        <div class="rw-card-title">
                            <span class="rw-icon-chip" style="background:rgba(16,185,129,.12); color:#059669;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </span>
                            أعلى المستشارين ربحاً
                        </div>
                        <div class="rw-card-sub">حسب حصتهم من الحجوزات المدفوعة</div>
                    </div>
                </div>
                @if($topConsultants->isEmpty())
                    <div class="rw-empty">
                        <div class="rw-empty-icon">📈</div>
                        <div class="rw-empty-text">لا توجد بيانات في هذه الفترة.</div>
                    </div>
                @else
                    <div class="rw-rank-list">
                        @foreach($topConsultants as $i => $tc)
                            @php $max = $topConsultants->max('earnings') ?: 1; $pct = round(($tc->earnings / $max) * 100); @endphp
                            <div class="rw-rank-row">
                                <div class="rw-rank-badge rw-rank-badge--{{ $i < 3 ? ($i === 0 ? 'gold' : ($i === 1 ? 'silver' : 'bronze')) : 'normal' }}">
                                    {{ $i + 1 }}
                                </div>
                                <div class="rw-rank-body">
                                    <div class="rw-rank-top">
                                        <span class="rw-rank-name">{{ $tc->consultant?->full_name_ar ?: $tc->consultant?->user?->name ?? '—' }}</span>
                                        <span class="rw-rank-val">{{ $fmt($tc->earnings) }} <span class="rw-rank-cur">ر.س</span></span>
                                    </div>
                                    <div class="rw-rank-bar">
                                        <div class="rw-rank-fill" style="width:{{ $pct }}%;"></div>
                                    </div>
                                    <div class="rw-rank-meta">
                                        <span>{{ $tc->bookings }} حجز</span>
                                        <span class="rw-rank-share">{{ $pct }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Services distribution --}}
            <div class="rw-card">
                <div class="rw-card-head">
                    <div>
                        <div class="rw-card-title">
                            <span class="rw-icon-chip" style="background:rgba(59,130,246,.12); color:#3B82F6;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                </svg>
                            </span>
                            توزيع الإيرادات حسب الخدمة
                        </div>
                        <div class="rw-card-sub">أهم الخدمات المُدرَّة للدخل</div>
                    </div>
                </div>
                @if($byService->isEmpty())
                    <div class="rw-empty">
                        <div class="rw-empty-icon">🧭</div>
                        <div class="rw-empty-text">لا توجد بيانات.</div>
                    </div>
                @else
                    @php
                        $totalRev = $byService->sum('revenue') ?: 1;
                        $palette = ['#3DAFB9','#2D4B7E','#3B82F6','#10B981','#F59E0B','#EF4444'];
                    @endphp
                    {{-- Donut chart --}}
                    <div class="rw-donut-wrap">
                        <svg viewBox="0 0 42 42" class="rw-donut">
                            @php $accum = 0; @endphp
                            @foreach($byService as $i => $srv)
                                @php
                                    $dash = ($srv->revenue / $totalRev) * 100;
                                    $offset = 25 - $accum;
                                    $accum += $dash;
                                @endphp
                                <circle class="rw-donut-seg" cx="21" cy="21" r="15.9155"
                                        fill="transparent" stroke="{{ $palette[$i % count($palette)] }}"
                                        stroke-width="7" stroke-dasharray="{{ $dash }} {{ 100 - $dash }}"
                                        stroke-dashoffset="{{ $offset }}"/>
                            @endforeach
                        </svg>
                        <div class="rw-donut-center">
                            <div class="rw-donut-total">{{ $fmtShort($totalRev) }}</div>
                            <div class="rw-donut-lbl">إجمالي ر.س</div>
                        </div>
                    </div>
                    <div class="rw-legend-list">
                        @foreach($byService as $i => $srv)
                            @php $pct = round(($srv->revenue / $totalRev) * 100, 1); @endphp
                            <div class="rw-legend-item">
                                <span class="rw-legend-swatch" style="background:{{ $palette[$i % count($palette)] }};"></span>
                                <span class="rw-legend-name">{{ $srv->service_title ?: '— بلا اسم —' }}</span>
                                <span class="rw-legend-val">{{ $fmt($srv->revenue) }}</span>
                                <span class="rw-legend-pct">{{ $pct }}%</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ═════════ Withdrawals + Zakat cards ═════════ --}}
        <div class="rw-two-col">
            <div class="rw-card">
                <div class="rw-card-head">
                    <div>
                        <div class="rw-card-title">
                            <span class="rw-icon-chip" style="background:rgba(245,158,11,.12); color:#D97706;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </span>
                            سحوبات المستشارين
                        </div>
                    </div>
                </div>
                <div class="rw-mini-grid">
                    <div class="rw-mini rw-mini--warning">
                        <div class="rw-mini-lbl">قيد المراجعة</div>
                        <div class="rw-mini-val">{{ $fmt($withdrawalsPending) }}</div>
                        <div class="rw-mini-cur">ر.س</div>
                    </div>
                    <div class="rw-mini rw-mini--success">
                        <div class="rw-mini-lbl">تم التحويل</div>
                        <div class="rw-mini-val">{{ $fmt($withdrawalsPaid) }}</div>
                        <div class="rw-mini-cur">ر.س</div>
                    </div>
                </div>
                <a href="{{ url('/admin/withdrawal-requests') }}" class="rw-link-cta">
                    إدارة طلبات السحب
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="rw-card">
                <div class="rw-card-head">
                    <div>
                        <div class="rw-card-title">
                            <span class="rw-icon-chip" style="background:rgba(217,119,6,.15); color:#B45309;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </span>
                            وعاء الزكاة
                        </div>
                    </div>
                </div>
                <div class="rw-mini-grid rw-mini-grid--3">
                    <div class="rw-mini">
                        <div class="rw-mini-lbl">المُحصّل</div>
                        <div class="rw-mini-val">{{ $fmtShort($zakatCollected) }}</div>
                    </div>
                    <div class="rw-mini">
                        <div class="rw-mini-lbl">المُحوَّل</div>
                        <div class="rw-mini-val">{{ $fmtShort($zakatRemitted) }}</div>
                    </div>
                    <div class="rw-mini rw-mini--amber">
                        <div class="rw-mini-lbl">المتاح</div>
                        <div class="rw-mini-val">{{ $fmtShort($zakatAvailable) }}</div>
                    </div>
                </div>
                <a href="{{ url('/admin/zakat-pool') }}" class="rw-link-cta">
                    إدارة وعاء الزكاة
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        {{-- ═════════ Quick actions ═════════ --}}
        <div class="rw-cta-row">
            <a href="{{ url('/admin/invoices') }}" class="rw-cta rw-cta--primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                عرض الفواتير الكاملة
            </a>
            <a href="{{ url('/admin/bookings') }}" class="rw-cta rw-cta--ghost">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                الحجوزات
            </a>
        </div>
    </div>

    {{-- ═════════ STYLES ═════════ --}}
    <style>
        .rw-fin { --brand-navy:#2D4B7E; --brand-teal:#3DAFB9; --brand-teal-soft:#6BC8D2; --ink:#0F172A; --sub:#64748B; --bg:#FFFFFF; --line:rgba(15,23,42,.08); }
        .dark .rw-fin { --ink:#F1F5F9; --sub:#94A3B8; --bg:rgba(18,36,64,.65); --line:rgba(107,200,210,.12); }

        /* ─── Segmented period control ─── */
        .rw-seg-wrap { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
        .rw-seg { display:inline-flex; padding:4px; background:rgba(15,23,42,.04); border-radius:14px; gap:2px; }
        .dark .rw-seg { background:rgba(107,200,210,.06); }
        .rw-seg-btn { padding:8px 16px; border-radius:10px; font-size:12.5px; font-weight:700; color:var(--sub); background:transparent; border:0; cursor:pointer; transition:all 200ms cubic-bezier(0.16,1,0.3,1); font-family:inherit; }
        .rw-seg-btn:hover { color:var(--ink); }
        .rw-seg-btn.is-active { background:linear-gradient(135deg,#2D4B7E,#3DAFB9); color:#fff; box-shadow:0 4px 12px -4px rgba(61,175,185,.5); }
        .rw-seg-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:999px; background:rgba(16,185,129,.08); color:#059669; font-size:11px; font-weight:700; border:1px solid rgba(16,185,129,.15); }
        .rw-seg-badge svg { width:12px; height:12px; }
        .dark .rw-seg-badge { color:#34D399; background:rgba(16,185,129,.10); border-color:rgba(16,185,129,.20); }

        /* ─── KPI grid ─── */
        .rw-kpi-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; margin-bottom:24px; }
        @media (max-width:1200px){ .rw-kpi-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (max-width:640px){ .rw-kpi-grid { grid-template-columns:1fr; } }
        .rw-kpi { position:relative; background:var(--bg); border:1px solid var(--line); border-radius:18px; padding:20px; overflow:hidden; transition:transform 250ms cubic-bezier(0.16,1,0.3,1), box-shadow 250ms ease; }
        .rw-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px -12px rgba(45,75,126,.18); }
        .rw-kpi-glow { position:absolute; inset:0; background:radial-gradient(circle at top right, rgba(61,175,185,.18), transparent 70%); pointer-events:none; }
        .rw-kpi--hero { background:linear-gradient(135deg,#0A1729 0%,#122440 55%,#1A2F50 100%); color:#fff; border-color:rgba(107,200,210,.25); }
        .rw-kpi--hero .rw-kpi-label { color:#6BC8D2; }
        .rw-kpi--hero .rw-kpi-value { color:#fff; }
        .rw-kpi--hero .rw-kpi-cur { color:rgba(255,255,255,.6); }
        .rw-kpi--hero .rw-kpi-badge { background:rgba(107,200,210,.15); color:#C2EBEF; border-color:rgba(107,200,210,.2); }
        .rw-kpi--emerald { border-color:rgba(16,185,129,.2); background:linear-gradient(180deg, rgba(236,253,245,.6), var(--bg)); }
        .rw-kpi--blue    { border-color:rgba(59,130,246,.2); background:linear-gradient(180deg, rgba(239,246,255,.6), var(--bg)); }
        .rw-kpi--amber   { border-color:rgba(245,158,11,.25); background:linear-gradient(180deg, rgba(255,251,235,.7), var(--bg)); }
        .dark .rw-kpi--emerald,.dark .rw-kpi--blue,.dark .rw-kpi--amber { background:var(--bg); }

        .rw-kpi-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; position:relative; }
        .rw-kpi-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 6px 18px -4px rgba(61,175,185,.35); }
        .rw-kpi-icon svg { width:20px; height:20px; }
        .rw-kpi-trend { padding:4px 10px; border-radius:999px; font-size:10.5px; font-weight:800; letter-spacing:0.02em; }
        .rw-kpi-trend--up      { background:rgba(107,200,210,.15); color:#6BC8D2; }
        .rw-kpi-trend--emerald { background:rgba(16,185,129,.12);  color:#059669; }
        .rw-kpi-trend--blue    { background:rgba(59,130,246,.12);  color:#3B82F6; }
        .rw-kpi-trend--amber   { background:rgba(245,158,11,.15);  color:#D97706; }
        .rw-kpi-label { font-size:11.5px; font-weight:700; color:var(--sub); letter-spacing:.05em; margin-bottom:6px; position:relative; }
        .rw-kpi-value { font-size:28px; font-weight:900; color:var(--ink); line-height:1.1; letter-spacing:-0.02em; position:relative; font-variant-numeric:tabular-nums; }
        .rw-kpi-cur { font-size:12px; font-weight:500; color:var(--sub); margin-inline-start:4px; }
        .rw-kpi-foot { display:flex; gap:6px; flex-wrap:wrap; margin-top:14px; position:relative; }
        .rw-kpi-badge { display:inline-flex; align-items:center; padding:4px 10px; border-radius:999px; font-size:10.5px; font-weight:700; background:rgba(61,175,185,.08); color:#3DAFB9; border:1px solid rgba(61,175,185,.15); }
        .rw-kpi-badge--soft { background:rgba(15,23,42,.04); color:var(--sub); border-color:transparent; }
        .rw-kpi-badge--amber { background:rgba(245,158,11,.12); color:#B45309; border-color:rgba(245,158,11,.25); }

        /* ─── Cards ─── */
        .rw-card { background:var(--bg); border:1px solid var(--line); border-radius:18px; padding:20px; margin-bottom:16px; }
        .rw-card-head { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:20px; flex-wrap:wrap; }
        .rw-card-title { display:inline-flex; align-items:center; gap:10px; font-size:15px; font-weight:800; color:var(--ink); }
        .rw-card-sub { font-size:12px; color:var(--sub); margin-top:4px; margin-inline-start:38px; }
        .rw-icon-chip { width:28px; height:28px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; }
        .rw-icon-chip svg { width:14px; height:14px; }
        .rw-card-legend { display:inline-flex; align-items:center; gap:6px; font-size:11px; color:var(--sub); font-weight:600; }
        .rw-legend-dot { width:12px; height:12px; border-radius:3px; display:inline-block; }

        /* ─── Chart ─── */
        .rw-chart-frame { position:relative; height:260px; padding-inline-start:44px; padding-bottom:28px; padding-top:8px; }
        .rw-chart-grid { position:absolute; inset:8px 8px 28px 44px; }
        .rw-chart-gridline { position:absolute; left:0; right:0; height:1px; background:linear-gradient(90deg, transparent, var(--line), transparent); }
        .rw-chart-yaxis { position:absolute; inset:8px auto 28px 0; width:38px; display:flex; flex-direction:column; justify-content:space-between; font-size:10px; color:var(--sub); text-align:end; font-variant-numeric:tabular-nums; }
        .rw-chart-bars { position:relative; display:flex; align-items:flex-end; gap:6px; height:100%; padding-bottom:24px; }
        .rw-bar-wrap { flex:1; display:flex; flex-direction:column; align-items:center; height:100%; position:relative; }
        .rw-bar-track { flex:1; width:100%; display:flex; align-items:flex-end; position:relative; }
        .rw-bar { width:100%; border-radius:6px 6px 2px 2px; background:linear-gradient(180deg,#6BC8D2 0%,#3DAFB9 55%,#2D4B7E 100%); transition:all 300ms ease; box-shadow:inset 0 -2px 0 rgba(0,0,0,.05); min-height:2px; }
        .rw-bar-wrap:hover .rw-bar { transform:scaleY(1.02); transform-origin:bottom; filter:brightness(1.1); }
        .rw-bar-label { font-size:9.5px; color:var(--sub); margin-top:6px; text-align:center; white-space:nowrap; line-height:1.2; font-weight:600; position:absolute; bottom:0; }
        .rw-bar-tooltip { position:absolute; bottom:calc(100% + 6px); left:50%; transform:translateX(-50%); background:#0F172A; color:#fff; padding:6px 10px; border-radius:8px; font-size:10.5px; opacity:0; pointer-events:none; transition:opacity 200ms ease; white-space:nowrap; z-index:10; box-shadow:0 8px 24px -6px rgba(0,0,0,.4); }
        .rw-bar-tooltip::after { content:''; position:absolute; top:100%; left:50%; transform:translateX(-50%); border:5px solid transparent; border-top-color:#0F172A; }
        .rw-bar-tooltip-val { font-weight:800; font-variant-numeric:tabular-nums; }
        .rw-bar-tooltip-lbl { color:#94A3B8; font-size:9.5px; margin-top:2px; }
        .rw-bar-wrap:hover .rw-bar-tooltip { opacity:1; }

        /* ─── Two column layout ─── */
        .rw-two-col { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
        @media (max-width:900px){ .rw-two-col { grid-template-columns:1fr; } }

        /* ─── Rank list (top consultants) ─── */
        .rw-rank-list { display:flex; flex-direction:column; gap:14px; }
        .rw-rank-row { display:flex; gap:12px; align-items:center; }
        .rw-rank-badge { flex-shrink:0; width:32px; height:32px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:13px; color:#fff; }
        .rw-rank-badge--gold   { background:linear-gradient(135deg,#F59E0B,#D97706); box-shadow:0 4px 12px -3px rgba(245,158,11,.5); }
        .rw-rank-badge--silver { background:linear-gradient(135deg,#94A3B8,#64748B); box-shadow:0 4px 12px -3px rgba(148,163,184,.5); }
        .rw-rank-badge--bronze { background:linear-gradient(135deg,#B45309,#78350F); box-shadow:0 4px 12px -3px rgba(180,83,9,.4); }
        .rw-rank-badge--normal { background:linear-gradient(135deg,#2D4B7E,#3DAFB9); }
        .rw-rank-body { flex:1; min-width:0; }
        .rw-rank-top { display:flex; align-items:baseline; justify-content:space-between; gap:8px; margin-bottom:6px; }
        .rw-rank-name { font-weight:700; font-size:13px; color:var(--ink); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .rw-rank-val { font-weight:900; font-size:14px; color:#059669; font-variant-numeric:tabular-nums; white-space:nowrap; }
        .rw-rank-cur { font-size:10px; font-weight:600; color:var(--sub); }
        .rw-rank-bar { height:6px; background:rgba(15,23,42,.05); border-radius:999px; overflow:hidden; }
        .dark .rw-rank-bar { background:rgba(107,200,210,.06); }
        .rw-rank-fill { height:100%; background:linear-gradient(90deg,#3DAFB9,#2D4B7E); border-radius:999px; box-shadow:0 0 8px rgba(61,175,185,.4); }
        .rw-rank-meta { display:flex; justify-content:space-between; margin-top:4px; font-size:10.5px; color:var(--sub); font-weight:600; }
        .rw-rank-share { color:#3DAFB9; font-weight:800; }

        /* ─── Donut ─── */
        .rw-donut-wrap { position:relative; width:180px; height:180px; margin:8px auto 20px; }
        .rw-donut { width:100%; height:100%; transform:rotate(-90deg); }
        .rw-donut-seg { transition:stroke-width 200ms ease; }
        .rw-donut-seg:hover { stroke-width:9; }
        .rw-donut-center { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; pointer-events:none; }
        .rw-donut-total { font-size:22px; font-weight:900; color:var(--ink); line-height:1; font-variant-numeric:tabular-nums; }
        .rw-donut-lbl { font-size:10px; color:var(--sub); font-weight:700; margin-top:4px; letter-spacing:.1em; text-transform:uppercase; }
        .rw-legend-list { display:flex; flex-direction:column; gap:10px; }
        .rw-legend-item { display:grid; grid-template-columns:14px 1fr auto auto; align-items:center; gap:10px; padding:6px 0; border-bottom:1px dashed var(--line); font-size:12px; }
        .rw-legend-item:last-child { border-bottom:0; }
        .rw-legend-swatch { width:14px; height:14px; border-radius:4px; }
        .rw-legend-name { font-weight:600; color:var(--ink); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .rw-legend-val { font-weight:800; font-variant-numeric:tabular-nums; color:var(--ink); }
        .rw-legend-pct { padding:2px 8px; border-radius:6px; background:rgba(61,175,185,.08); color:#3DAFB9; font-weight:800; font-size:10.5px; font-variant-numeric:tabular-nums; }

        /* ─── Mini stat cards ─── */
        .rw-mini-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px; }
        .rw-mini-grid--3 { grid-template-columns:repeat(3,1fr); }
        .rw-mini { padding:14px 12px; border-radius:12px; background:rgba(15,23,42,.03); border:1px solid var(--line); text-align:center; }
        .dark .rw-mini { background:rgba(107,200,210,.04); }
        .rw-mini-lbl { font-size:10px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--sub); margin-bottom:6px; }
        .rw-mini-val { font-size:20px; font-weight:900; color:var(--ink); font-variant-numeric:tabular-nums; }
        .rw-mini-cur { font-size:10px; color:var(--sub); font-weight:700; margin-top:2px; }
        .rw-mini--warning { background:linear-gradient(180deg,rgba(245,158,11,.08),rgba(245,158,11,.02)); border-color:rgba(245,158,11,.2); }
        .rw-mini--warning .rw-mini-val { color:#D97706; }
        .rw-mini--success { background:linear-gradient(180deg,rgba(16,185,129,.08),rgba(16,185,129,.02)); border-color:rgba(16,185,129,.2); }
        .rw-mini--success .rw-mini-val { color:#059669; }
        .rw-mini--amber { background:linear-gradient(180deg,rgba(245,158,11,.10),rgba(245,158,11,.03)); border-color:rgba(245,158,11,.25); }
        .rw-mini--amber .rw-mini-val { color:#B45309; }

        .rw-link-cta { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:999px; background:rgba(61,175,185,.08); color:#3DAFB9; font-size:12px; font-weight:800; text-decoration:none; transition:all 200ms ease; }
        .rw-link-cta svg { width:14px; height:14px; }
        [dir="rtl"] .rw-link-cta svg { transform:rotate(180deg); }
        .rw-link-cta:hover { background:rgba(61,175,185,.15); transform:translateX(-2px); }

        /* ─── Empty state ─── */
        .rw-empty { padding:40px 20px; text-align:center; color:var(--sub); }
        .rw-empty-icon { font-size:32px; opacity:.4; margin-bottom:8px; }
        .rw-empty-text { font-size:13px; }

        /* ─── CTA row ─── */
        .rw-cta-row { display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; }
        .rw-cta { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; transition:all 200ms ease; }
        .rw-cta svg { width:16px; height:16px; }
        .rw-cta--primary { background:linear-gradient(135deg,#2D4B7E,#3DAFB9); color:#fff; box-shadow:0 8px 20px -6px rgba(61,175,185,.5); }
        .rw-cta--primary:hover { transform:translateY(-2px); box-shadow:0 12px 28px -8px rgba(61,175,185,.6); }
        .rw-cta--ghost { background:var(--bg); border:1px solid var(--line); color:var(--ink); }
        .rw-cta--ghost:hover { border-color:#3DAFB9; color:#3DAFB9; }

        @media (max-width:640px){
            .rw-kpi-value { font-size:22px; }
            .rw-chart-frame { height:200px; padding-inline-start:36px; }
            .rw-chart-yaxis { width:32px; font-size:9px; }
            .rw-donut-wrap { width:150px; height:150px; }
        }
    </style>
</x-filament-panels::page>
