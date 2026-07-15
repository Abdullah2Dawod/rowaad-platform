<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-24 bg-paper">
            <div class="max-w-4xl mx-auto px-6 lg:px-10">
                <h1 class="text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-2">حجوزاتي</h1>
                <p class="text-[13px] text-ink-body mb-8">جميع الاستشارات التي حجزتها.</p>

                <div v-if="!bookings.data.length" class="text-center py-16 rounded-[1.5rem] bg-elevated border border-soft">
                    <div class="w-16 h-16 rounded-full bg-[#3DAFB9]/10 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </div>
                    <p class="text-ink-body mb-4">لم تحجز أي استشارة بعد.</p>
                    <Link href="/consultants" class="inline-flex px-5 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-105 transition-transform">
                        تصفّح المستشارين
                    </Link>
                </div>

                <div v-else class="space-y-4">
                    <div v-for="b in bookings.data" :key="b.id"
                         class="group relative rounded-[1.25rem] bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover transition-all overflow-hidden">
                        <!-- Live pulse strip -->
                        <div v-if="b.live_state === 'live'"
                             class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-l from-emerald-500 via-emerald-400 to-emerald-500 animate-pulse"></div>

                        <div class="flex flex-col sm:flex-row items-stretch gap-4 p-5">
                            <img :src="b.consultant.avatar" :alt="b.consultant.name"
                                 class="w-14 h-14 rounded-full object-cover shrink-0" />

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <div class="text-[14px] font-black text-ink truncate">{{ b.consultant.name }}</div>
                                    <span :class="['inline-flex items-center gap-1 text-[9.5px] font-black px-2 py-0.5 rounded-full', liveStateStyle(b.live_state)]">
                                        <span v-if="b.live_state === 'live'" class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                                        {{ liveStateLabel(b.live_state) }}
                                    </span>
                                </div>
                                <div class="text-[11.5px] text-ink-body">{{ b.consultant.title }}</div>
                                <div class="text-[11.5px] text-ink-muted mt-1">
                                    <svg class="w-3 h-3 inline align-[-2px] me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    {{ b.preferred_date }} · {{ formatTime(b.preferred_time) }} · {{ b.duration_min }} دقيقة
                                </div>
                                <div class="text-[10px] text-ink-muted/70 mt-1 font-mono" dir="ltr">{{ b.reference }}</div>
                            </div>

                            <div class="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-2 shrink-0 sm:min-w-[160px]">
                                <div class="text-right">
                                    <div class="text-[15px] font-black text-ink">{{ formatPrice(b.amount) }}<span class="text-[10px] text-ink-muted mr-1">ر.س</span></div>
                                    <div class="text-[9.5px] text-ink-muted mt-0.5">{{ statusLabel(b.status) }}</div>
                                </div>
                                <a v-if="b.live_state === 'live' && b.meeting_url"
                                   :href="b.meeting_url" target="_blank" rel="noopener"
                                   class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 rounded-full bg-gradient-to-l from-emerald-500 to-emerald-600 text-white text-[11.5px] font-black shadow-md shadow-emerald-500/40 hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    انضم الآن
                                </a>
                                <Link v-else :href="`/bookings/${b.id}`"
                                      class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 rounded-full border border-[#3DAFB9]/35 text-[#3DAFB9] text-[11.5px] font-black hover:bg-[#3DAFB9]/8 transition-colors">
                                    التفاصيل
                                    <svg class="w-3 h-3 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
defineProps({ bookings: Object });

const formatPrice = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));
const formatTime = (v) => {
    const [h, m] = String(v).split(':');
    const d = new Date(); d.setHours(+h, +m, 0);
    return d.toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit', hour12: true });
};
const statusLabel = (s) => ({ pending_payment: 'بانتظار الدفع', paid: 'مدفوع', confirmed: 'مؤكّد', cancelled: 'ملغى', completed: 'مكتمل' }[s] || s);
const liveStateLabel = (s) => ({ upcoming: 'قادمة', live: 'مباشرة الآن', ended: 'انتهى الوقت', completed: 'مكتملة', cancelled: 'ملغاة' }[s] || 'قادمة');
const liveStateStyle = (s) => ({
    upcoming:  'bg-blue-500/12 text-blue-600',
    live:      'bg-emerald-500/15 text-emerald-600',
    ended:     'bg-amber-500/12 text-amber-600',
    completed: 'bg-gray-500/12 text-gray-500',
    cancelled: 'bg-red-500/12 text-red-500',
}[s] || 'bg-gray-500/12 text-gray-500');
</script>
