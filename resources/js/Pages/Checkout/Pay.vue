<template>
    <MainLayout>
        <Head title="بوابة الدفع" />
        <section class="pt-28 pb-16 bg-paper min-h-screen">
            <div class="max-w-[720px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="rounded-3xl bg-elevated border border-soft p-8 lg:p-12 text-center">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#3DAFB9]/12 text-[#3DAFB9] text-[10.5px] font-black tracking-wider mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3DAFB9] animate-pulse"></span>
                        بوابة الدفع الآمنة
                    </div>
                    <h1 class="text-2xl lg:text-3xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-2">إتمام الدفع</h1>
                    <p class="text-[13px] text-ink-muted mb-6">طلب رقم <span class="font-black text-ink" dir="ltr">{{ order.reference }}</span></p>

                    <div class="rounded-2xl bg-paper border border-soft p-5 mb-6 text-right">
                        <div class="space-y-2 text-[13px]">
                            <div v-for="(i, idx) in order.items" :key="idx" class="flex justify-between text-ink-body">
                                <span class="flex-1 line-clamp-1">{{ i.title }}</span>
                                <span class="font-bold text-ink whitespace-nowrap">{{ formatPrice(i.subtotal) }} ر.س</span>
                            </div>
                            <div class="border-t border-soft pt-2 flex justify-between text-ink-body">
                                <span>المجموع</span><span class="font-bold text-ink">{{ formatPrice(order.subtotal) }} ر.س</span>
                            </div>
                            <div class="flex justify-between text-ink-body">
                                <span>الضريبة</span><span class="font-bold text-ink">{{ formatPrice(order.vat_amount) }} ر.س</span>
                            </div>
                            <div class="border-t border-soft pt-2 flex justify-between">
                                <span class="font-black text-ink">الإجمالي المطلوب</span>
                                <span class="text-[20px] font-black text-gradient-brand">{{ formatPrice(order.total) }} ر.س</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-[#2D4B7E]/6 to-[#3DAFB9]/6 border border-[#3DAFB9]/20 p-5 mb-6 text-right">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-[#3DAFB9] flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <div class="text-[12px] leading-[1.8] text-ink-body">
                                <p class="font-bold text-ink mb-1">دفع آمن ومشفّر</p>
                                عملية الدفع تتم عبر بوابة دفع معتمدة (Moyasar / Tap / PayTabs). بياناتك محمية بمعايير <span dir="ltr" class="font-bold">PCI-DSS Level 1</span> و <span dir="ltr" class="font-bold">3-D Secure</span>. لا نخزّن أي معلومات بطاقات على خوادمنا.
                            </div>
                        </div>
                    </div>

                    <button @click="confirmPayment" :disabled="busy"
                            class="w-full py-4 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[14px] font-black shadow-lg hover:scale-[1.02] transition-transform disabled:opacity-60 disabled:cursor-not-allowed">
                        {{ busy ? 'جاري الاتصال بالبوابة…' : `ادفع ${formatPrice(order.total)} ر.س الآن` }}
                    </button>
                    <p class="text-[11px] text-ink-muted mt-4">
                        بالضغط على "ادفع الآن" توافق على <a href="/terms" class="text-[#3DAFB9] hover:underline">الشروط والأحكام</a>.
                    </p>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({ order: Object });
const busy = ref(false);

const formatPrice = (v) => new Intl.NumberFormat('ar-SA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Math.round(v * 100) / 100);

function confirmPayment() {
    busy.value = true;
    router.post(`/checkout/${props.order.id}/confirm`, {}, { onFinish: () => (busy.value = false) });
}
</script>
