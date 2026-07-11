<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-24 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[15%] w-[520px] h-[520px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
            </div>
            <div class="relative max-w-2xl mx-auto px-6 lg:px-10 text-center">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <span class="absolute inset-0 rounded-full bg-[#3DAFB9]/15 animate-ping"></span>
                    <div class="relative w-full h-full rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center shadow-xl shadow-[#3DAFB9]/40">
                        <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
                <div class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold mb-3">تم التأكيد</div>
                <h1 class="text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-3">أهلاً بك في نشرة رواد</h1>
                <p class="text-[15px] text-ink-body leading-[1.9] max-w-lg mx-auto mb-8">
                    نجح تأكيد اشتراك <span class="font-black text-ink" dir="ltr">{{ email }}</span>.
                    ستستقبل تحاليلنا الاقتصادية، الفرص الاستثمارية، والدراسات الجديدة أولاً بأول.
                </p>
                <Link href="/" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13.5px] font-black shadow-lg hover:scale-105 transition-transform">
                    العودة للرئيسية
                </Link>
            </div>
        </section>
    </MainLayout>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
defineProps({ email: String });
</script>
