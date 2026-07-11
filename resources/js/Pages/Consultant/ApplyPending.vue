<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-24 overflow-hidden bg-paper">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[15%] w-[500px] h-[500px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
                <div class="absolute bottom-0 left-[10%] w-[400px] h-[400px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.10), transparent 70%); animation-delay: 3s;"></div>
            </div>

            <div class="relative max-w-2xl mx-auto px-6 lg:px-10 text-center">
                <!-- Success mark -->
                <div class="relative w-28 h-28 mx-auto mb-8">
                    <span class="absolute inset-0 rounded-full bg-[#3DAFB9]/15 animate-ping"></span>
                    <span class="absolute inset-2 rounded-full bg-[#3DAFB9]/20"></span>
                    <div class="relative w-full h-full rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center shadow-xl shadow-[#3DAFB9]/40">
                        <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="h-px w-8 bg-gradient-to-r from-transparent to-[#3DAFB9]"></div>
                    <span class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">تم الاستلام</span>
                    <div class="h-px w-8 bg-gradient-to-l from-transparent to-[#3DAFB9]"></div>
                </div>

                <h1 class="text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4">
                    تم إرسال طلبك بنجاح
                </h1>
                <p class="text-[15px] text-ink-body leading-[1.9] max-w-lg mx-auto">
                    شكراً لثقتك بمنصة رواد. طلبك الآن قيد المراجعة من قِبَل فريق الاعتماد،
                    وسنتواصل معك عبر بريدك الإلكتروني خلال <span class="font-black text-ink">48 ساعة عمل</span>.
                </p>

                <!-- What's next -->
                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-3 text-right rtl:text-right">
                    <div v-for="(step, i) in nextSteps" :key="i"
                         class="p-5 rounded-2xl bg-elevated border border-soft">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-full bg-[#3DAFB9]/15 text-[#3DAFB9] flex items-center justify-center text-[11px] font-black">{{ i + 1 }}</span>
                            <span class="text-[13px] font-black text-ink">{{ step.title }}</span>
                        </div>
                        <p class="text-[12px] text-ink-body leading-relaxed">{{ step.desc }}</p>
                    </div>
                </div>

                <!-- CTA -->
                <div class="mt-10 flex items-center justify-center gap-3 flex-wrap">
                    <Link href="/consultants" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-105 transition-transform">
                        تصفّح المستشارين
                    </Link>
                    <Link href="/" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-elevated border border-soft text-ink text-[13px] font-bold hover:border-[#3DAFB9]/40 transition-colors">
                        العودة للرئيسية
                    </Link>
                </div>

                <p v-if="submitted_at" class="mt-8 text-[11px] text-ink-muted">
                    تم الإرسال: {{ new Date(submitted_at).toLocaleString('ar-SA') }}
                </p>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({ submitted_at: String });

const nextSteps = [
    { title: 'المراجعة', desc: 'يُراجع فريقنا بياناتك ومستنداتك خلال 48 ساعة عمل.' },
    { title: 'التواصل',  desc: 'قد نتواصل معك للتوضيح أو استكمال أي بيانات ناقصة.' },
    { title: 'الاعتماد', desc: 'عند الموافقة، ستظهر صفحتك للعملاء وتبدأ باستقبال الحجوزات.' },
];
</script>
