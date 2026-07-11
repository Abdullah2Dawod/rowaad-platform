<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-24 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[10%] w-[500px] h-[500px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>
            </div>

            <div class="relative max-w-4xl mx-auto px-6 lg:px-10">
                <div class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold mb-2">الخطوة الأخيرة</div>
                <h1 class="text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-2">أتم عملية الدفع</h1>
                <p class="text-[13.5px] text-ink-body mb-8">مرجع الحجز: <span class="font-mono font-black text-ink" dir="ltr">{{ booking.reference }}</span></p>

                <div class="grid grid-cols-12 gap-6">
                    <!-- Payment methods -->
                    <form @submit.prevent="submit" class="col-span-12 lg:col-span-7 rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-9 shadow-card space-y-6">
                        <div>
                            <h2 class="text-lg font-black text-ink mb-1">اختر طريقة الدفع</h2>
                            <p class="text-[12.5px] text-ink-body">جميع المعاملات مؤمّنة بتشفير SSL.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <label v-for="m in methods" :key="m.id"
                                   :class="['relative flex items-center gap-3 p-4 rounded-2xl border cursor-pointer transition-all',
                                            form.payment_method === m.id ? 'border-[#3DAFB9] bg-[#3DAFB9]/8 shadow-sm' : 'border-soft hover:border-[#3DAFB9]/40']">
                                <input v-model="form.payment_method" :value="m.id" type="radio" class="sr-only" />
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-2xl shrink-0"
                                     :class="form.payment_method === m.id ? 'bg-[#3DAFB9]/15' : 'bg-canvas'">
                                    {{ m.icon }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[13px] font-black text-ink">{{ m.label }}</div>
                                    <div class="text-[10.5px] text-ink-muted">{{ m.hint }}</div>
                                </div>
                            </label>
                        </div>

                        <!-- Mock card fields (visual only) -->
                        <div v-if="form.payment_method && form.payment_method !== 'apple_pay'" class="pt-4 border-t border-soft space-y-3">
                            <div>
                                <label class="text-[11.5px] font-bold text-ink-body block mb-1.5">رقم البطاقة</label>
                                <input type="text" dir="ltr" class="fld" placeholder="4242 4242 4242 4242" maxlength="19" />
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[11.5px] font-bold text-ink-body block mb-1.5">تاريخ الانتهاء</label>
                                    <input type="text" dir="ltr" class="fld" placeholder="MM/YY" maxlength="5" />
                                </div>
                                <div>
                                    <label class="text-[11.5px] font-bold text-ink-body block mb-1.5">CVV</label>
                                    <input type="text" dir="ltr" class="fld" placeholder="123" maxlength="4" />
                                </div>
                            </div>
                            <p class="text-[10.5px] text-ink-muted flex items-center gap-1.5">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                محاكاة دفع للتجربة — لا تُخزَّن أي بيانات بطاقة فعلياً
                            </p>
                        </div>

                        <button type="submit" :disabled="form.processing || !form.payment_method"
                                class="group w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[14px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed transition-transform">
                            <span v-if="form.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            دفع {{ formatPrice(booking.total_amount || booking.amount) }} ر.س بأمان
                        </button>
                    </form>

                    <!-- Summary -->
                    <aside class="col-span-12 lg:col-span-5">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card">
                            <div class="flex items-center gap-3 mb-5 pb-5 border-b border-soft">
                                <img :src="booking.consultant.avatar" :alt="booking.consultant.name" class="w-14 h-14 rounded-full object-cover shrink-0" />
                                <div class="min-w-0">
                                    <div class="text-[14px] font-black text-ink truncate">{{ booking.consultant.name }}</div>
                                    <div class="text-[11.5px] text-ink-body truncate">{{ booking.consultant.title }}</div>
                                </div>
                            </div>

                            <dl class="space-y-3 text-[13px]">
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">التاريخ</dt>
                                    <dd class="font-bold text-ink">{{ booking.preferred_date }} · {{ booking.preferred_time }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">المدة</dt>
                                    <dd class="font-bold text-ink">{{ booking.duration_min }} دقيقة</dd>
                                </div>
                                <div v-if="booking.service_title" class="flex items-center justify-between">
                                    <dt class="text-ink-muted">الخدمة</dt>
                                    <dd class="font-bold text-ink text-left">{{ booking.service_title }}</dd>
                                </div>
                            </dl>

                            <div class="border-t border-soft mt-5 pt-5 space-y-2">
                                <div class="flex items-center justify-between text-[13px]">
                                    <span class="text-ink-body">المبلغ الأساسي</span>
                                    <span class="font-bold text-ink">{{ formatPrice(booking.base_amount) }} ر.س</span>
                                </div>
                                <div class="flex items-center justify-between text-[13px]">
                                    <span class="text-ink-body">زكاة (15%)</span>
                                    <span class="font-bold text-[#F59E0B]">+ {{ formatPrice(booking.zakat_amount) }} ر.س</span>
                                </div>
                                <div class="flex items-center justify-between pt-3 mt-1 border-t border-soft text-[15px]">
                                    <span class="font-black text-ink">الإجمالي المستحق</span>
                                    <span class="font-black text-[#3DAFB9] text-lg">{{ formatPrice(booking.total_amount) }} ر.س</span>
                                </div>
                                <div class="rounded-2xl bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5 border border-[#3DAFB9]/20 p-3 mt-3">
                                    <div class="text-[10px] text-[#3DAFB9] font-black tracking-widest mb-2">توزيع المبلغ بعد الدفع</div>
                                    <div class="text-[11.5px] space-y-1 text-ink-body">
                                        <div class="flex justify-between"><span>حصة المستشار</span><span class="font-bold">{{ formatPrice(booking.consultant_share) }} ر.س</span></div>
                                        <div class="flex justify-between"><span>حصة المنصة</span><span class="font-bold">{{ formatPrice(booking.platform_share) }} ر.س</span></div>
                                        <div class="flex justify-between text-[#F59E0B]"><span>وعاء الزكاة</span><span class="font-bold">{{ formatPrice(booking.zakat_amount) }} ر.س</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({ booking: Object });

const form = useForm({
    payment_method: 'mada',
});

const methods = [
    { id: 'mada',      label: 'مدى',       hint: 'بطاقات البنوك السعودية',   icon: '💳' },
    { id: 'visa',      label: 'Visa/Master', hint: 'بطاقات ائتمانية دولية', icon: '💳' },
    { id: 'apple_pay', label: 'Apple Pay', hint: 'دفع سريع بلمسة واحدة',    icon: '' },
    { id: 'mock',      label: 'دفع تجريبي', hint: 'للاختبار فقط',            icon: '⚡' },
];

const formatPrice = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));
const submit = () => form.post(`/bookings/${props.booking.id}/pay`);
</script>

<style scoped>
.fld { display: block; width: 100%; height: 40px; padding: 0 14px; background: var(--bg-canvas); border: 1px solid var(--border-soft); border-radius: 10px; color: var(--ink-primary); font-size: 13.5px; font-family: inherit; transition: border-color 200ms ease, box-shadow 200ms ease; }
.fld:focus { outline: none; border-color: #3DAFB9; box-shadow: 0 0 0 3px rgba(61,175,185,0.15); }
</style>
