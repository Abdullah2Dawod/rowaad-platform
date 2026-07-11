<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-24 bg-paper">
            <div class="relative max-w-2xl mx-auto px-6 lg:px-10 text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mb-6">
                    <svg class="w-9 h-9 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" d="M12 8v4m0 4h.01"/>
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black text-ink mb-3">لم يُعتمد طلبك حالياً</h1>
                <p class="text-[14px] text-ink-body leading-[1.9] max-w-md mx-auto mb-6">
                    نشكرك على اهتمامك بالانضمام إلى منصة رواد. للأسف، لم يكن بمقدورنا اعتماد طلبك في هذه المرة.
                </p>
                <div v-if="reason" class="text-right rtl:text-right p-5 rounded-2xl bg-elevated border border-soft mb-8">
                    <div class="text-[11px] font-black text-red-500 tracking-widest mb-2">ملاحظات المراجعة</div>
                    <p class="text-[13px] text-ink-body leading-relaxed whitespace-pre-line">{{ reason }}</p>
                </div>
                <Link href="/" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-elevated border border-soft text-ink text-[13px] font-bold hover:border-[#3DAFB9]/40 transition-colors">
                    العودة للرئيسية
                </Link>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
defineProps({ reason: String });
</script>
