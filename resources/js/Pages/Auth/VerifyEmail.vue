<template>
    <Head title="توثيق البريد الإلكتروني" />

    <div class="min-h-screen flex items-center justify-center bg-paper px-6 py-10 relative" dir="rtl">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute inset-0 grid-pattern opacity-40"></div>
            <div class="absolute top-0 right-[15%] w-[520px] h-[520px] rounded-full aurora-drift"
                 style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
            <div class="absolute bottom-0 left-[10%] w-[420px] h-[420px] rounded-full aurora-drift"
                 style="background: radial-gradient(circle, rgba(45,75,126,0.10), transparent 70%); animation-delay: 3s;"></div>
        </div>

        <div class="relative w-full max-w-md">
            <a href="/" class="flex items-center justify-center gap-3 mb-8 group">
                <img :src="isDark ? '/images/rowaad-logo-symbol-dark.png' : '/images/rowaad-logo-symbol.png'"
                     alt="رواد" class="h-11 w-auto object-contain logo-glow" />
                <span class="font-black text-lg text-ink">رواد بلا حدود</span>
            </a>

            <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-9 shadow-card">
                <!-- Icon -->
                <div class="relative w-20 h-20 mx-auto mb-6">
                    <span class="absolute inset-0 rounded-full bg-[#3DAFB9]/15 animate-ping"></span>
                    <div class="relative w-full h-full rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center shadow-xl shadow-[#3DAFB9]/40">
                        <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#3DAFB9]/10 border border-[#3DAFB9]/25 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3DAFB9] animate-pulse"></span>
                        <span class="text-[10.5px] font-black text-[#3DAFB9] tracking-widest">خطوة أخيرة</span>
                    </div>
                    <h1 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-3">وثّق بريدك الإلكتروني</h1>
                    <p class="text-[13.5px] text-ink-body leading-[1.9]">
                        أرسلنا رابط التوثيق إلى:<br>
                        <span class="inline-block mt-2 px-3 py-1 rounded-lg bg-canvas font-bold text-ink" dir="ltr">{{ email }}</span>
                    </p>
                </div>

                <!-- Just-sent flash -->
                <div v-if="status === 'verification-link-sent'"
                     class="mb-5 p-3 rounded-2xl bg-green-500/10 border border-green-500/25 text-center">
                    <div class="text-[13px] font-black text-green-700 dark:text-green-400">تم إرسال رابط توثيق جديد ✓</div>
                    <div class="text-[11.5px] text-ink-body mt-1">تحقّق من صندوق البريد.</div>
                </div>

                <div class="p-4 rounded-2xl bg-canvas border border-soft mb-5">
                    <p class="text-[12.5px] text-ink-body leading-[1.9] text-center">
                        قبل أن تتمكّن من حجز أي استشارة، يجب أن نتحقّق من صحّة بريدك.
                        <span class="block mt-1 text-ink-muted text-[11.5px]">لم يصلك الرابط؟ ابحث في مجلد الرسائل غير المرغوبة.</span>
                    </p>
                </div>

                <div class="space-y-3">
                    <form @submit.prevent="resend">
                        <button type="submit" :disabled="form.processing"
                                class="w-full py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[14px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.02] disabled:opacity-60 transition-transform">
                            <span class="inline-flex items-center justify-center gap-2">
                                <span v-if="form.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                إعادة إرسال رابط التوثيق
                            </span>
                        </button>
                    </form>

                    <Link href="/profile" class="block w-full py-2.5 rounded-full bg-elevated border border-soft text-ink text-[12.5px] font-bold text-center hover:border-[#3DAFB9]/40 transition-colors">
                        الذهاب إلى ملفي
                    </Link>

                    <form @submit.prevent="logout">
                        <button type="submit" class="w-full py-2 text-[12px] text-ink-muted hover:text-red-500 font-bold transition-colors">
                            تسجيل خروج
                        </button>
                    </form>
                </div>
            </div>

            <p class="mt-8 text-center text-[11px] text-ink-muted">
                لديك مشكلة؟ <Link href="/contact" class="text-[#3DAFB9] font-bold hover:underline">تواصل معنا</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { useTheme } from '@/composables/useTheme';

defineProps({
    status: String,
    email:  String,
});

const { isDark } = useTheme();

const form = useForm({});
const resend = () => form.post('/email/verification-notification');
const logout = () => router.post('/logout');
</script>
