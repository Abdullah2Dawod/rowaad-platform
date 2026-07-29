<template>
    <MainLayout>
        <Head title="إتمام الطلب" />
        <section class="pt-28 pb-16 bg-paper min-h-screen">
            <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-10">
                <h1 class="text-2xl lg:text-3xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-2">إتمام الطلب</h1>
                <p class="text-[13px] text-ink-muted mb-8">دفع آمن ومشفّر — بياناتك محمية بأعلى معايير الأمان.</p>

                <form @submit.prevent="submit" class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 lg:col-span-8 space-y-6">
                        <!-- Billing -->
                        <div class="rounded-2xl bg-elevated border border-soft p-6">
                            <h3 class="text-[15px] font-black text-ink mb-5 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full bg-[#3DAFB9]/15 text-[#3DAFB9] flex items-center justify-center text-[12px] font-black">1</span>
                                بيانات الفوترة
                            </h3>
                            <div class="grid grid-cols-2 gap-3 text-[12.5px]">
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-ink-body font-bold mb-1.5">الاسم الكامل *</label>
                                    <input v-model="form.contact_name" type="text" required
                                           class="w-full px-4 py-2.5 rounded-lg bg-paper border border-soft focus:border-[#3DAFB9] focus:ring-2 focus:ring-[#3DAFB9]/20 outline-none" />
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-ink-body font-bold mb-1.5">البريد الإلكتروني *</label>
                                    <input v-model="form.contact_email" type="email" required
                                           class="w-full px-4 py-2.5 rounded-lg bg-paper border border-soft focus:border-[#3DAFB9] focus:ring-2 focus:ring-[#3DAFB9]/20 outline-none" />
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-ink-body font-bold mb-1.5">الجوال *</label>
                                    <input v-model="form.contact_phone" type="tel" required placeholder="+9665…"
                                           class="w-full px-4 py-2.5 rounded-lg bg-paper border border-soft focus:border-[#3DAFB9] focus:ring-2 focus:ring-[#3DAFB9]/20 outline-none" dir="ltr" />
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-ink-body font-bold mb-1.5">اسم الشركة (اختياري)</label>
                                    <input v-model="form.company_name" type="text"
                                           class="w-full px-4 py-2.5 rounded-lg bg-paper border border-soft focus:border-[#3DAFB9] focus:ring-2 focus:ring-[#3DAFB9]/20 outline-none" />
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-ink-body font-bold mb-1.5">الرقم الضريبي (اختياري)</label>
                                    <input v-model="form.tax_id" type="text"
                                           class="w-full px-4 py-2.5 rounded-lg bg-paper border border-soft focus:border-[#3DAFB9] focus:ring-2 focus:ring-[#3DAFB9]/20 outline-none" dir="ltr" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-ink-body font-bold mb-1.5">عنوان الفوترة (اختياري)</label>
                                    <textarea v-model="form.billing_address" rows="2"
                                              class="w-full px-4 py-2.5 rounded-lg bg-paper border border-soft focus:border-[#3DAFB9] focus:ring-2 focus:ring-[#3DAFB9]/20 outline-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment method -->
                        <div class="rounded-2xl bg-elevated border border-soft p-6">
                            <h3 class="text-[15px] font-black text-ink mb-5 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full bg-[#3DAFB9]/15 text-[#3DAFB9] flex items-center justify-center text-[12px] font-black">2</span>
                                طريقة الدفع
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <label v-for="m in methods" :key="m.value"
                                       :class="['flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all',
                                                 form.payment_method === m.value
                                                     ? 'border-[#3DAFB9] bg-[#3DAFB9]/8 shadow-sm'
                                                     : 'border-soft bg-paper hover:border-[#3DAFB9]/40']">
                                    <input type="radio" v-model="form.payment_method" :value="m.value" class="sr-only" />
                                    <span class="text-2xl">{{ m.emoji }}</span>
                                    <span class="text-[12px] font-bold text-ink">{{ m.label }}</span>
                                </label>
                            </div>
                            <p class="text-[11px] text-ink-muted mt-3">
                                <svg class="inline w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1l3 6 6 .87-4.5 4.35L15.5 19 10 15.9 4.5 19l1-6.78L1 7.87 7 7l3-6z"/></svg>
                                بياناتك مشفّرة عبر SSL. لا نخزّن أي معلومات بطاقة على خوادمنا.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-elevated border border-soft p-6">
                            <label class="block text-[13px] font-bold text-ink mb-2">ملاحظات إضافية (اختياري)</label>
                            <textarea v-model="form.notes" rows="3" placeholder="أي تفاصيل تودّ إخبارنا بها…"
                                      class="w-full px-4 py-2.5 rounded-lg bg-paper border border-soft focus:border-[#3DAFB9] focus:ring-2 focus:ring-[#3DAFB9]/20 outline-none text-[12.5px]"></textarea>
                        </div>
                    </div>

                    <!-- Order summary -->
                    <aside class="col-span-12 lg:col-span-4">
                        <div class="rounded-2xl bg-elevated border border-soft p-6 sticky top-24">
                            <h3 class="text-[15px] font-black text-ink mb-4">ملخّص الطلب</h3>
                            <div class="space-y-2 text-[12px] mb-4 pb-4 border-b border-soft">
                                <div v-for="item in items" :key="item.purchasable_type + '#' + item.purchasable_id" class="flex justify-between gap-3">
                                    <span class="text-ink-body flex-1 line-clamp-2">{{ item.title }}</span>
                                    <span class="text-ink font-bold whitespace-nowrap">{{ formatPrice(item.unit_price * item.quantity) }}</span>
                                </div>
                            </div>
                            <div class="space-y-2 text-[13px]">
                                <div class="flex justify-between text-ink-body">
                                    <span>المجموع الفرعي</span>
                                    <span class="font-bold text-ink">{{ formatPrice(subtotal) }} ر.س</span>
                                </div>
                                <div class="flex justify-between text-ink-body">
                                    <span>الضريبة ({{ (vatRate * 100).toFixed(0) }}%)</span>
                                    <span class="font-bold text-ink">{{ formatPrice(vatAmount) }} ر.س</span>
                                </div>
                                <div class="border-t border-soft pt-3 flex justify-between">
                                    <span class="font-bold text-ink">الإجمالي</span>
                                    <span class="text-[18px] font-black text-gradient-brand">{{ formatPrice(total) }} ر.س</span>
                                </div>
                            </div>
                            <button type="submit" :disabled="busy"
                                    class="w-full mt-6 py-3 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-black shadow-md hover:scale-[1.02] transition-transform disabled:opacity-60">
                                {{ busy ? 'جاري المعالجة…' : 'إتمام الطلب والانتقال للدفع' }}
                            </button>
                        </div>
                    </aside>
                </form>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    items:    { type: Array,  default: () => [] },
    subtotal: { type: Number, default: 0 },
    vatRate:  { type: Number, default: 0.15 },
    user:     { type: Object, default: null },
});

const busy = ref(false);
const form = ref({
    contact_name:    props.user?.name  ?? '',
    contact_email:   props.user?.email ?? '',
    contact_phone:   props.user?.phone ?? '',
    company_name:    '',
    tax_id:          '',
    billing_address: '',
    payment_method:  'mada',
    notes:           '',
});

const methods = [
    { value: 'mada',          label: 'مدى',           emoji: '💳' },
    { value: 'apple_pay',     label: 'Apple Pay',      emoji: '' },
    { value: 'stc_pay',       label: 'STC Pay',        emoji: '📱' },
    { value: 'credit_card',   label: 'بطاقة ائتمان',   emoji: '💳' },
    { value: 'bank_transfer', label: 'تحويل بنكي',     emoji: '🏦' },
];

const vatAmount = computed(() => props.subtotal * props.vatRate);
const total     = computed(() => props.subtotal + vatAmount.value);

const formatPrice = (v) => new Intl.NumberFormat('ar-SA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Math.round(v * 100) / 100);

function submit() {
    busy.value = true;
    router.post('/checkout', form.value, { onFinish: () => (busy.value = false) });
}
</script>
