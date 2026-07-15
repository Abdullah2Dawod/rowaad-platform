<x-filament-panels::page>
    <div dir="rtl" x-data="bookingView({
            start: '{{ $startsIso }}',
            end:   '{{ $endsIso }}',
            completed: {{ $b->completed_at ? 'true' : 'false' }}
        })"
         class="space-y-6">

        {{-- ═════════ HEADER RIBBON ═════════ --}}
        <div class="rw-bv-hero">
            <div class="rw-bv-hero-glow"></div>
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <img src="{{ $consultant->avatar_url }}" alt="" class="w-16 h-16 rounded-full object-cover ring-4 ring-white/20"/>
                <div class="flex-1 min-w-0">
                    <div class="text-[10.5px] text-[#6BC8D2] tracking-widest font-black uppercase mb-1">استشارة</div>
                    <div class="text-[18px] font-black text-white truncate">
                        {{ $consultant->full_name_ar ?: $consultant->user->name }}
                        <span class="text-white/50 font-normal text-[13px]"> ← </span>
                        {{ $client->name }}
                    </div>
                    <div class="text-[12px] text-white/70 mt-0.5">{{ $consultant->professional_title }}</div>
                </div>
                <div class="text-left">
                    <div class="text-[10px] text-white/50 tracking-wider mb-1">المرجع</div>
                    <div class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white text-[12.5px] font-black tracking-wider inline-block">
                        {{ $b->reference }}
                    </div>
                    <div class="mt-2">
                        <span class="rw-bv-chip rw-bv-chip-{{ $liveState }}">
                            <span class="rw-bv-dot"></span>
                            @switch($liveState)
                                @case('upcoming') لم يحن دورها @break
                                @case('soon') اقتربت @break
                                @case('live') مباشرة الآن @break
                                @case('ended') انتهى الوقت @break
                                @case('completed') مكتملة @break
                                @case('cancelled') ملغاة @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═════════ MAIN GRID ═════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            {{-- LEFT: hourglass + details --}}
            <div class="lg:col-span-7 space-y-5">
                {{-- Hourglass card --}}
                <div class="rw-bv-card rw-bv-glass">
                    <div class="flex flex-col items-center gap-4">
                        <svg viewBox="0 0 200 260" class="rw-bv-hourglass"
                             :class="{ 'rw-bv-hg-live': state === 'live', 'rw-bv-hg-ended': state === 'ended' || state === 'completed' }">
                            <defs>
                                <linearGradient id="bvFrame" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#3DAFB9"/>
                                    <stop offset="100%" stop-color="#2D4B7E"/>
                                </linearGradient>
                                <linearGradient id="bvSand" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#F8CA6E"/>
                                    <stop offset="100%" stop-color="#E8A54C"/>
                                </linearGradient>
                                <clipPath id="bvTopClip"><path d="M40,20 L160,20 L160,25 C160,60 120,90 100,120 C80,90 40,60 40,25 Z"/></clipPath>
                                <clipPath id="bvBotClip"><path d="M100,140 C120,170 160,200 160,235 L160,240 L40,240 L40,235 C40,200 80,170 100,140 Z"/></clipPath>
                            </defs>
                            <rect x="30" y="12" width="140" height="10" rx="4" fill="url(#bvFrame)"/>
                            <path d="M40,22 L160,22 C160,60 120,95 100,125 C80,95 40,60 40,22 Z" fill="rgba(15,35,64,0.06)" stroke="url(#bvFrame)" stroke-width="2"/>
                            <path d="M100,135 C120,165 160,200 160,238 L40,238 C40,200 80,165 100,135 Z" fill="rgba(15,35,64,0.06)" stroke="url(#bvFrame)" stroke-width="2"/>
                            <rect x="30" y="238" width="140" height="10" rx="4" fill="url(#bvFrame)"/>
                            <g clip-path="url(#bvTopClip)">
                                <rect x="40" y="20" width="120" :height="topH" fill="url(#bvSand)" class="rw-bv-sand"/>
                            </g>
                            <g clip-path="url(#bvBotClip)">
                                <rect x="40" :y="240 - botH" width="120" :height="botH" fill="url(#bvSand)" class="rw-bv-sand"/>
                            </g>
                            <line x-show="state === 'upcoming'" x1="100" y1="125" x2="100" y2="140" stroke="url(#bvSand)" stroke-width="2" class="rw-bv-stream"/>
                            <path d="M92,125 L108,125 L104,138 L96,138 Z" fill="url(#bvFrame)"/>
                        </svg>

                        <div class="rw-bv-numbers" x-show="state === 'upcoming'">
                            <div class="rw-bv-cell"><span class="rw-bv-num" x-text="pad(days)"></span><span class="rw-bv-lbl">يوم</span></div>
                            <span class="rw-bv-sep">:</span>
                            <div class="rw-bv-cell"><span class="rw-bv-num" x-text="pad(hours)"></span><span class="rw-bv-lbl">ساعة</span></div>
                            <span class="rw-bv-sep">:</span>
                            <div class="rw-bv-cell"><span class="rw-bv-num" x-text="pad(mins)"></span><span class="rw-bv-lbl">دقيقة</span></div>
                            <span class="rw-bv-sep">:</span>
                            <div class="rw-bv-cell"><span class="rw-bv-num rw-bv-flash" x-text="pad(secs)"></span><span class="rw-bv-lbl">ثانية</span></div>
                        </div>
                        <div x-show="state === 'live'" class="rw-bv-live-title">
                            <span class="rw-bv-dot"></span>
                            الجلسة تجري الآن — تنتهي بعد <strong x-text="liveRemaining"></strong>
                        </div>
                        <div x-show="state === 'ended'" class="rw-bv-ended-title">انتهى وقت الجلسة</div>
                        <div x-show="state === 'completed'" class="rw-bv-ended-title rw-bv-completed">✓ اكتملت الجلسة بنجاح</div>

                        @if($b->meeting_url)
                            <a x-show="state === 'live'" href="{{ $b->meeting_url }}" target="_blank" rel="noopener" class="rw-bv-join">
                                🎥 انضم إلى الجلسة الآن
                            </a>
                            <div x-show="state === 'upcoming'" class="rw-bv-ready">
                                ✓ رابط الجلسة جاهز — سيُفتح زر الانضمام عند بدء الوقت
                            </div>
                            <div x-show="state === 'ended' || state === 'completed'" class="rw-bv-locked">🔒 زر الانضمام مُغلق</div>
                        @else
                            <div class="rw-bv-hint">⏳ بانتظار المستشار لإرسال رابط الجلسة</div>
                        @endif
                    </div>
                </div>

                {{-- Meeting link card (visible to consultant only if empty/manageable) --}}
                @if($b->meeting_url)
                    <div class="rw-bv-card">
                        <div class="rw-bv-section-head">
                            <span class="rw-bv-bar"></span>
                            <h3>رابط الجلسة</h3>
                        </div>
                        <div class="rw-bv-link-box" dir="ltr">
                            <input type="text" readonly value="{{ $b->meeting_url }}" class="rw-bv-link-input"/>
                            <a href="{{ $b->meeting_url }}" target="_blank" class="rw-bv-link-open">فتح ↗</a>
                        </div>
                        @if($canManage)
                            <div class="rw-bv-hint-note">اضغط زر "تحديث رابط الجلسة" في الأعلى لتغيير الرابط.</div>
                        @endif
                    </div>
                @endif

                {{-- Status timeline --}}
                <div class="rw-bv-card">
                    <div class="rw-bv-section-head">
                        <span class="rw-bv-bar"></span>
                        <h3>مراحل الحجز</h3>
                    </div>
                    <ol class="rw-bv-timeline">
                        <li class="rw-bv-step rw-bv-step-done">
                            <span class="rw-bv-step-dot">✓</span>
                            <div><div class="rw-bv-step-title">تم استلام الحجز</div><div class="rw-bv-step-time">{{ $b->created_at->isoFormat('D MMM YYYY · h:mm a') }}</div></div>
                        </li>
                        <li class="rw-bv-step {{ $b->paid_at ? 'rw-bv-step-done' : 'rw-bv-step-pending' }}">
                            <span class="rw-bv-step-dot">{{ $b->paid_at ? '✓' : '2' }}</span>
                            <div><div class="rw-bv-step-title">تم الدفع</div><div class="rw-bv-step-time">{{ $b->paid_at?->isoFormat('D MMM YYYY · h:mm a') ?? 'بانتظار الدفع' }}</div></div>
                        </li>
                        <li class="rw-bv-step {{ $b->confirmed_at ? 'rw-bv-step-done' : 'rw-bv-step-pending' }}">
                            <span class="rw-bv-step-dot">{{ $b->confirmed_at ? '✓' : '3' }}</span>
                            <div><div class="rw-bv-step-title">تأكيد المستشار</div><div class="rw-bv-step-time">{{ $b->confirmed_at?->isoFormat('D MMM YYYY · h:mm a') ?? 'بانتظار موافقة المستشار وإرسال الرابط' }}</div></div>
                        </li>
                        <li class="rw-bv-step {{ $b->completed_at ? 'rw-bv-step-done' : 'rw-bv-step-pending' }}">
                            <span class="rw-bv-step-dot">{{ $b->completed_at ? '✓' : '4' }}</span>
                            <div><div class="rw-bv-step-title">إتمام الجلسة</div><div class="rw-bv-step-time">{{ $b->completed_at?->isoFormat('D MMM YYYY · h:mm a') ?? 'يكتمل تلقائياً بعد انتهاء الوقت' }}</div></div>
                        </li>
                    </ol>
                </div>
            </div>

            {{-- RIGHT: details + parties --}}
            <div class="lg:col-span-5 space-y-5">
                {{-- Details --}}
                <div class="rw-bv-card">
                    <div class="rw-bv-section-head">
                        <span class="rw-bv-bar"></span>
                        <h3>تفاصيل الجلسة</h3>
                    </div>
                    <dl class="rw-bv-dl">
                        <div><dt>التاريخ</dt><dd>{{ $b->preferred_date->isoFormat('dddd, D MMMM YYYY') }}</dd></div>
                        <div><dt>الوقت</dt><dd>{{ \Carbon\Carbon::parse($b->preferred_time)->format('h:i a') }}</dd></div>
                        <div><dt>المدة</dt><dd>{{ $b->duration_min }} دقيقة</dd></div>
                        <div><dt>القيمة</dt><dd class="rw-bv-price">{{ number_format($b->amount, 0) }} ر.س</dd></div>
                        <div><dt>حصة المستشار</dt><dd>{{ number_format($b->consultant_share, 0) }} ر.س</dd></div>
                        <div><dt>حصة المنصة</dt><dd>{{ number_format($b->platform_share, 0) }} ر.س</dd></div>
                        <div><dt>الزكاة</dt><dd>{{ number_format($b->zakat_amount, 0) }} ر.س</dd></div>
                        @if($b->service_title)
                            <div class="rw-bv-full"><dt>الخدمة</dt><dd>{{ $b->service_title }}</dd></div>
                        @endif
                        @if($b->notes)
                            <div class="rw-bv-full"><dt>ملاحظات العميل</dt><dd class="rw-bv-notes">{{ $b->notes }}</dd></div>
                        @endif
                    </dl>
                </div>

                {{-- Client card --}}
                <div class="rw-bv-card">
                    <div class="rw-bv-section-head">
                        <span class="rw-bv-bar"></span>
                        <h3>العميل</h3>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="rw-bv-avatar-letter">{{ mb_substr($client->name, 0, 1) }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13.5px] font-black text-gray-900 dark:text-white truncate">{{ $client->name }}</div>
                            <div class="text-[12px] text-gray-500 truncate">{{ $client->email }}</div>
                            @if($client->phone)
                                <div class="text-[12px] text-gray-500 mt-0.5" dir="ltr">{{ $client->phone }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .rw-bv-hero { position: relative; overflow: hidden; padding: 22px 24px; border-radius: 22px;
                       background: linear-gradient(135deg, #0A1729 0%, #122440 50%, #1A2F50 100%);
                       box-shadow: 0 20px 40px -20px rgba(45,75,126,0.4); }
        .rw-bv-hero-glow { position: absolute; top: -80px; right: -80px; width: 300px; height: 300px; border-radius: 50%;
                            background: radial-gradient(circle, rgba(61,175,185,0.30), transparent 70%); }

        .rw-bv-card { padding: 22px; border-radius: 18px; background: white; border: 1px solid rgba(15,23,42,0.08);
                       box-shadow: 0 2px 8px -2px rgba(15,23,42,0.05); }
        .dark .rw-bv-card { background: rgb(31,41,55); border-color: rgba(255,255,255,0.08); }
        .rw-bv-glass { background: linear-gradient(160deg, rgba(61,175,185,0.06), rgba(45,75,126,0.04));
                        border-color: rgba(61,175,185,0.20); }

        .rw-bv-section-head { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
        .rw-bv-section-head h3 { font-size: 15px; font-weight: 900; color: #1F2937; margin: 0; }
        .dark .rw-bv-section-head h3 { color: #F9FAFB; }
        .rw-bv-bar { width: 4px; height: 20px; border-radius: 4px; background: linear-gradient(180deg, #3DAFB9, #2D4B7E); }

        .rw-bv-chip { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px;
                       font-size: 10.5px; font-weight: 900; }
        .rw-bv-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; animation: bv-blink 1.4s infinite; }
        .rw-bv-chip-upcoming { background: rgba(59,130,246,0.15); color: #2563EB; }
        .rw-bv-chip-soon { background: rgba(245,158,11,0.15); color: #B45309; }
        .rw-bv-chip-live { background: rgba(16,185,129,0.15); color: #059669; }
        .rw-bv-chip-ended { background: rgba(245,158,11,0.15); color: #B45309; }
        .rw-bv-chip-completed { background: rgba(107,114,128,0.15); color: #4B5563; }
        .rw-bv-chip-cancelled { background: rgba(239,68,68,0.15); color: #DC2626; }
        @keyframes bv-blink { 0%,100% { opacity: 1; } 50% { opacity: 0.35; } }

        .rw-bv-hourglass { width: 140px; height: 182px; transition: transform 600ms ease; }
        .rw-bv-hg-live { animation: bv-tilt 3s ease-in-out infinite; }
        .rw-bv-hg-ended { opacity: 0.55; }
        @keyframes bv-tilt { 0%,100% { transform: rotate(-1deg); } 50% { transform: rotate(1deg); } }
        .rw-bv-sand { transition: y 800ms ease, height 800ms ease; }
        .rw-bv-stream { animation: bv-stream 0.9s linear infinite; }
        @keyframes bv-stream { 0%,100% { opacity: 0.4; } 50% { opacity: 1; } }

        .rw-bv-numbers { display: inline-flex; align-items: center; gap: 4px; font-variant-numeric: tabular-nums; }
        .rw-bv-cell { display: flex; flex-direction: column; align-items: center; padding: 8px 12px; min-width: 56px;
                       background: rgba(255,255,255,0.85); backdrop-filter: blur(6px); border: 1px solid rgba(61,175,185,0.22);
                       border-radius: 12px; box-shadow: 0 2px 6px -2px rgba(45,75,126,0.15); }
        .dark .rw-bv-cell { background: rgba(10,23,41,0.5); }
        .rw-bv-num { font-size: 22px; font-weight: 900; color: #2D4B7E; line-height: 1; }
        .dark .rw-bv-num { color: #6BC8D2; }
        .rw-bv-lbl { font-size: 9.5px; color: rgba(45,75,126,0.6); margin-top: 3px; }
        .dark .rw-bv-lbl { color: rgba(107,200,210,0.7); }
        .rw-bv-sep { font-size: 20px; font-weight: 900; color: rgba(45,75,126,0.4); margin-bottom: 14px; }
        .rw-bv-flash { animation: bv-blink 1s ease-in-out infinite; }

        .rw-bv-live-title { display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 800; color: #059669; }
        .rw-bv-ended-title { font-size: 13.5px; font-weight: 800; color: rgba(75,85,99,0.75); }
        .rw-bv-completed { color: #059669; }
        .rw-bv-join { display: inline-flex; align-items: center; justify-content: center; gap: 10px; width: 100%;
                       max-width: 380px; padding: 14px 20px; border-radius: 999px;
                       background: linear-gradient(-90deg, #10B981, #059669, #10B981); background-size: 200% 100%;
                       color: white; font-weight: 900; font-size: 14.5px; text-decoration: none;
                       box-shadow: 0 12px 28px -8px rgba(16,185,129,0.6);
                       animation: bv-shine 3s linear infinite, bv-bounce 2s ease-in-out infinite; }
        @keyframes bv-shine { to { background-position: 200% 0; } }
        @keyframes bv-bounce { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-3px); } }
        .rw-bv-ready, .rw-bv-hint, .rw-bv-locked { display: inline-flex; align-items: center; gap: 8px; padding: 12px 18px;
            border-radius: 14px; font-size: 12.5px; font-weight: 700; width: 100%; max-width: 380px; justify-content: center; }
        .rw-bv-ready { background: rgba(16,185,129,0.08); color: #047857; border: 1px solid rgba(16,185,129,0.25); }
        .rw-bv-hint  { background: rgba(245,158,11,0.10); color: #B45309; border: 1px solid rgba(245,158,11,0.25); }
        .rw-bv-locked { background: rgba(100,116,139,0.10); color: #64748B; border: 1px solid rgba(100,116,139,0.20); }

        .rw-bv-link-box { display: flex; gap: 8px; padding: 6px; background: #F3F4F6; border-radius: 12px; border: 1px solid rgba(15,23,42,0.08); }
        .dark .rw-bv-link-box { background: rgba(0,0,0,0.3); }
        .rw-bv-link-input { flex: 1; background: transparent; border: none; padding: 8px 10px; font-family: monospace; font-size: 12px; color: #374151; }
        .dark .rw-bv-link-input { color: #E5E7EB; }
        .rw-bv-link-input:focus { outline: none; }
        .rw-bv-link-open { display: inline-flex; align-items: center; padding: 8px 14px; border-radius: 8px;
            background: linear-gradient(-90deg,#3DAFB9,#2D4B7E); color: white; font-weight: 800; font-size: 12px; text-decoration: none; }
        .rw-bv-hint-note { margin-top: 8px; font-size: 11px; color: #6B7280; }

        .rw-bv-timeline { position: relative; list-style: none; padding: 0; margin: 0; padding-inline-start: 20px; }
        .rw-bv-timeline::before { content: ''; position: absolute; top: 8px; bottom: 8px; inset-inline-start: 8px; width: 2px;
            background: linear-gradient(180deg, #3DAFB9, rgba(61,175,185,0.15)); }
        .rw-bv-step { position: relative; display: flex; align-items: flex-start; gap: 12px; padding: 8px 0; }
        .rw-bv-step-dot { position: absolute; inset-inline-start: -20px; top: 8px; width: 24px; height: 24px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900;
            background: white; border: 2px solid rgba(15,23,42,0.15); color: #6B7280; }
        .dark .rw-bv-step-dot { background: rgb(31,41,55); }
        .rw-bv-step-done .rw-bv-step-dot { background: #10B981; border-color: #10B981; color: white; }
        .rw-bv-step-title { font-size: 12.5px; font-weight: 900; color: #1F2937; }
        .dark .rw-bv-step-title { color: #F9FAFB; }
        .rw-bv-step-pending .rw-bv-step-title { color: #9CA3AF; }
        .rw-bv-step-time { font-size: 10.5px; color: #6B7280; margin-top: 2px; }

        .rw-bv-dl { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .rw-bv-dl > div { padding: 10px 12px; border-radius: 10px; background: #F9FAFB; border: 1px solid rgba(15,23,42,0.06); }
        .dark .rw-bv-dl > div { background: rgba(0,0,0,0.2); }
        .rw-bv-dl dt { font-size: 10.5px; color: #6B7280; margin-bottom: 3px; font-weight: 700; }
        .rw-bv-dl dd { font-size: 12.5px; font-weight: 900; color: #111827; margin: 0; }
        .dark .rw-bv-dl dd { color: #F9FAFB; }
        .rw-bv-full { grid-column: span 2; }
        .rw-bv-price { background: linear-gradient(-90deg,#3DAFB9,#2D4B7E); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .rw-bv-notes { font-size: 12px !important; font-weight: 500 !important; line-height: 1.7; color: #4B5563 !important; }
        .dark .rw-bv-notes { color: #D1D5DB !important; }

        .rw-bv-avatar-letter { width: 46px; height: 46px; border-radius: 50%;
            background: linear-gradient(135deg,#3DAFB9,#2D4B7E); color: white; font-weight: 900; font-size: 18px;
            display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
    </style>
    @endpush

    @push('scripts')
    <script>
        function bookingView({ start, end, completed }) {
            return {
                startMs: new Date(start).getTime(),
                endMs:   new Date(end).getTime(),
                completed,
                now: Date.now(),
                init() { setInterval(() => this.now = Date.now(), 1000); },
                get state() {
                    if (this.completed) return 'completed';
                    if (this.now < this.startMs) return 'upcoming';
                    if (this.now < this.endMs) return 'live';
                    return 'ended';
                },
                get secsLeft() { return Math.max(0, Math.floor((this.startMs - this.now)/1000)); },
                get days()  { return Math.floor(this.secsLeft/86400); },
                get hours() { return Math.floor((this.secsLeft%86400)/3600); },
                get mins()  { return Math.floor((this.secsLeft%3600)/60); },
                get secs()  { return this.secsLeft%60; },
                get frac() {
                    const cap = 3*86400*1000;
                    const elapsed = cap - (this.startMs - this.now);
                    return Math.min(1, Math.max(0, elapsed/cap));
                },
                get topH() { return this.state !== 'upcoming' ? 0 : Math.max(0, 100*(1-this.frac)); },
                get botH() { return this.state !== 'upcoming' ? 100 : Math.max(0, 100*this.frac); },
                get liveRemaining() {
                    const s = Math.max(0, Math.floor((this.endMs - this.now)/1000));
                    const h = Math.floor(s/3600), m = Math.floor((s%3600)/60), sec = s%60;
                    return `${this.pad(h)}:${this.pad(m)}:${this.pad(sec)}`;
                },
                pad(n) { return String(n).padStart(2,'0'); }
            }
        }
    </script>
    @endpush
</x-filament-panels::page>
