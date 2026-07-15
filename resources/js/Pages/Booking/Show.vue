<template>
    <MainLayout>
        <Head :title="`حجز ${booking.reference}`" />

        <section class="relative pt-28 lg:pt-32 pb-16 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-20 right-[10%] w-[420px] h-[420px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.14), transparent 70%);"></div>
                <div class="absolute bottom-16 left-[10%] w-[360px] h-[360px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.10), transparent 70%); animation-delay: 3s;"></div>
                <div class="absolute inset-0 grid-pattern opacity-30"></div>
            </div>

            <div class="relative max-w-[1100px] mx-auto px-5 lg:px-10">
                <Link href="/bookings" class="inline-flex items-center gap-2 text-[12px] text-ink-body hover:text-[#3DAFB9] font-bold mb-6 transition-colors">
                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    كل حجوزاتي
                </Link>

                <!-- Header ribbon -->
                <div class="rounded-[1.5rem] bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50] p-6 lg:p-8 shadow-card-hover relative overflow-hidden mb-6">
                    <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full aurora-drift"
                         style="background: radial-gradient(circle, rgba(61,175,185,0.25), transparent 70%);"></div>
                    <div class="relative flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                        <img :src="booking.consultant.avatar" :alt="booking.consultant.name"
                             class="w-20 h-20 rounded-full object-cover ring-4 ring-white/20 shrink-0" />
                        <div class="flex-1 min-w-0">
                            <div class="text-[10.5px] text-[#6BC8D2] tracking-widest font-black uppercase mb-1">استشارة مع</div>
                            <h1 class="text-xl lg:text-2xl font-black text-white truncate">{{ booking.consultant.name }}</h1>
                            <p class="text-[13px] text-white/70 mt-1">{{ booking.consultant.title }}</p>
                        </div>
                        <div class="sm:text-left">
                            <div class="text-[10.5px] text-white/50 tracking-wider mb-1">مرجع الحجز</div>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white text-[13px] font-black tracking-wider">
                                {{ booking.reference }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main grid -->
                <div class="grid grid-cols-12 gap-6">
                    <!-- LEFT: countdown + details -->
                    <div class="col-span-12 lg:col-span-7 space-y-6">
                        <!-- Session-ended lock banner (replaces countdown when session finished) -->
                        <div v-if="sessionEnded" class="rounded-[1.5rem] overflow-hidden border shadow-card"
                             :class="booking.status === 'cancelled'
                                        ? 'bg-gradient-to-br from-red-50 to-white dark:from-[#3F1414] dark:to-[#1F0808] border-red-200 dark:border-red-500/30'
                                        : 'bg-gradient-to-br from-emerald-50 to-white dark:from-[#0F2A1E] dark:to-[#0A1F17] border-emerald-200 dark:border-emerald-500/30'">
                            <div class="p-7 lg:p-9 text-center">
                                <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4"
                                     :class="booking.status === 'cancelled' ? 'bg-red-100 dark:bg-red-500/20' : 'bg-emerald-100 dark:bg-emerald-500/20'">
                                    <svg v-if="booking.status !== 'cancelled'" class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <svg v-else class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <h3 class="text-[18px] font-black mb-2"
                                    :class="booking.status === 'cancelled' ? 'text-red-800 dark:text-red-300' : 'text-emerald-800 dark:text-emerald-300'">
                                    {{ booking.status === 'cancelled' ? 'تم إلغاء الحجز' : 'اكتملت الجلسة' }}
                                </h3>
                                <p class="text-[13px] leading-relaxed max-w-md mx-auto"
                                   :class="booking.status === 'cancelled' ? 'text-red-700/80 dark:text-red-300/80' : 'text-emerald-700/80 dark:text-emerald-300/80'">
                                    {{ booking.status === 'cancelled'
                                       ? 'تم إلغاء هذا الحجز. لا يمكن الانضمام أو التعديل.'
                                       : (booking.meeting_url
                                          ? 'انتهى وقت الاستشارة. رابط الجلسة مغلق ولا يمكن استخدامه بعد الآن.'
                                          : 'انتهى وقت الاستشارة ولم يتم إرسال رابط الجلسة. الحجز مقفل نهائياً.') }}
                                </p>

                                <!-- Locked join button (visual only, disabled) -->
                                <div class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-200/60 dark:bg-slate-700/40 text-slate-500 dark:text-slate-400 text-[13px] font-black cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    الحجز مغلق
                                </div>
                            </div>
                        </div>

                        <!-- Countdown hourglass (only while active) -->
                        <BookingCountdown v-else
                            :starts-at="booking.starts_at_iso"
                            :ends-at="booking.ends_at_iso"
                            :meeting-url="booking.meeting_url"
                            :completed-at="booking.completed_at"
                            :initial-state="booking.live_state"
                        />

                        <!-- Session details card -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h3 class="text-[15px] font-black text-ink">تفاصيل الجلسة</h3>
                            </div>
                            <dl class="grid grid-cols-2 gap-4 text-[13px]">
                                <div>
                                    <dt class="text-[11px] text-ink-muted mb-1">التاريخ</dt>
                                    <dd class="font-black text-ink">{{ formatDate(booking.preferred_date) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] text-ink-muted mb-1">الوقت</dt>
                                    <dd class="font-black text-ink">{{ formatTime(booking.preferred_time) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] text-ink-muted mb-1">المدة</dt>
                                    <dd class="font-black text-ink">{{ booking.duration_min }} دقيقة</dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] text-ink-muted mb-1">القيمة</dt>
                                    <dd class="font-black text-gradient-brand">{{ formatMoney(booking.amount) }} ر.س</dd>
                                </div>
                                <div v-if="booking.service_title" class="col-span-2">
                                    <dt class="text-[11px] text-ink-muted mb-1">الخدمة</dt>
                                    <dd class="font-bold text-ink-body">{{ booking.service_title }}</dd>
                                </div>
                                <div v-if="booking.notes" class="col-span-2">
                                    <dt class="text-[11px] text-ink-muted mb-1">ملاحظاتك</dt>
                                    <dd class="text-[12.5px] text-ink-body leading-relaxed p-3 rounded-xl bg-canvas border border-soft">{{ booking.notes }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Status timeline -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h3 class="text-[15px] font-black text-ink">مراحل الحجز</h3>
                            </div>
                            <ol class="relative pr-4 rtl:pr-0 rtl:pl-4 space-y-4">
                                <li class="flex items-start gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black shrink-0">✓</span>
                                    <div class="flex-1">
                                        <div class="text-[12.5px] font-black text-ink">تم استلام الحجز</div>
                                        <div class="text-[11px] text-ink-muted mt-0.5">أنشأت طلب الحجز</div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span :class="['w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black shrink-0',
                                        booking.paid_at ? 'bg-emerald-500 text-white' : 'bg-canvas border border-soft text-ink-muted']">
                                        <template v-if="booking.paid_at">✓</template>
                                        <template v-else>2</template>
                                    </span>
                                    <div class="flex-1">
                                        <div :class="['text-[12.5px] font-black', booking.paid_at ? 'text-ink' : 'text-ink-muted']">تم الدفع</div>
                                        <div class="text-[11px] text-ink-muted mt-0.5">{{ booking.paid_at ? formatDateTime(booking.paid_at) : 'بانتظار الدفع' }}</div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span :class="['w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black shrink-0',
                                        booking.confirmed_at ? 'bg-emerald-500 text-white' : 'bg-canvas border border-soft text-ink-muted']">
                                        <template v-if="booking.confirmed_at">✓</template>
                                        <template v-else>3</template>
                                    </span>
                                    <div class="flex-1">
                                        <div :class="['text-[12.5px] font-black', booking.confirmed_at ? 'text-ink' : 'text-ink-muted']">تأكيد المستشار</div>
                                        <div class="text-[11px] text-ink-muted mt-0.5">{{ booking.confirmed_at ? formatDateTime(booking.confirmed_at) : 'بانتظار موافقة المستشار وإرسال الرابط' }}</div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span :class="['w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black shrink-0',
                                        sessionEnded ? 'bg-emerald-500 text-white' : 'bg-canvas border border-soft text-ink-muted']">
                                        <template v-if="sessionEnded">✓</template>
                                        <template v-else>4</template>
                                    </span>
                                    <div class="flex-1">
                                        <div :class="['text-[12.5px] font-black', sessionEnded ? 'text-ink' : 'text-ink-muted']">إتمام الجلسة</div>
                                        <div class="text-[11px] text-ink-muted mt-0.5">{{ booking.completed_at ? formatDateTime(booking.completed_at) : (sessionEnded ? 'اكتملت الجلسة' : 'ستكتمل تلقائياً بعد انتهاء الوقت') }}</div>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <!-- RIGHT: participant + tips -->
                    <div class="col-span-12 lg:col-span-5 space-y-6">
                        <!-- The other participant card -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card">
                            <div class="text-[10.5px] text-[#3DAFB9] tracking-widest font-black uppercase mb-3">
                                {{ viewer === 'consultant' ? 'العميل' : 'المستشار' }}
                            </div>
                            <div class="flex items-center gap-4">
                                <img v-if="viewer !== 'consultant'"
                                     :src="booking.consultant.avatar" :alt="booking.consultant.name"
                                     class="w-14 h-14 rounded-full object-cover" />
                                <div v-else class="w-14 h-14 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] text-white flex items-center justify-center text-[16px] font-black">
                                    {{ (booking.client?.name || '?').charAt(0) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[14px] font-black text-ink truncate">
                                        {{ viewer === 'consultant' ? booking.client.name : booking.consultant.name }}
                                    </div>
                                    <div class="text-[12px] text-ink-body truncate">
                                        {{ viewer === 'consultant' ? booking.client.email : booking.consultant.title }}
                                    </div>
                                </div>
                            </div>
                            <Link v-if="viewer !== 'consultant'" :href="`/consultants/${booking.consultant.id}`"
                                  class="mt-4 inline-flex items-center justify-center gap-1.5 w-full py-2.5 rounded-full text-[12.5px] font-black text-[#3DAFB9] border border-[#3DAFB9]/30 hover:bg-[#3DAFB9]/8 transition-colors">
                                عرض ملف المستشار
                                <svg class="w-3 h-3 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
                            </Link>
                        </div>

                        <!-- Tips card -->
                        <div class="rounded-[1.5rem] bg-gradient-to-br from-[#EAF6F7] to-white dark:from-[#0F2340]/40 dark:to-[#122440]/30 border border-[#3DAFB9]/20 p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h4 class="text-[13.5px] font-black text-[#2D4B7E] dark:text-[#6BC8D2]">نصائح قبل الجلسة</h4>
                            </div>
                            <ul class="space-y-2 text-[12px] text-ink-body">
                                <li class="flex items-start gap-2">
                                    <span class="mt-1.5 w-1.5 h-1.5 shrink-0 rounded-full bg-[#3DAFB9]"></span>
                                    <span>سنُرسل تذكيراً بالبريد قبل الموعد بـ 10 دقائق</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1.5 w-1.5 h-1.5 shrink-0 rounded-full bg-[#3DAFB9]"></span>
                                    <span>تأكد من اتصال إنترنت مستقر وميكروفون يعمل</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1.5 w-1.5 h-1.5 shrink-0 rounded-full bg-[#3DAFB9]"></span>
                                    <span>حضّر أسئلتك مسبقاً لاستثمار الوقت بأفضل شكل</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Rating widget (shown after session ended, only for the client) -->
                        <div v-if="sessionEnded && viewer === 'user'" class="rounded-[1.5rem] bg-gradient-to-br from-[#FDF6EC] to-white dark:from-[#0F2340]/40 dark:to-[#122440]/30 border border-amber-400/30 p-6 shadow-card">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <h4 class="text-[14px] font-black text-ink">قيّم استشارتك</h4>
                            </div>
                            <p class="text-[12px] text-ink-body leading-relaxed mb-4">
                                رأيك يساعد المستشارين على التحسّن ويوجّه المستخدمين للاختيار الأنسب.
                            </p>
                            <ConsultantRating
                                :consultant-id="booking.consultant.id"
                                :consultant-name="booking.consultant.name"
                                :initial-avg="0"
                                :initial-count="0"
                            />
                        </div>

                        <!-- Support -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6">
                            <div class="text-[12.5px] font-black text-ink mb-1">تحتاج مساعدة؟</div>
                            <p class="text-[11.5px] text-ink-body leading-relaxed mb-3">
                                لأي استفسار حول الحجز، فريق الدعم متاح على مدار الساعة.
                            </p>
                            <Link href="/contact" class="inline-flex items-center gap-1.5 text-[12px] font-black text-[#3DAFB9] hover:underline">
                                تواصل معنا
                                <svg class="w-3 h-3 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import BookingCountdown from '@/Components/BookingCountdown.vue';
import ConsultantRating from '@/Components/ConsultantRating.vue';

const props = defineProps({
    booking: Object,
    viewer:  { type: String, default: 'user' },
});

// Live "session finished" — either explicitly completed OR past ends_at_iso
const now = ref(Date.now());
let tick = null;
onMounted(() => { tick = setInterval(() => now.value = Date.now(), 1000); });
onBeforeUnmount(() => { if (tick) clearInterval(tick); });

const sessionEnded = computed(() => {
    if (props.booking.completed_at) return true;
    if (props.booking.status === 'cancelled') return true;
    return now.value >= new Date(props.booking.ends_at_iso).getTime();
});

const formatDate = (v) => new Date(v).toLocaleDateString('ar-SA', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
const formatTime = (v) => {
    const [h, m] = String(v).split(':');
    const d = new Date(); d.setHours(+h, +m, 0);
    return d.toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit', hour12: true });
};
const formatDateTime = (iso) => new Date(iso).toLocaleString('ar-SA', { dateStyle: 'medium', timeStyle: 'short' });
const formatMoney = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));
</script>
