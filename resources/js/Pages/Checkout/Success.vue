<template>
    <MainLayout>
        <Head title="تم الدفع بنجاح" />
        <section class="pt-28 pb-16 bg-paper min-h-screen">
            <div class="max-w-[720px] mx-auto px-4 sm:px-6 lg:px-10 text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-3">تم الدفع بنجاح 🎉</h1>
                <p class="text-[15px] text-ink-body mb-2">شكراً لك — طلبك رقم <span class="font-black text-gradient-brand" dir="ltr">{{ order.reference }}</span></p>
                <p class="text-[13px] text-ink-muted mb-8">تم إرسال تفاصيل الطلب وروابط التحميل إلى بريدك: <b class="text-ink" dir="ltr">{{ order.contact_email }}</b></p>

                <div class="rounded-2xl bg-elevated border border-soft p-6 text-right">
                    <h3 class="text-[15px] font-black text-ink mb-4">تحميل مشترياتك</h3>
                    <div class="space-y-3">
                        <div v-for="(i, idx) in order.items" :key="idx"
                             class="flex items-center justify-between gap-3 p-4 rounded-xl bg-paper border border-soft">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-ink text-[13.5px] line-clamp-1">{{ i.title }}</p>
                                <p class="text-[11px] text-ink-muted mt-0.5">{{ formatPrice(i.subtotal) }} ر.س</p>
                            </div>
                            <a v-if="i.download_url" :href="i.download_url"
                               class="px-4 py-2 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[12px] font-black shadow-sm hover:scale-105 transition-transform flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                                تحميل
                            </a>
                        </div>
                    </div>
                </div>

                <Link href="/" class="inline-block mt-8 text-[13px] text-ink-muted hover:text-[#3DAFB9]">العودة للرئيسية →</Link>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({ order: Object });
const formatPrice = (v) => new Intl.NumberFormat('ar-SA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Math.round(v * 100) / 100);
</script>
