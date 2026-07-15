<template>
    <div class="rw-bc" dir="rtl">
        <!-- ═══════ HOURGLASS + TIME ═══════ -->
        <div class="rw-bc-visual">
            <!-- Custom hourglass SVG -->
            <svg viewBox="0 0 200 260" class="rw-bc-hourglass" :class="{ 'rw-bc-live': state === 'live', 'rw-bc-ended': state === 'ended' || state === 'completed' }">
                <defs>
                    <linearGradient id="hgFrame" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#3DAFB9"/>
                        <stop offset="100%" stop-color="#2D4B7E"/>
                    </linearGradient>
                    <linearGradient id="hgSand" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#F8CA6E"/>
                        <stop offset="100%" stop-color="#E8A54C"/>
                    </linearGradient>
                    <linearGradient id="hgGlow" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#6BC8D2" stop-opacity="0"/>
                        <stop offset="50%" stop-color="#6BC8D2" stop-opacity="0.4"/>
                        <stop offset="100%" stop-color="#6BC8D2" stop-opacity="0"/>
                    </linearGradient>
                    <clipPath id="hgTopClip">
                        <path d="M40,20 L160,20 L160,25 C160,60 120,90 100,120 C80,90 40,60 40,25 Z"/>
                    </clipPath>
                    <clipPath id="hgBotClip">
                        <path d="M100,140 C120,170 160,200 160,235 L160,240 L40,240 L40,235 C40,200 80,170 100,140 Z"/>
                    </clipPath>
                </defs>

                <!-- Frame top cap -->
                <rect x="30" y="12" width="140" height="10" rx="4" fill="url(#hgFrame)"/>
                <!-- Top chamber outline -->
                <path d="M40,22 L160,22 C160,60 120,95 100,125 C80,95 40,60 40,22 Z"
                      fill="rgba(15,35,64,0.06)" stroke="url(#hgFrame)" stroke-width="2"/>
                <!-- Bottom chamber outline -->
                <path d="M100,135 C120,165 160,200 160,238 L40,238 C40,200 80,165 100,135 Z"
                      fill="rgba(15,35,64,0.06)" stroke="url(#hgFrame)" stroke-width="2"/>
                <!-- Frame bottom cap -->
                <rect x="30" y="238" width="140" height="10" rx="4" fill="url(#hgFrame)"/>

                <!-- Top sand — height shrinks as time passes -->
                <g clip-path="url(#hgTopClip)">
                    <rect x="40" y="20" width="120"
                          :height="topSandHeight"
                          fill="url(#hgSand)"
                          class="rw-sand-anim"/>
                </g>

                <!-- Bottom sand — height grows -->
                <g clip-path="url(#hgBotClip)">
                    <rect x="40" :y="240 - bottomSandHeight" width="120"
                          :height="bottomSandHeight"
                          fill="url(#hgSand)"
                          class="rw-sand-anim"/>
                </g>

                <!-- Falling stream (only while upcoming) -->
                <line v-if="state === 'upcoming' && !allDone"
                      x1="100" y1="125" x2="100" y2="140"
                      stroke="url(#hgSand)" stroke-width="2" class="rw-stream"/>

                <!-- Central pinch -->
                <path d="M92,125 L108,125 L104,138 L96,138 Z" fill="url(#hgFrame)"/>

                <!-- Live pulse glow -->
                <circle v-if="state === 'live'" cx="100" cy="130" r="55" fill="none"
                        stroke="#10B981" stroke-width="2" opacity="0.6" class="rw-live-pulse"/>
            </svg>

            <!-- Digital countdown numbers -->
            <div class="rw-bc-numbers" v-if="state === 'upcoming'">
                <div class="rw-bc-cell">
                    <span class="rw-bc-num">{{ pad(days) }}</span>
                    <span class="rw-bc-lbl">يوم</span>
                </div>
                <div class="rw-bc-sep">:</div>
                <div class="rw-bc-cell">
                    <span class="rw-bc-num">{{ pad(hours) }}</span>
                    <span class="rw-bc-lbl">ساعة</span>
                </div>
                <div class="rw-bc-sep">:</div>
                <div class="rw-bc-cell">
                    <span class="rw-bc-num">{{ pad(minutes) }}</span>
                    <span class="rw-bc-lbl">دقيقة</span>
                </div>
                <div class="rw-bc-sep">:</div>
                <div class="rw-bc-cell">
                    <span class="rw-bc-num rw-bc-num-flash">{{ pad(seconds) }}</span>
                    <span class="rw-bc-lbl">ثانية</span>
                </div>
            </div>
            <div v-else-if="state === 'live'" class="rw-bc-live-title">
                <span class="rw-bc-live-dot"></span>
                الجلسة تجري الآن — تنتهي بعد
                <strong>{{ liveRemaining }}</strong>
            </div>
            <div v-else-if="state === 'ended'" class="rw-bc-ended-title">
                انتهى وقت الجلسة
            </div>
            <div v-else-if="state === 'completed'" class="rw-bc-ended-title rw-bc-completed">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-5 h-5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                اكتملت الجلسة بنجاح
            </div>
        </div>

        <!-- ═══════ ACTION AREA ═══════ -->
        <div class="rw-bc-action">
            <template v-if="state === 'upcoming'">
                <div v-if="!meetingUrl" class="rw-bc-hint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                        <path stroke-linecap="round" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.48 0l-7.1 12.25A2 2 0 005 19z"/>
                    </svg>
                    بانتظار المستشار لإرسال رابط الجلسة
                </div>
                <div v-else class="rw-bc-ready">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-emerald-500">
                        <path stroke-linecap="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    رابط الجلسة جاهز — سيُفتح زر الانضمام عند بدء الوقت
                </div>
            </template>

            <a v-if="state === 'live' && meetingUrl"
               :href="meetingUrl" target="_blank" rel="noopener"
               class="rw-bc-join">
                <span class="rw-bc-join-glow"></span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="w-5 h-5">
                    <path stroke-linecap="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                انضم إلى الجلسة الآن
            </a>

            <div v-if="state === 'ended' || state === 'completed'" class="rw-bc-locked">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <path stroke-linecap="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                زر الانضمام مُغلق
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, onBeforeUnmount, ref } from 'vue';

