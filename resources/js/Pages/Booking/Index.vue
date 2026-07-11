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

                <div v-else class="space-y-3">
                    <Link v-for="b in bookings.data" :key="b.id" :href="`/bookings/${b.id}`"
                          class="group flex items-center gap-4 p-5 rounded-2xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover transition-all">
                        <img :src="b.consultant.avatar" :alt="b.consultant.name" class="w-14 h-14 rounded-full object-cover shrink-0" />
                        <div class="flex-1 min-w-0">
                            <div class="text-[14px] font-black text-ink group-hover:text-[#3DAFB9] transition-colors truncate">{{ b.consultant.name }}</div>
                            <div class="text-[11.5px] text-ink-muted mt-0.5">
                                {{ b.preferred_date }} · {{ b.preferred_time }} · {{ b.duration_min }} دقيقة
                            </div>
                            <div class="text-[10px] text-ink-muted mt-1 font-mono" dir="ltr">{{ b.reference }}</div>
                        </div>
                        <div class="text-left rtl:text-left shrink-0">
                            <div class="text-[15px] font-black text-ink">{{ formatPrice(b.amount) }}</div>
                            <div class="text-[9px] text-ink-muted uppercase tracking-widest mt-0.5">ر.س</div>
                            <span :class="['inline-block mt-2 text-[9.5px] font-bold px-2 py-0.5 rounded-full', statusColor(b.status)]">
                                {{ statusLabel(b.status) }}
                            </span>
                        </div>
                    </Link>
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
const statusLabel = (s) => ({ pending_payment: 'بانتظار الدفع', paid: 'مدفوع', confirmed: 'مؤكّد', cancelled: 'ملغى', completed: 'مكتمل' }[s] || s);
const statusColor = (s) => ({
    pending_payment: 'bg-orange-500/12 text-orange-500',
    paid:            'bg-blue-500/12 text-blue-500',
    confirmed:       'bg-green-500/12 text-green-600',
    cancelled:       'bg-red-500/12 text-red-500',
    completed:       'bg-gray-500/12 text-gray-500',
}[s] || 'bg-gray-500/12 text-gray-500');
</script>
