<template>
    <MainLayout>
        <Head title="سلة المشتريات" />
        <section class="pt-28 pb-16 bg-paper min-h-screen">
            <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-10">
                <h1 class="text-2xl lg:text-3xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-2">سلة المشتريات</h1>
                <p class="text-[13px] text-ink-muted mb-8">{{ count }} عنصر · المجموع {{ formatPrice(subtotal) }} ر.س</p>

                <div v-if="!items.length" class="rounded-2xl bg-elevated border border-soft p-14 text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-[#3DAFB9]/10 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                        </svg>
                    </div>
                    <p class="text-[15px] font-bold text-ink mb-2">سلتك فارغة</p>
                    <p class="text-[13px] text-ink-muted mb-6">تصفّح دراسات الجدوى وأضف ما يهمّك.</p>
                    <Link href="/feasibility-studies"
                          class="inline-block px-6 py-2.5 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-black shadow-md hover:scale-105 transition-transform">
                        تصفّح الدراسات
                    </Link>
                </div>

                <div v-else class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 lg:col-span-8 space-y-3">
                        <div v-for="item in items" :key="item.purchasable_type + '#' + item.purchasable_id"
                             class="flex items-center gap-4 p-5 rounded-2xl bg-elevated border border-soft">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M15 15l-3-3m0 0l-3 3m3-3v6M9 12H5.625c-.621 0-1.125-.504-1.125-1.125V3.375c0-.621.504-1.125 1.125-1.125h9.75c.621 0 1.125.504 1.125 1.125v3.375"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-ink text-[14px] leading-tight">{{ item.title }}</p>
                                <p class="text-[11px] text-ink-muted mt-1">دراسة جدوى · PDF</p>
                            </div>
                            <div class="text-left flex-shrink-0">
                                <p class="font-black text-gradient-brand text-[16px]">{{ formatPrice(item.unit_price * item.quantity) }}</p>
                                <p class="text-[10px] text-ink-muted">ر.س</p>
                            </div>
                            <button @click="removeItem(item)" class="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 text-red-500 transition-colors" title="إزالة">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </div>

                        <button @click="clearAll" class="text-[12px] text-red-500 hover:underline mt-4">إفراغ السلة بالكامل</button>
                    </div>

                    <!-- Summary sidebar -->
                    <aside class="col-span-12 lg:col-span-4">
                        <div class="rounded-2xl bg-elevated border border-soft p-6 sticky top-24">
                            <h3 class="text-[15px] font-black text-ink mb-4">ملخّص الطلب</h3>
                            <div class="space-y-2.5 text-[13px]">
                                <div class="flex justify-between text-ink-body">
                                    <span>المجموع الفرعي</span>
                                    <span class="font-bold text-ink">{{ formatPrice(subtotal) }} ر.س</span>
                                </div>
                                <div class="flex justify-between text-ink-body">
                                    <span>ضريبة القيمة المضافة (15%)</span>
                                    <span class="font-bold text-ink">{{ formatPrice(subtotal * 0.15) }} ر.س</span>
                                </div>
                                <div class="border-t border-soft pt-3 flex justify-between">
                                    <span class="font-bold text-ink">الإجمالي</span>
                                    <span class="text-[18px] font-black text-gradient-brand">{{ formatPrice(subtotal * 1.15) }} ر.س</span>
                                </div>
                            </div>
                            <Link :href="route('checkout.show')" as="button"
                                  class="w-full mt-6 py-3 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-black shadow-md hover:scale-[1.02] transition-transform text-center block">
                                متابعة إلى الدفع
                            </Link>
                            <p class="text-[10.5px] text-ink-muted text-center mt-3">دفع آمن · Mada · Apple Pay · STC Pay · تحويل بنكي</p>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    items:    { type: Array,  default: () => [] },
    subtotal: { type: Number, default: 0 },
    count:    { type: Number, default: 0 },
});

const formatPrice = (v) => new Intl.NumberFormat('ar-SA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Math.round(v * 100) / 100);

function removeItem(item) {
    router.delete('/cart/item', { data: { type: item.purchasable_type, id: item.purchasable_id }, preserveScroll: true });
}
function clearAll() {
    if (confirm('إفراغ السلة بالكامل؟')) router.delete('/cart', { preserveScroll: true });
}
</script>
