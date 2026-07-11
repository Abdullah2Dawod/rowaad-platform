@php
    $fmt = fn ($v) => number_format((float) $v, 2);
    $fmtShort = fn ($v) => $v >= 1000 ? number_format($v/1000, 1).'k' : number_format($v);
    $timelineWithData = $timeline->filter(fn ($t) => $t['revenue'] > 0)->count();
    $sparkPath = '';
    $sparkAreaPath = '';
    if ($timeline->count() > 1) {
        $points = [];
        foreach ($timeline as $i => $t) {
            $x = ($i / ($timeline->count() - 1)) * 800;
            $y = $timelineMax > 0 ? 220 - (($t['revenue'] / $timelineMax) * 180) : 220;
            $points[] = "{$x},{$y}";
        }
        $sparkPath = 'M ' . implode(' L ', $points);
        $sparkAreaPath = "M 0,220 L " . implode(' L ', $points) . " L 800,220 Z";
    }
    // Now/Day/Hour string
    $now = now();
    $hour = (int) $now->format('H');
    $greeting = $hour < 12 ? 'صباح الخير' : ($hour < 18 ? 'مساء الخير' : 'مساء الخير');
@endphp
<div class="rw-dh" wire:poll.20s dir="rtl">
    {{-- ═══════════════════════════════════════════════════════════
         HERO CARD — greeting, today's snapshot, big revenue chart
         ═══════════════════════════════════════════════════════════ --}}
    <div class="rw-dh-hero">
        {{-- Ambient decorative glow --}}
        <div class="rw-dh-glow rw-dh-glow--1"></div>
        <div class="rw-dh-glow rw-dh-glow--2"></div>
        <div class="rw-dh-grid-bg"></div>

        <div class="rw-dh-hero-content">
            {{-- LEFT: greeting + today stats --}}
            <div class="rw-dh-hero-left">
                <div class="rw-dh-time">
                    <div class="rw-dh-pulse"></div>
                    <span>{{ $now->translatedFormat('l · d F Y · H:i') }}</span>
                </div>
                <h1 class="rw-dh-greet">
                    {{ $greeting }}، <span class="rw-dh-greet-name">{{ $greetName }}</span>
                </h1>
                <p class="rw-dh-sub">
                    @if($role === 'admin')
                        نظرة سريعة على أداء المنصة اليوم — كل شيء يعمل كما ينبغي.
                    @else
                        نظرة سريعة على أدائك اليوم — نتمنى لك يوماً مثمراً.
                    @endif
                </p>

                <div class="rw-dh-today">
                    <div class="rw-dh-today-item">
                        <div class="rw-dh-today-lbl">
                            @if($role === 'admin') إيراد المنصة اليوم @else أرباحك اليوم @endif
                        </div>
                        <div class="rw-dh-today-val">
                            {{ $fmt($todayRevenue) }}
                            <span class="rw-dh-today-cur">ر.س</span>
                        </div>
                        @if($revTrend !== 0)
                            <div class="rw-dh-today-trend rw-dh-today-trend--{{ $revTrend >= 0 ? 'up' : 'down' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $revTrend >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"/>
                                </svg>
                                {{ abs($revTrend) }}% عن الأمس
                            </div>
                        @endif
                    </div>
                    <div class="rw-dh-today-divider"></div>
                    <div class="rw-dh-today-item">
                        <div class="rw-dh-today-lbl">حجوزات اليوم</div>
                        <div class="rw-dh-today-val">{{ $todayBookings }}</div>
                        <div class="rw-dh-today-note">حجز جديد</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: revenue chart (SVG) --}}
            <div class="rw-dh-hero-right">
                <div class="rw-dh-chart-head">
                    <div>
                        <div class="rw-dh-chart-title">
                            @if($role === 'admin') إيراد المنصة · آخر 30 يوم @else أرباحك · آخر 30 يوم @endif
                        </div>
                        <div class="rw-dh-chart-sub">{{ $timelineWithData }} يوم بحركة نشطة</div>
                    </div>
                    <div class="rw-dh-chart-tag">
                        <span class="rw-dh-tag-dot"></span>
                        مباشر
                    </div>
                </div>
                <div class="rw-dh-chart">
                    <svg viewBox="0 0 800 240" preserveAspectRatio="none" class="rw-dh-svg">
                        <defs>
                            <linearGradient id="rwDhArea" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%"   stop-color="#6BC8D2" stop-opacity="0.55"/>
                                <stop offset="60%"  stop-color="#3DAFB9" stop-opacity="0.15"/>
                                <stop offset="100%" stop-color="#3DAFB9" stop-opacity="0"/>
                            </linearGradient>
                            <linearGradient id="rwDhLine" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%"   stop-color="#6BC8D2"/>
                                <stop offset="100%" stop-color="#3DAFB9"/>
                            </linearGradient>
                            <filter id="rwDhGlow">
                                <feGaussianBlur stdDeviation="3" result="blur"/>
                                <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
                            </filter>
                        </defs>
                        {{-- Grid lines --}}
                        @for($i = 0; $i <= 3; $i++)
                            <line x1="0" x2="800" y1="{{ 40 + $i * 60 }}" y2="{{ 40 + $i * 60 }}"
                                  stroke="rgba(107,200,210,0.10)" stroke-width="1" stroke-dasharray="2 6"/>
                        @endfor
                        {{-- Area --}}
                        @if($sparkAreaPath)
                            <path d="{{ $sparkAreaPath }}" fill="url(#rwDhArea)"/>
                        @endif
                        {{-- Line --}}
                        @if($sparkPath)
                            <path d="{{ $sparkPath }}" fill="none" stroke="url(#rwDhLine)" stroke-width="2.5"
                                  stroke-linecap="round" stroke-linejoin="round" filter="url(#rwDhGlow)"/>
                        @endif
                        {{-- Points --}}
                        @foreach($timeline as $i => $t)
                            @php
                                $x = ($i / max(1, $timeline->count() - 1)) * 800;
                                $y = $timelineMax > 0 ? 220 - (($t['revenue'] / $timelineMax) * 180) : 220;
                            @endphp
                            @if($t['revenue'] > 0)
                                <circle cx="{{ $x }}" cy="{{ $y }}" r="{{ $t['isToday'] ? 5 : 3 }}"
                                        fill="{{ $t['isToday'] ? '#F59E0B' : '#3DAFB9' }}"
                                        stroke="#FFFFFF" stroke-width="1.5">
                                    <title>{{ $t['label'] }}: {{ $fmt($t['revenue']) }} ر.س</title>
                                </circle>
                            @endif
                        @endforeach
                    </svg>

                    {{-- X-axis labels (sparse) --}}
                    <div class="rw-dh-xaxis">
                        @foreach($timeline as $i => $t)
                            @if($i % 5 === 0 || $t['isToday'])
                                <span class="{{ $t['isToday'] ? 'is-today' : '' }}"
                                      style="left: {{ ($i / max(1, $timeline->count() - 1)) * 100 }}%;">
                                    {{ $t['label'] }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         ACTIONABLES ROW — what needs attention now
         ═══════════════════════════════════════════════════════════ --}}
    <div class="rw-dh-actions">
        @foreach($actionables as $a)
            <a href="{{ url($a['url']) }}" class="rw-dh-action rw-dh-action--{{ $a['color'] }}">
                <div class="rw-dh-action-icon">
                    @if(str_contains($a['label'], 'اعتماد') || str_contains($a['label'], 'انضمام'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    @elseif(str_contains($a['label'], 'حجوز'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @elseif(str_contains($a['label'], 'تعديل'))
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    @endif
                </div>
                <div class="rw-dh-action-body">
                    <div class="rw-dh-action-count">{{ $a['count'] }}</div>
                    <div class="rw-dh-action-lbl">{{ $a['label'] }}</div>
                </div>
                <svg class="rw-dh-action-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TWO-COL: Activity feed + Best consultant / calendar heatmap
         ═══════════════════════════════════════════════════════════ --}}
    <div class="rw-dh-two-col">
        {{-- Live activity feed --}}
        <div class="rw-dh-card">
            <div class="rw-dh-card-head">
                <div>
                    <div class="rw-dh-card-title">
                        <span class="rw-dh-icon-chip rw-dh-icon-chip--teal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </span>
                        النشاط المباشر
                    </div>
                    <div class="rw-dh-card-sub">آخر الأحداث في المنصة</div>
                </div>
                <span class="rw-dh-live-tag">
                    <span class="rw-dh-live-dot"></span>
                    مباشر
                </span>
            </div>
            @if($activity->isEmpty())
                <div class="rw-dh-empty">
                    <div class="rw-dh-empty-icon">◇</div>
                    <div>لا يوجد نشاط بعد.</div>
                </div>
            @else
                <div class="rw-dh-feed">
                    @foreach($activity as $ev)
                        <div class="rw-dh-feed-item">
                            <div class="rw-dh-feed-dot rw-dh-feed-dot--{{ $ev['color'] }}">
                                @if($ev['icon'] === 'booking')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @elseif($ev['icon'] === 'user')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <div class="rw-dh-feed-body">
                                <div class="rw-dh-feed-top">
                                    <span class="rw-dh-feed-title">{{ $ev['title'] }}</span>
                                    @if(isset($ev['amount']))
                                        <span class="rw-dh-feed-amount">{{ $fmtShort($ev['amount']) }} ر.س</span>
                                    @endif
                                </div>
                                <div class="rw-dh-feed-sub">{{ $ev['sub'] }}</div>
                                <div class="rw-dh-feed-time">{{ $ev['time']?->diffForHumans() ?? '—' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Best performer / calendar heatmap --}}
        <div class="rw-dh-card">
            @if($role === 'admin' && $bestConsultant)
                <div class="rw-dh-card-head">
                    <div>
                        <div class="rw-dh-card-title">
                            <span class="rw-dh-icon-chip rw-dh-icon-chip--gold">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM11.146 4.146a1 1 0 011.708 0l1.294 1.293h1.897a1 1 0 011 1v1.897l1.293 1.294a1 1 0 010 1.708l-1.293 1.294v1.897a1 1 0 01-1 1h-1.897l-1.294 1.293a1 1 0 01-1.708 0l-1.294-1.293H7.146a1 1 0 01-1-1v-1.897L4.853 11.854a1 1 0 010-1.708l1.293-1.294V7.44a1 1 0 011-1h1.706l1.294-1.293z"/>
                                </svg>
                            </span>
                            المستشار الأعلى ربحاً · هذا الشهر
                        </div>
                        <div class="rw-dh-card-sub">حسب حصته من الحجوزات المدفوعة</div>
                    </div>
                </div>
                <div class="rw-dh-star">
                    <div class="rw-dh-star-medal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="rw-dh-star-name">
                        {{ $bestConsultant->consultant?->full_name_ar ?: $bestConsultant->consultant?->user?->name ?? '—' }}
                    </div>
                    <div class="rw-dh-star-title">
                        {{ $bestConsultant->consultant?->professional_title ?? 'مستشار معتمد' }}
                    </div>
                    <div class="rw-dh-star-stats">
                        <div class="rw-dh-star-stat">
                            <div class="rw-dh-star-stat-val">{{ $fmt($bestConsultant->earnings) }}</div>
                            <div class="rw-dh-star-stat-lbl">أرباح · ر.س</div>
                        </div>
                        <div class="rw-dh-star-stat-divider"></div>
                        <div class="rw-dh-star-stat">
                            <div class="rw-dh-star-stat-val">{{ $bestConsultant->bookings }}</div>
                            <div class="rw-dh-star-stat-lbl">حجز</div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Booking heatmap (30 day) --}}
                <div class="rw-dh-card-head">
                    <div>
                        <div class="rw-dh-card-title">
                            <span class="rw-dh-icon-chip rw-dh-icon-chip--emerald">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                </svg>
                            </span>
                            خريطة النشاط · 30 يوم
                        </div>
                        <div class="rw-dh-card-sub">كل مربّع يمثّل يوماً — الأغمق = نشاط أعلى</div>
                    </div>
                </div>
                <div class="rw-dh-heatmap">
                    @php $maxCount = max($timeline->max('count') ?? 1, 1); @endphp
                    @foreach($timeline as $t)
                        @php
                            $intensity = $maxCount > 0 ? min(1, $t['count'] / $maxCount) : 0;
                            $bg = $t['count'] === 0
                                ? 'rgba(107,200,210,0.06)'
                                : "rgba(61,175,185," . (0.2 + $intensity * 0.75) . ")";
                        @endphp
                        <div class="rw-dh-heat-cell {{ $t['isToday'] ? 'is-today' : '' }}"
                             style="background:{{ $bg }};" title="{{ $t['label'] }}: {{ $t['count'] }} حجز">
                        </div>
                    @endforeach
                </div>
                <div class="rw-dh-heat-legend">
                    <span>أقلّ</span>
                    <span class="rw-dh-heat-swatch" style="background:rgba(107,200,210,0.06);"></span>
                    <span class="rw-dh-heat-swatch" style="background:rgba(61,175,185,0.35);"></span>
                    <span class="rw-dh-heat-swatch" style="background:rgba(61,175,185,0.65);"></span>
                    <span class="rw-dh-heat-swatch" style="background:rgba(61,175,185,0.95);"></span>
                    <span>أكثر</span>
                </div>
            @endif
        </div>
    </div>

    <style>
        .rw-dh { --brand-navy:#2D4B7E; --brand-teal:#3DAFB9; --brand-teal-soft:#6BC8D2; --ink:#0F172A; --sub:#64748B; --bg:#FFFFFF; --line:rgba(15,23,42,.08); }
        .dark .rw-dh { --ink:#F1F5F9; --sub:#94A3B8; --bg:rgba(18,36,64,.65); --line:rgba(107,200,210,.12); }

        /* ═══════════ HERO ═══════════ */
        .rw-dh-hero {
            position:relative; overflow:hidden;
            background: linear-gradient(135deg,#0A1729 0%,#122440 45%,#1A2F50 100%);
            border-radius:24px; padding:32px; margin-bottom:20px;
            box-shadow: 0 20px 50px -18px rgba(45,75,126,.5);
            color:#fff;
        }
        .rw-dh-glow { position:absolute; border-radius:50%; pointer-events:none; }
        .rw-dh-glow--1 { top:-30%; right:-10%; width:600px; height:600px; background:radial-gradient(circle, rgba(107,200,210,.22), transparent 65%); }
        .rw-dh-glow--2 { bottom:-40%; left:-10%; width:520px; height:520px; background:radial-gradient(circle, rgba(45,75,126,.4), transparent 65%); }
        .rw-dh-grid-bg { position:absolute; inset:0; opacity:.06; background-image: linear-gradient(rgba(107,200,210,.6) 1px, transparent 1px), linear-gradient(90deg, rgba(107,200,210,.6) 1px, transparent 1px); background-size: 48px 48px; pointer-events:none; }

        .rw-dh-hero-content { position:relative; z-index:1; display:grid; grid-template-columns: 1fr 1.4fr; gap:32px; align-items:center; }
        @media (max-width:1100px){ .rw-dh-hero-content { grid-template-columns:1fr; gap:24px; } }

        .rw-dh-hero-left { min-width:0; }
        .rw-dh-time { display:inline-flex; align-items:center; gap:8px; padding:6px 14px; border-radius:999px; background:rgba(107,200,210,.12); border:1px solid rgba(107,200,210,.25); font-size:11px; font-weight:800; color:#6BC8D2; letter-spacing:.02em; margin-bottom:16px; backdrop-filter:blur(10px); }
        .rw-dh-pulse { width:8px; height:8px; border-radius:50%; background:#10B981; box-shadow:0 0 0 4px rgba(16,185,129,.25); animation:rwDhPulse 2s ease-in-out infinite; }
        @keyframes rwDhPulse { 0%,100%{ transform:scale(1); opacity:1;} 50%{ transform:scale(1.15); opacity:.8;} }

        .rw-dh-greet { font-size:32px; font-weight:900; letter-spacing:-.02em; line-height:1.15; margin-bottom:8px; color:#fff; }
        .rw-dh-greet-name { background:linear-gradient(135deg,#6BC8D2,#3DAFB9); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; color:transparent; }
        .rw-dh-sub { font-size:13.5px; color:rgba(255,255,255,.6); line-height:1.7; margin-bottom:24px; max-width:420px; }

        .rw-dh-today { display:flex; align-items:center; padding:20px 22px; border-radius:16px; background:rgba(255,255,255,.05); border:1px solid rgba(107,200,210,.15); backdrop-filter:blur(20px); }
        .rw-dh-today-item { flex:1; }
        .rw-dh-today-lbl { font-size:10.5px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.5); margin-bottom:6px; }
        .rw-dh-today-val { font-size:28px; font-weight:900; letter-spacing:-.02em; font-variant-numeric:tabular-nums; color:#fff; line-height:1; }
        .rw-dh-today-cur { font-size:12px; font-weight:500; color:rgba(255,255,255,.5); margin-inline-start:4px; }
        .rw-dh-today-note { font-size:10.5px; color:rgba(255,255,255,.5); margin-top:4px; font-weight:600; }
        .rw-dh-today-trend { display:inline-flex; align-items:center; gap:4px; margin-top:6px; padding:3px 10px; border-radius:999px; font-size:10.5px; font-weight:800; }
        .rw-dh-today-trend svg { width:10px; height:10px; }
        .rw-dh-today-trend--up   { background:rgba(16,185,129,.15);  color:#10B981; }
        .rw-dh-today-trend--down { background:rgba(239,68,68,.15);  color:#EF4444; }
        .rw-dh-today-divider { width:1px; height:60px; background:rgba(107,200,210,.15); margin:0 20px; }

        /* Chart right side */
        .rw-dh-hero-right { min-width:0; }
        .rw-dh-chart-head { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px; }
        .rw-dh-chart-title { font-size:13px; font-weight:800; color:#fff; }
        .rw-dh-chart-sub { font-size:11px; color:rgba(255,255,255,.5); margin-top:2px; }
        .rw-dh-chart-tag { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:999px; background:rgba(16,185,129,.12); color:#34D399; font-size:10px; font-weight:800; border:1px solid rgba(16,185,129,.2); }
        .rw-dh-tag-dot { width:6px; height:6px; border-radius:50%; background:#10B981; box-shadow:0 0 0 3px rgba(16,185,129,.25); }
        .rw-dh-chart { position:relative; }
        .rw-dh-svg { width:100%; height:200px; display:block; }
        .rw-dh-xaxis { position:relative; height:16px; margin-top:6px; }
        .rw-dh-xaxis span { position:absolute; transform:translateX(-50%); font-size:9.5px; color:rgba(255,255,255,.4); font-weight:600; white-space:nowrap; font-variant-numeric:tabular-nums; }
        .rw-dh-xaxis span.is-today { color:#F59E0B; font-weight:900; }

        /* ═══════════ ACTIONABLES ═══════════ */
        .rw-dh-actions { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; margin-bottom:20px; }
        @media (max-width:900px){ .rw-dh-actions { grid-template-columns:repeat(2,1fr); } }
        @media (max-width:520px){ .rw-dh-actions { grid-template-columns:1fr; } }

        .rw-dh-action { position:relative; display:flex; align-items:center; gap:12px; padding:16px 18px; border-radius:16px; background:var(--bg); border:1px solid var(--line); text-decoration:none; transition:all 250ms cubic-bezier(0.16,1,0.3,1); overflow:hidden; }
        .rw-dh-action::before { content:''; position:absolute; top:0; inset-inline-start:0; width:4px; height:100%; background:currentColor; opacity:.5; }
        .rw-dh-action:hover { transform:translateY(-2px); box-shadow:0 12px 24px -10px rgba(45,75,126,.2); }
        .rw-dh-action-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:currentColor; color:#fff; opacity:.9; }
        .rw-dh-action-icon svg { width:18px; height:18px; color:#fff; }
        .rw-dh-action-body { flex:1; min-width:0; }
        .rw-dh-action-count { font-size:22px; font-weight:900; letter-spacing:-.02em; font-variant-numeric:tabular-nums; color:var(--ink); line-height:1; }
        .rw-dh-action-lbl { font-size:11.5px; font-weight:700; color:var(--sub); margin-top:4px; }
        .rw-dh-action-arrow { width:16px; height:16px; color:var(--sub); opacity:.4; transition:all 250ms ease; }
        [dir="rtl"] .rw-dh-action-arrow { transform:rotate(180deg); }
        .rw-dh-action:hover .rw-dh-action-arrow { opacity:1; transform:rotate(180deg) translateX(-2px); }

        .rw-dh-action--amber   { color:#D97706; }
        .rw-dh-action--blue    { color:#3B82F6; }
        .rw-dh-action--emerald { color:#10B981; }
        .rw-dh-action--purple  { color:#8B5CF6; }
        .rw-dh-action--red     { color:#EF4444; }

        /* ═══════════ TWO-COL ═══════════ */
        .rw-dh-two-col { display:grid; grid-template-columns:1.35fr 1fr; gap:16px; margin-bottom:16px; }
        @media (max-width:900px){ .rw-dh-two-col { grid-template-columns:1fr; } }

        .rw-dh-card { background:var(--bg); border:1px solid var(--line); border-radius:18px; padding:20px; }
        .rw-dh-card-head { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:16px; }
        .rw-dh-card-title { display:inline-flex; align-items:center; gap:10px; font-size:14px; font-weight:800; color:var(--ink); }
        .rw-dh-card-sub { font-size:11.5px; color:var(--sub); margin-top:3px; margin-inline-start:38px; }
        .rw-dh-icon-chip { width:28px; height:28px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; }
        .rw-dh-icon-chip svg { width:14px; height:14px; }
        .rw-dh-icon-chip--teal    { background:rgba(61,175,185,.12);  color:#3DAFB9; }
        .rw-dh-icon-chip--gold    { background:rgba(245,158,11,.12);  color:#D97706; }
        .rw-dh-icon-chip--emerald { background:rgba(16,185,129,.12);  color:#059669; }

        .rw-dh-live-tag { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:999px; background:rgba(16,185,129,.1); color:#059669; font-size:10.5px; font-weight:800; border:1px solid rgba(16,185,129,.2); }
        .rw-dh-live-dot { width:6px; height:6px; border-radius:50%; background:#10B981; box-shadow:0 0 0 3px rgba(16,185,129,.2); animation:rwDhPulse 2s ease-in-out infinite; }

        /* Feed */
        .rw-dh-feed { display:flex; flex-direction:column; gap:0; max-height:420px; overflow-y:auto; padding-inline-end:8px; }
        .rw-dh-feed::-webkit-scrollbar { width:6px; }
        .rw-dh-feed::-webkit-scrollbar-thumb { background:rgba(61,175,185,.3); border-radius:3px; }
        .rw-dh-feed-item { display:flex; gap:12px; padding:10px 0; border-bottom:1px dashed var(--line); }
        .rw-dh-feed-item:last-child { border-bottom:0; }
        .rw-dh-feed-dot { flex-shrink:0; width:32px; height:32px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; }
        .rw-dh-feed-dot svg { width:14px; height:14px; }
        .rw-dh-feed-dot--teal    { background:linear-gradient(135deg,#6BC8D2,#3DAFB9); box-shadow:0 4px 10px -3px rgba(61,175,185,.4); }
        .rw-dh-feed-dot--emerald { background:linear-gradient(135deg,#10B981,#059669); box-shadow:0 4px 10px -3px rgba(16,185,129,.4); }
        .rw-dh-feed-dot--amber   { background:linear-gradient(135deg,#F59E0B,#D97706); box-shadow:0 4px 10px -3px rgba(245,158,11,.4); }
        .rw-dh-feed-dot--blue    { background:linear-gradient(135deg,#3B82F6,#1D4ED8); box-shadow:0 4px 10px -3px rgba(59,130,246,.4); }
        .rw-dh-feed-dot--red     { background:linear-gradient(135deg,#EF4444,#DC2626); box-shadow:0 4px 10px -3px rgba(239,68,68,.4); }
        .rw-dh-feed-dot--purple  { background:linear-gradient(135deg,#A78BFA,#8B5CF6); box-shadow:0 4px 10px -3px rgba(139,92,246,.4); }
        .rw-dh-feed-dot--gray    { background:linear-gradient(135deg,#94A3B8,#64748B); }
        .rw-dh-feed-body { flex:1; min-width:0; }
        .rw-dh-feed-top { display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:3px; }
        .rw-dh-feed-title { font-size:12.5px; font-weight:700; color:var(--ink); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .rw-dh-feed-amount { flex-shrink:0; font-size:11px; font-weight:800; color:#3DAFB9; font-variant-numeric:tabular-nums; padding:2px 8px; border-radius:6px; background:rgba(61,175,185,.08); }
        .rw-dh-feed-sub { font-size:11px; color:var(--sub); margin-bottom:2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .rw-dh-feed-time { font-size:10px; color:var(--sub); opacity:.7; font-weight:600; }

        .rw-dh-empty { padding:40px 20px; text-align:center; color:var(--sub); }
        .rw-dh-empty-icon { font-size:32px; color:#3DAFB9; opacity:.4; margin-bottom:8px; }

        /* Star performer */
        .rw-dh-star { text-align:center; padding:20px 12px 8px; }
        .rw-dh-star-medal { width:72px; height:72px; margin:0 auto 14px; border-radius:50%; background:linear-gradient(135deg,#FCD34D 0%,#F59E0B 50%,#D97706 100%); display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 12px 32px -8px rgba(245,158,11,.5), inset 0 -3px 0 rgba(180,83,9,.3); }
        .rw-dh-star-medal svg { width:36px; height:36px; }
        .rw-dh-star-name { font-size:16px; font-weight:900; color:var(--ink); }
        .rw-dh-star-title { font-size:12px; color:var(--sub); margin-top:2px; }
        .rw-dh-star-stats { display:flex; align-items:center; justify-content:center; margin-top:20px; padding:16px 12px; border-radius:14px; background:linear-gradient(180deg,rgba(245,158,11,.06),rgba(217,119,6,.02)); border:1px solid rgba(245,158,11,.15); }
        .rw-dh-star-stat { flex:1; text-align:center; }
        .rw-dh-star-stat-val { font-size:20px; font-weight:900; color:#D97706; font-variant-numeric:tabular-nums; letter-spacing:-.01em; }
        .rw-dh-star-stat-lbl { font-size:10px; color:var(--sub); font-weight:700; margin-top:4px; letter-spacing:.08em; text-transform:uppercase; }
        .rw-dh-star-stat-divider { width:1px; height:32px; background:rgba(245,158,11,.2); margin:0 12px; }

        /* Heatmap */
        .rw-dh-heatmap { display:grid; grid-template-columns:repeat(15,1fr); gap:4px; padding:8px 0 16px; }
        .rw-dh-heat-cell { aspect-ratio:1; border-radius:4px; transition:all 200ms; border:1px solid rgba(61,175,185,.05); }
        .rw-dh-heat-cell:hover { transform:scale(1.15); border-color:#3DAFB9; z-index:2; position:relative; }
        .rw-dh-heat-cell.is-today { border:1.5px solid #F59E0B; box-shadow:0 0 0 2px rgba(245,158,11,.15); }
        .rw-dh-heat-legend { display:flex; align-items:center; justify-content:center; gap:6px; font-size:10.5px; color:var(--sub); font-weight:700; }
        .rw-dh-heat-swatch { width:12px; height:12px; border-radius:3px; }

        @media (max-width:640px){
            .rw-dh-hero { padding:22px; border-radius:18px; }
            .rw-dh-greet { font-size:22px; }
            .rw-dh-today { padding:14px; }
            .rw-dh-today-val { font-size:22px; }
            .rw-dh-today-divider { margin:0 12px; height:44px; }
            .rw-dh-svg { height:160px; }
            .rw-dh-action { padding:14px; }
            .rw-dh-action-count { font-size:18px; }
        }
    </style>
</div>