const props = defineProps({
    startsAt:    { type: String, required: true }, // ISO
    endsAt:      { type: String, required: true },
    meetingUrl:  { type: String, default: null },
    initialState: { type: String, default: null },  // upcoming|live|ended|completed
    completedAt: { type: String, default: null },
});

const now = ref(Date.now());
let timer = null;

onMounted(() => {
    timer = setInterval(() => { now.value = Date.now(); }, 1000);
});
onBeforeUnmount(() => { if (timer) clearInterval(timer); });

const startMs = computed(() => new Date(props.startsAt).getTime());
const endMs   = computed(() => new Date(props.endsAt).getTime());
const totalMs = computed(() => Math.max(1, endMs.value - startMs.value));

const state = computed(() => {
    if (props.completedAt) return 'completed';
    if (now.value < startMs.value) return 'upcoming';
    if (now.value < endMs.value)   return 'live';
    return 'ended';
});

const secondsLeft = computed(() => Math.max(0, Math.floor((startMs.value - now.value) / 1000)));
const days    = computed(() => Math.floor(secondsLeft.value / 86400));
const hours   = computed(() => Math.floor((secondsLeft.value % 86400) / 3600));
const minutes = computed(() => Math.floor((secondsLeft.value % 3600) / 60));
const seconds = computed(() => secondsLeft.value % 60);

// Hourglass proportions
// Full-height chambers roughly 100 px each (from viewBox above)
// When upcoming: top = full → 0 based on elapsed fraction
const totalUpcomingMs = 3 * 86400 * 1000; // cap visual at 3 days for meaningful fill
const upcomingFraction = computed(() => {
    if (state.value !== 'upcoming') return state.value === 'live' || state.value === 'ended' || state.value === 'completed' ? 1 : 0;
    const elapsed = totalUpcomingMs - (startMs.value - now.value);
    return Math.min(1, Math.max(0, elapsed / totalUpcomingMs));
});
const topSandHeight    = computed(() => Math.max(0, 100 * (1 - upcomingFraction.value)));
const bottomSandHeight = computed(() => Math.max(0, 100 * upcomingFraction.value));
const allDone = computed(() => upcomingFraction.value >= 0.99);

// Live remaining formatting
const liveSecondsLeft = computed(() => Math.max(0, Math.floor((endMs.value - now.value) / 1000)));
const liveRemaining = computed(() => {
    const h = Math.floor(liveSecondsLeft.value / 3600);
    const m = Math.floor((liveSecondsLeft.value % 3600) / 60);
    const s = liveSecondsLeft.value % 60;
    return `${pad(h)}:${pad(m)}:${pad(s)}`;
});

function pad(n) { return String(n).padStart(2, '0'); }
</script>

<style scoped>
.rw-bc { display: flex; flex-direction: column; align-items: center; gap: 22px; padding: 28px 20px; border-radius: 24px;
         background: linear-gradient(160deg, rgba(61,175,185,0.06) 0%, rgba(45,75,126,0.04) 100%);
         border: 1px solid rgba(61,175,185,0.18); }
