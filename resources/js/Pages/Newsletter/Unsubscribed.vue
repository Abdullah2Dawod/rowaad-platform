<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-24 bg-paper overflow-hidden">
            <div class="relative max-w-2xl mx-auto px-6 lg:px-10 text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-orange-500/12 border border-orange-500/20 flex items-center justify-center mb-6">
                    <svg class="w-9 h-9 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M18 12H6"/></svg>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black text-ink mb-3">تم إلغاء اشتراكك</h1>
                <p class="text-[14px] text-ink-body leading-[1.9] max-w-md mx-auto mb-6">
                    لن نُرسل إليك رسائل بعد الآن. سنفتقدك — إن غيّرت رأيك، يمكنك إعادة الاشتراك في أي وقت.
                </p>
                <Link href="/" class="inline-flex px-5 py-2.5 rounded-full bg-elevated border border-soft text-ink text-[13px] font-bold hover:border-[#3DAFB9]/40 transition-colors">
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
