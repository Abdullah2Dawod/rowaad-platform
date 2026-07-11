<x-filament-panels::page>
    @php
        $fmt = fn ($v) => number_format((float) $v, 2);
        $fmtShort = fn ($v) => $v >= 1000 ? number_format($v/1000, 1).'k' : number_format($v);
        $pct = $collected > 0 ? round(($remitted / $collected) * 100) : 0;
    @endphp

    <div class="rw-zk" dir="rtl">

        {{-- ═════════ HERO: Available (huge) + circular progress ═════════ --}}
        <div class="rw-zk-hero">
            <div class="rw-zk-hero-main">
                <div class="rw-zk-hero-eyebrow">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>الهيئة العامة للزكاة والدخل · وعاء المنصة</span>
                </div>
                <div class="rw-zk-hero-title">المتاح في الوعاء الآن</div>
                <div class="rw-zk-hero-value">
                    {{ $fmt($available) }}
                    <span class="rw-zk-hero-cur">ر.س</span>
                </div>
                <div class="rw-zk-hero-sub">
                    جاهز للتحويل للهيئة العامة عند انتهاء الفترة الضريبية.
                </div>
                <div class="rw-zk-hero-stats">
                    <div class="rw-zk-hero-stat">
                        <div class="rw-zk-hero-stat-val">15%</div>
                        <div class="rw-zk-hero-stat-lbl">نسبة الزكاة على كل حجز</div>
                    </div>
                    <div class="rw-zk-hero-stat-divider"></div>
                    <div class="rw-zk-hero-stat">
                        <div class="rw-zk-hero-stat-val">{{ $remittances->total() }}</div>
                        <div class="rw-zk-hero-stat-lbl">تحويل مُسجَّل</div>
                    </div>
                    <div class="rw-zk-hero-stat-divider"></div>
                    <div class="rw-zk-hero-stat">
                        <div class="rw-zk-hero-stat-val">{{ $pct }}%</div>
                        <div class="rw-zk-hero-stat-lbl">من المُحصَّل تم تحويله</div>
                    </div>
                </div>
            </div>

            {{-- Circular progress --}}
            <div class="rw-zk-hero-ring">
                @php
                    $r = 65;
                    $circ = 2 * pi() * $r;
                    $dashLen = ($pct / 100) * $circ;
                @endphp
                <svg viewBox="0 0 160 160" width="160" height="160">
                    {{-- Track --}}
                    <circle cx="80" cy="80" r="{{ $r }}" fill="none" stroke="rgba(255,255,255,.12)" stroke-width="12"/>
                    {{-- Progress --}}
                    <circle cx="80" cy="80" r="{{ $r }}" fill="none" stroke="url(#zkProgress)" stroke-width="12"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $dashLen }} {{ $circ }}"
                            transform="rotate(-90 80 80)"/>
                    <defs>
                        <linearGradient id="zkProgress" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#FCD34D"/>
                            <stop offset="100%" stop-color="#F59E0B"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="rw-zk-hero-ring-inner">
                    <div class="rw-zk-hero-ring-pct">{{ $pct }}%</div>
                    <div class="rw-zk-hero-ring-lbl">مُحوَّل</div>
                </div>
            </div>
        </div>

        {{-- ═════════ Secondary stats + register button ═════════ --}}
        <div class="rw-zk-stats-row">
            <div class="rw-zk-stat rw-zk-stat--slate">
                <div class="rw-zk-stat-head">
                    <div class="rw-zk-stat-icon" style="background:linear-gradient(135deg,#94A3B8,#64748B);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="rw-zk-stat-lbl">إجمالي المُحصَّل</div>
                        <div class="rw-zk-stat-hint">15% من كل حجز مدفوع</div>
                    </div>
                </div>
                <div class="rw-zk-stat-val">{{ $fmt($collected) }} <span>ر.س</span></div>
            </div>

            <div class="rw-zk-stat rw-zk-stat--emerald">
                <div class="rw-zk-stat-head">
                    <div class="rw-zk-stat-icon" style="background:linear-gradient(135deg,#10B981,#059669);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="rw-zk-stat-lbl">إجمالي المُحوَّل</div>
                        <div class="rw-zk-stat-hint">مجموع التحويلات السابقة للهيئة</div>
                    </div>
                </div>
                <div class="rw-zk-stat-val" style="color:#059669;">{{ $fmt($remitted) }} <span>ر.س</span></div>
            </div>

            <div class="rw-zk-stat rw-zk-stat--action">
                <div class="rw-zk-stat-head">
                    <div class="rw-zk-stat-icon" style="background:linear-gradient(135deg,#F59E0B,#D97706);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="rw-zk-stat-lbl">إجراء سريع</div>
                        <div class="rw-zk-stat-hint">سجّل تحويلاً جديداً للهيئة</div>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('rwZkForm').scrollIntoView({behavior:'smooth'})"
                        class="rw-zk-stat-btn">
                    تسجيل تحويل جديد
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- ═════════ Register form ═════════ --}}
        <div id="rwZkForm" class="rw-zk-form-card">
            <div class="rw-zk-form-head">
                <div class="rw-zk-form-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <div class="rw-zk-form-title">تسجيل تحويل للهيئة العامة</div>
                    <div class="rw-zk-form-sub">سجّل هنا كل عملية تحويل للزكاة المتراكمة في وعاء المنصة.</div>
                </div>
            </div>
            <form wire:submit="record" class="rw-zk-form-body">
                {{ $this->form }}
                <div class="rw-zk-form-actions">
                    <x-filament::button type="submit" icon="heroicon-o-check" size="lg">تسجيل التحويل</x-filament::button>
                </div>
            </form>
        </div>

        {{-- ═════════ Remittances log ═════════ --}}
        <div class="rw-zk-log">
            <div class="rw-zk-log-head">
                <div>
                    <div class="rw-zk-log-title">
                        <span class="rw-zk-log-title-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        سجل التحويلات
                    </div>
                    <div class="rw-zk-log-sub">جميع التحويلات المسجّلة للهيئة العامة للزكاة والدخل</div>
                </div>
                <div class="rw-zk-log-count">{{ $remittances->total() }} تحويل</div>
            </div>

            @if($remittances->isEmpty())
                <div class="rw-zk-empty">
                    <div class="rw-zk-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="rw-zk-empty-title">لم تُسجَّل تحويلات بعد</div>
                    <div class="rw-zk-empty-sub">استخدم النموذج أعلاه لتسجيل أول تحويل.</div>
                </div>
            @else
                <div class="rw-zk-timeline">
                    @foreach($remittances as $r)
                        <div class="rw-zk-timeline-item">
                            <div class="rw-zk-timeline-dot">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="rw-zk-timeline-card">
                                <div class="rw-zk-timeline-top">
                                    <div class="rw-zk-timeline-date">
                                        <div class="rw-zk-timeline-date-main">{{ $r->remitted_at->format('Y-m-d') }}</div>
                                        <div class="rw-zk-timeline-date-sub">{{ $r->remitted_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="rw-zk-timeline-amount">
                                        {{ $fmt($r->amount) }} <span>ر.س</span>
                                    </div>
                                </div>
                                <div class="rw-zk-timeline-meta">
                                    <div class="rw-zk-timeline-field">
                                        <div class="rw-zk-timeline-field-lbl">الفترة</div>
                                        <div class="rw-zk-timeline-field-val">
                                            {{ $r->period_from->format('Y-m-d') }} → {{ $r->period_to->format('Y-m-d') }}
                                        </div>
                                    </div>
                                    <div class="rw-zk-timeline-field">
                                        <div class="rw-zk-timeline-field-lbl">مرجع GAZT</div>
                                        <div class="rw-zk-timeline-field-val" dir="ltr" style="font-family:monospace;">
                                            {{ $r->reference ?: '—' }}
                                        </div>
                                    </div>
                                    <div class="rw-zk-timeline-field">
                                        <div class="rw-zk-timeline-field-lbl">سجّله</div>
                                        <div class="rw-zk-timeline-field-val">{{ $r->remittedBy?->name ?? '—' }}</div>
                                    </div>
                                </div>
                                @if($r->notes)
                                    <div class="rw-zk-timeline-notes">{{ $r->notes }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="rw-zk-pagi">{{ $remittances->links() }}</div>
            @endif
        </div>
    </div>

    <style>
        .rw-zk { --brand-navy:#2D4B7E; --brand-teal:#3DAFB9; --amber:#D97706; --amber-light:#F59E0B; --ink:#0F172A; --sub:#64748B; --bg:#FFFFFF; --line:rgba(15,23,42,.08); }
        .dark .rw-zk { --ink:#F1F5F9; --sub:#94A3B8; --bg:rgba(18,36,64,.65); --line:rgba(107,200,210,.12); }

        /* ─── HERO ─── */
        .rw-zk-hero {
            position:relative; overflow:hidden;
            background:linear-gradient(135deg,#7C2D12 0%,#9A3412 30%,#B45309 60%,#D97706 100%);
            border-radius:24px; padding:36px 32px;
            display:grid; grid-template-columns: 1fr auto; gap:24px; align-items:center;
            box-shadow:0 20px 50px -15px rgba(180,83,9,.5);
            color:#fff; margin-bottom:24px;
        }
        .rw-zk-hero::before {
            content:''; position:absolute; top:-40%; right:-15%; width:60%; height:180%;
            background:radial-gradient(circle, rgba(252,211,77,.25), transparent 60%);
            pointer-events:none;
        }
        .rw-zk-hero::after {
            content:''; position:absolute; bottom:-40%; left:-15%; width:50%; height:150%;
            background:radial-gradient(circle, rgba(120,53,15,.35), transparent 60%);
            pointer-events:none;
        }
        .rw-zk-hero-main { position:relative; z-index:1; }
        .rw-zk-hero-eyebrow { display:inline-flex; align-items:center; gap:8px; padding:6px 14px; border-radius:999px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.2); font-size:11px; font-weight:800; letter-spacing:.04em; margin-bottom:16px; backdrop-filter:blur(10px); }
        .rw-zk-hero-eyebrow svg { width:14px; height:14px; }
        .rw-zk-hero-title { font-size:14px; font-weight:700; color:rgba(255,255,255,.75); margin-bottom:8px; }
        .rw-zk-hero-value { font-size:52px; font-weight:900; line-height:1; letter-spacing:-.03em; font-variant-numeric:tabular-nums; margin-bottom:12px; }
        .rw-zk-hero-cur { font-size:20px; font-weight:600; color:rgba(255,255,255,.6); }
        .rw-zk-hero-sub { font-size:13.5px; color:rgba(255,255,255,.7); max-width:520px; line-height:1.7; margin-bottom:24px; }
        .rw-zk-hero-stats { display:flex; align-items:center; gap:20px; padding-top:20px; border-top:1px solid rgba(255,255,255,.15); }
        .rw-zk-hero-stat { }
        .rw-zk-hero-stat-val { font-size:22px; font-weight:900; letter-spacing:-.02em; font-variant-numeric:tabular-nums; }
        .rw-zk-hero-stat-lbl { font-size:10.5px; color:rgba(255,255,255,.6); font-weight:600; margin-top:2px; }
        .rw-zk-hero-stat-divider { width:1px; height:32px; background:rgba(255,255,255,.15); }
        .rw-zk-hero-ring { position:relative; width:160px; height:160px; z-index:1; }
        .rw-zk-hero-ring-inner { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; }
        .rw-zk-hero-ring-pct { font-size:32px; font-weight:900; line-height:1; }
        .rw-zk-hero-ring-lbl { font-size:10px; color:rgba(255,255,255,.7); font-weight:700; letter-spacing:.15em; text-transform:uppercase; margin-top:4px; }

        @media (max-width:900px){
            .rw-zk-hero { grid-template-columns:1fr; padding:28px 22px; }
            .rw-zk-hero-value { font-size:38px; }
            .rw-zk-hero-ring { margin-inline-start:auto; width:120px; height:120px; }
            .rw-zk-hero-ring svg { width:120px; height:120px; }
            .rw-zk-hero-ring-pct { font-size:22px; }
            .rw-zk-hero-stats { flex-wrap:wrap; gap:12px; }
        }

        /* ─── Secondary stats ─── */
        .rw-zk-stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:24px; }
        @media (max-width:900px){ .rw-zk-stats-row { grid-template-columns:1fr; } }
        .rw-zk-stat { background:var(--bg); border:1px solid var(--line); border-radius:16px; padding:18px; transition:transform 200ms ease, box-shadow 200ms ease; }
        .rw-zk-stat:hover { transform:translateY(-2px); box-shadow:0 10px 24px -8px rgba(45,75,126,.15); }
        .rw-zk-stat--slate { background:linear-gradient(180deg,rgba(248,250,252,.6),var(--bg)); }
        .rw-zk-stat--emerald { border-color:rgba(16,185,129,.2); background:linear-gradient(180deg,rgba(236,253,245,.5),var(--bg)); }
        .rw-zk-stat--action { border-color:rgba(245,158,11,.25); background:linear-gradient(180deg,rgba(255,251,235,.6),var(--bg)); }
        .dark .rw-zk-stat--slate,.dark .rw-zk-stat--emerald,.dark .rw-zk-stat--action { background:var(--bg); }
        .rw-zk-stat-head { display:flex; align-items:flex-start; gap:12px; margin-bottom:14px; }
        .rw-zk-stat-icon { flex-shrink:0; width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 6px 16px -4px rgba(0,0,0,.15); }
        .rw-zk-stat-icon svg { width:20px; height:20px; }
        .rw-zk-stat-lbl { font-size:13px; font-weight:800; color:var(--ink); }
        .rw-zk-stat-hint { font-size:11px; color:var(--sub); margin-top:2px; }
        .rw-zk-stat-val { font-size:26px; font-weight:900; color:var(--ink); font-variant-numeric:tabular-nums; letter-spacing:-.02em; line-height:1; }
        .rw-zk-stat-val span { font-size:11px; font-weight:600; color:var(--sub); margin-inline-start:4px; }
        .rw-zk-stat-btn { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:12px; background:linear-gradient(135deg,#F59E0B,#D97706); color:#fff; font-size:12.5px; font-weight:800; border:0; cursor:pointer; font-family:inherit; box-shadow:0 8px 20px -6px rgba(245,158,11,.5); transition:all 200ms ease; }
        .rw-zk-stat-btn:hover { transform:translateY(-1px); box-shadow:0 12px 24px -6px rgba(245,158,11,.6); }
        .rw-zk-stat-btn svg { width:14px; height:14px; }
        [dir="rtl"] .rw-zk-stat-btn svg { transform:rotate(180deg); }

        /* ─── Form card ─── */
        .rw-zk-form-card { background:var(--bg); border:1px solid var(--line); border-radius:18px; padding:22px; margin-bottom:24px; scroll-margin-top:100px; }
        .rw-zk-form-head { display:flex; align-items:center; gap:14px; margin-bottom:16px; padding-bottom:16px; border-bottom:1px solid var(--line); }
        .rw-zk-form-badge { width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg,#F59E0B,#D97706); color:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 18px -4px rgba(245,158,11,.45); }
        .rw-zk-form-badge svg { width:22px; height:22px; }
        .rw-zk-form-title { font-size:16px; font-weight:900; color:var(--ink); }
        .rw-zk-form-sub { font-size:12px; color:var(--sub); margin-top:2px; }
        .rw-zk-form-actions { display:flex; justify-content:flex-end; margin-top:16px; padding-top:16px; border-top:1px solid var(--line); }

        /* ─── Log ─── */
        .rw-zk-log { background:var(--bg); border:1px solid var(--line); border-radius:18px; overflow:hidden; }
        .rw-zk-log-head { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; padding:20px 22px; border-bottom:1px solid var(--line); }
        .rw-zk-log-title { display:inline-flex; align-items:center; gap:10px; font-size:15px; font-weight:800; color:var(--ink); }
        .rw-zk-log-title-icon { width:28px; height:28px; border-radius:8px; background:rgba(245,158,11,.12); color:#D97706; display:inline-flex; align-items:center; justify-content:center; }
        .rw-zk-log-title-icon svg { width:14px; height:14px; }
        .rw-zk-log-sub { font-size:11.5px; color:var(--sub); margin-top:4px; margin-inline-start:38px; }
        .rw-zk-log-count { display:inline-flex; align-items:center; padding:6px 12px; border-radius:999px; background:rgba(245,158,11,.1); color:#D97706; font-size:11px; font-weight:800; border:1px solid rgba(245,158,11,.2); }

        /* ─── Timeline ─── */
        .rw-zk-timeline { padding:20px 22px; position:relative; }
        .rw-zk-timeline::before { content:''; position:absolute; top:32px; bottom:32px; inset-inline-end:34px; width:2px; background:linear-gradient(180deg, rgba(245,158,11,.2), rgba(245,158,11,.05) 90%); }
        [dir="rtl"] .rw-zk-timeline::before { inset-inline-end:auto; inset-inline-start:auto; right:34px; }
        .rw-zk-timeline-item { display:flex; gap:16px; margin-bottom:16px; position:relative; }
        .rw-zk-timeline-item:last-child { margin-bottom:0; }
        .rw-zk-timeline-dot { flex-shrink:0; width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#10B981,#059669); color:#fff; display:flex; align-items:center; justify-content:center; z-index:1; box-shadow:0 4px 12px -3px rgba(16,185,129,.5), 0 0 0 4px var(--bg); }
        .rw-zk-timeline-dot svg { width:14px; height:14px; }
        .rw-zk-timeline-card { flex:1; background:linear-gradient(180deg, rgba(245,158,11,.03), transparent); border:1px solid var(--line); border-radius:14px; padding:16px 18px; transition:all 200ms ease; }
        .rw-zk-timeline-card:hover { border-color:rgba(245,158,11,.3); box-shadow:0 8px 20px -8px rgba(245,158,11,.15); }
        .rw-zk-timeline-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; padding-bottom:12px; border-bottom:1px dashed var(--line); }
        .rw-zk-timeline-date-main { font-size:13px; font-weight:800; color:var(--ink); font-variant-numeric:tabular-nums; }
        .rw-zk-timeline-date-sub { font-size:10.5px; color:var(--sub); margin-top:2px; }
        .rw-zk-timeline-amount { font-size:20px; font-weight:900; color:#D97706; font-variant-numeric:tabular-nums; letter-spacing:-.01em; }
        .rw-zk-timeline-amount span { font-size:11px; color:var(--sub); font-weight:600; }
        .rw-zk-timeline-meta { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
        @media (max-width:640px){ .rw-zk-timeline-meta { grid-template-columns:1fr; } }
        .rw-zk-timeline-field-lbl { font-size:9.5px; font-weight:800; color:var(--sub); letter-spacing:.12em; text-transform:uppercase; margin-bottom:4px; }
        .rw-zk-timeline-field-val { font-size:12px; font-weight:700; color:var(--ink); }
        .rw-zk-timeline-notes { margin-top:12px; padding-top:12px; border-top:1px dashed var(--line); font-size:12px; color:var(--sub); font-style:italic; line-height:1.7; }

        .rw-zk-pagi { padding:14px 22px; border-top:1px solid var(--line); }

        .rw-zk-empty { padding:60px 20px; text-align:center; }
        .rw-zk-empty-icon { width:64px; height:64px; margin:0 auto 16px; border-radius:20px; background:linear-gradient(135deg,rgba(245,158,11,.12),rgba(217,119,6,.06)); color:#D97706; display:flex; align-items:center; justify-content:center; }
        .rw-zk-empty-icon svg { width:28px; height:28px; }
        .rw-zk-empty-title { font-size:15px; font-weight:800; color:var(--ink); margin-bottom:6px; }
        .rw-zk-empty-sub { font-size:12.5px; color:var(--sub); }
    </style>
</x-filament-panels::page>