.rw-bc-visual { display: flex; flex-direction: column; align-items: center; gap: 16px; }
.rw-bc-hourglass { width: 140px; height: 182px; transition: transform 600ms ease; }
.rw-bc-hourglass.rw-bc-live { transform: rotate(0deg) scale(1.04); animation: bc-tilt 3s ease-in-out infinite; }
.rw-bc-hourglass.rw-bc-ended { opacity: 0.55; }
@keyframes bc-tilt { 0%,100% { transform: rotate(-1deg) scale(1.04); } 50% { transform: rotate(1deg) scale(1.04); } }
.rw-sand-anim { transition: y 800ms ease, height 800ms ease; }
.rw-stream { animation: bc-stream 0.9s linear infinite; }
@keyframes bc-stream { 0% { opacity: 0.4; } 50% { opacity: 1; } 100% { opacity: 0.4; } }
.rw-live-pulse { animation: bc-pulse 2s ease-out infinite; transform-origin: 100px 130px; }
@keyframes bc-pulse { 0% { transform: scale(1); opacity: 0.6; } 100% { transform: scale(1.5); opacity: 0; } }

.rw-bc-numbers { display: inline-flex; align-items: center; gap: 4px; font-variant-numeric: tabular-nums; }
.rw-bc-cell { display: flex; flex-direction: column; align-items: center; padding: 8px 10px; min-width: 54px;
              background: rgba(255,255,255,0.75); backdrop-filter: blur(6px); border: 1px solid rgba(61,175,185,0.22);
              border-radius: 12px; box-shadow: 0 2px 6px -2px rgba(45,75,126,0.15); }
:root.dark .rw-bc-cell { background: rgba(10,23,41,0.45); }
.rw-bc-num { font-size: 22px; font-weight: 900; color: #2D4B7E; line-height: 1; }
:root.dark .rw-bc-num { color: #6BC8D2; }
.rw-bc-num-flash { animation: bc-flash 1s ease-in-out infinite; }
@keyframes bc-flash { 0%,100% { opacity: 1; } 50% { opacity: 0.55; } }
.rw-bc-lbl { font-size: 9.5px; color: rgba(45,75,126,0.6); margin-top: 3px; letter-spacing: 0.5px; }
:root.dark .rw-bc-lbl { color: rgba(107,200,210,0.7); }
.rw-bc-sep { color: rgba(45,75,126,0.4); font-weight: 900; margin-bottom: 14px; }

.rw-bc-live-title { display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 800; color: #10B981; }
.rw-bc-live-title strong { font-variant-numeric: tabular-nums; color: #059669; }
.rw-bc-live-dot { width: 10px; height: 10px; border-radius: 50%; background: #10B981; box-shadow: 0 0 0 4px rgba(16,185,129,0.25); animation: bc-flash 1s ease-in-out infinite; }
.rw-bc-ended-title { display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 800; color: rgba(45,75,126,0.7); }
.rw-bc-completed { color: #059669; }

.rw-bc-action { width: 100%; max-width: 380px; }
.rw-bc-hint, .rw-bc-ready, .rw-bc-locked { display: inline-flex; align-items: center; gap: 8px; padding: 12px 18px;
    border-radius: 14px; font-size: 12.5px; font-weight: 700; width: 100%; justify-content: center; }
.rw-bc-hint  { background: rgba(245,158,11,0.10); color: #B45309; border: 1px solid rgba(245,158,11,0.25); }
.rw-bc-ready { background: rgba(16,185,129,0.08); color: #047857; border: 1px solid rgba(16,185,129,0.25); }
.rw-bc-locked { background: rgba(100,116,139,0.10); color: #64748B; border: 1px solid rgba(100,116,139,0.20); }
.rw-bc-join { position: relative; overflow: hidden; display: inline-flex; align-items: center; justify-content: center; gap: 10px;
              width: 100%; padding: 14px 20px; border-radius: 999px;
              background: linear-gradient(-90deg, #10B981, #059669, #10B981);
              background-size: 200% 100%; color: #fff; font-weight: 900; font-size: 14.5px; text-decoration: none;
              box-shadow: 0 12px 28px -8px rgba(16,185,129,0.6);
              animation: bc-shine 3s linear infinite, bc-bounce 2s ease-in-out infinite; }
@keyframes bc-shine { 0% { background-position: 0% 0; } 100% { background-position: 200% 0; } }
@keyframes bc-bounce { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-3px); } }
.rw-bc-join:hover { transform: scale(1.03); }
.rw-bc-join-glow { position: absolute; inset: -50%; background: radial-gradient(circle, rgba(255,255,255,0.35), transparent 60%); animation: bc-glow 4s linear infinite; }
@keyframes bc-glow { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>
