<template>
    <Head title="إنشاء حساب" />

    <div class="min-h-screen flex flex-col lg:flex-row bg-paper" dir="rtl">
        <!-- ═════════ BRAND PANEL (hidden on mobile) ═════════ -->
        <aside class="hidden lg:flex lg:w-[46%] xl:w-[42%] relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50]"></div>
            <div class="absolute -top-24 -right-24 w-[520px] h-[520px] rounded-full aurora-drift"
                 style="background: radial-gradient(circle, rgba(61,175,185,0.35), transparent 70%);"></div>
            <div class="absolute -bottom-24 -left-24 w-[480px] h-[480px] rounded-full aurora-drift"
                 style="background: radial-gradient(circle, rgba(45,75,126,0.45), transparent 70%); animation-delay: 3s;"></div>
            <div class="absolute inset-0 opacity-[0.07]" style="background-image: linear-gradient(rgba(107,200,210,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(107,200,210,0.5) 1px, transparent 1px); background-size: 56px 56px;"></div>

            <div class="relative flex flex-col justify-between w-full p-12 xl:p-16">
                <a href="/" class="inline-flex items-center gap-3 self-start group">
                    <img src="/images/rowaad-logo-symbol-dark.png" alt="رواد" class="h-12 w-auto object-contain logo-glow group-hover:scale-105 transition-transform" />
                    <span class="text-white/95 font-black text-lg tracking-tight">رواد بلا حدود</span>
                </a>

                <div class="relative">
                    <svg class="absolute -top-8 -left-8 w-64 h-64 opacity-30" style="animation: brandSpin 40s linear infinite;" viewBox="0 0 200 200" fill="none">
                        <circle cx="100" cy="100" r="98" stroke="#6BC8D2" stroke-width="1" stroke-dasharray="4 12"/>
                        <circle cx="100" cy="100" r="80" stroke="#6BC8D2" stroke-width="0.5" stroke-dasharray="2 8"/>
                    </svg>

                    <div class="relative inline-flex items-center gap-3 mb-5">
                        <div class="flex items-end gap-[3px] h-5">
                            <span class="block w-[3px] h-3 bg-gradient-to-t from-[#3DAFB9] to-[#6BC8D2] rounded-full bar-pulse"></span>
                            <span class="block w-[3px] h-5 bg-gradient-to-t from-[#3DAFB9] to-[#6BC8D2] rounded-full bar-pulse" style="animation-delay: .2s;"></span>
                            <span class="block w-[3px] h-3.5 bg-gradient-to-t from-[#3DAFB9] to-[#6BC8D2] rounded-full bar-pulse" style="animation-delay: .4s;"></span>
                        </div>
                        <span class="text-[10px] tracking-[0.3em] uppercase font-bold text-[#6BC8D2]">انضم إلينا</span>
                    </div>

                    <h1 class="relative text-white text-3xl xl:text-[2.4rem] font-black leading-tight mb-4">
                        ابدأ رحلتك<br>
                        <span class="bg-clip-text text-transparent bg-gradient-to-l from-[#6BC8D2] to-[#3DAFB9]">مع منصة رواد</span>
                    </h1>
                    <p class="relative text-white/60 text-[14px] leading-[1.9] max-w-md">
                        أنشئ حسابك الآن للوصول إلى نخبة المستشارين، حجز الاستشارات، وطلب دراسات الجدوى المتخصّصة —
                        كل ذلك في منصة واحدة بمعايير عالمية.
                    </p>
                </div>

                <!-- Value props -->
                <div class="relative grid grid-cols-3 gap-4 pt-8 border-t border-white/10">
                    <div>
                        <div class="text-2xl font-black text-white">+12</div>
                        <div class="text-[10px] text-white/50 tracking-wider mt-1">مستشار معتمد</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-l from-[#6BC8D2] to-[#3DAFB9]">24/7</div>
                        <div class="text-[10px] text-white/50 tracking-wider mt-1">دعم مباشر</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-white">آمن</div>
                        <div class="text-[10px] text-white/50 tracking-wider mt-1">تشفير كامل</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ═════════ FORM PANEL ═════════ -->
        <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-10 py-8 sm:py-10 lg:py-0 relative">
            <div class="lg:hidden absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[10%] w-[400px] h-[400px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
            </div>

            <div class="relative w-full max-w-md">
                <!-- Mobile logo -->
                <a href="/" class="flex lg:hidden items-center gap-3 mb-6 justify-center group">
                    <img :src="isDark ? '/images/rowaad-logo-symbol-dark.png' : '/images/rowaad-logo-symbol.png'"
                         alt="رواد" class="h-11 w-auto object-contain logo-glow" />
                    <span class="font-black text-lg text-ink">رواد بلا حدود</span>
                </a>

                <!-- Header -->
                <div class="mb-7">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#3DAFB9]/10 border border-[#3DAFB9]/25 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3DAFB9] animate-pulse"></span>
                        <span class="text-[10.5px] font-black text-[#3DAFB9] tracking-widest">حساب جديد</span>
                    </div>
                    <h2 class="text-[1.5rem] sm:text-2xl lg:text-3xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-tight mb-2">
                        أنشئ حسابك في دقيقة
                    </h2>
                    <p class="text-[13px] text-ink-body">
                        املأ البيانات أدناه للبدء — سنُرسل رابط تفعيل لبريدك.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">الاسم الكامل</label>
                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input v-model="form.name" type="text" autofocus autocomplete="name"
                                   class="fld pr-11" placeholder="اسمك الكامل" />
                        </div>
                        <span v-if="form.errors.name" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ form.errors.name }}</span>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">البريد الإلكتروني</label>
                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input v-model="form.email" type="email" dir="ltr" autocomplete="username"
                                   class="fld pr-11" placeholder="you@example.com" />
                        </div>
                        <span v-if="form.errors.email" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ form.errors.email }}</span>
                    </div>

                    <!-- Phone (optional) -->
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">
                            رقم الجوال
                            <span class="text-ink-muted font-normal">(اختياري)</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <input v-model="form.phone" type="tel" dir="ltr" autocomplete="tel"
                                   class="fld pr-11" placeholder="+9665XXXXXXXX" />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">كلمة المرور</label>
                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input v-model="form.password" :type="showPassword ? 'text' : 'password'" dir="ltr" autocomplete="new-password"
                                   class="fld pr-11 pl-11" placeholder="8 أحرف على الأقل" />
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute top-1/2 -translate-y-1/2 left-4 text-ink-muted hover:text-ink-body transition-colors">
                                <svg v-if="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <span v-if="form.errors.password" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ form.errors.password }}</span>
                    </div>

                    <!-- Password confirmation -->
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">تأكيد كلمة المرور</label>
                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <input v-model="form.password_confirmation" :type="showPassword ? 'text' : 'password'" dir="ltr" autocomplete="new-password"
                                   class="fld pr-11" placeholder="أعد كتابة كلمة المرور" />
                        </div>
                    </div>

                    <!-- Terms -->
                    <label class="flex items-start gap-2 cursor-pointer">
                        <input type="checkbox" v-model="form.terms" class="peer sr-only" required />
                        <span class="mt-0.5 w-4 h-4 shrink-0 rounded-md border border-soft peer-checked:bg-gradient-to-br peer-checked:from-[#2D4B7E] peer-checked:to-[#3DAFB9] peer-checked:border-transparent flex items-center justify-center transition-all">
                            <svg v-if="form.terms" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-[11.5px] text-ink-body font-medium leading-relaxed">
                            أوافق على
                            <Link href="/terms" class="text-[#3DAFB9] font-bold hover:underline">شروط الاستخدام</Link>
                            و
                            <Link href="/privacy" class="text-[#3DAFB9] font-bold hover:underline">سياسة الخصوصية</Link>
                        </span>
                    </label>

                    <!-- Submit -->
                    <button type="submit" :disabled="form.processing || !form.terms"
                            class="group relative w-full py-3.5 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[14px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.02] disabled:opacity-60 disabled:hover:scale-100 transition-transform overflow-hidden">
                        <span class="absolute inset-0 bg-gradient-to-l from-[#6BC8D2] to-[#3DAFB9] opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        <span class="relative inline-flex items-center justify-center gap-2">
                            <span v-if="form.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                            أنشئ الحساب الآن
                        </span>
                    </button>

                    <!-- Already have account -->
                    <p class="text-[12.5px] text-ink-body text-center pt-3 border-t border-soft">
                        لديك حساب بالفعل؟
                        <Link href="/login" class="text-[#3DAFB9] font-bold hover:underline">
                            سجّل دخولك
                        </Link>
                    </p>
                </form>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useTheme } from '@/composables/useTheme';

const { isDark } = useTheme();
const showPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<style scoped>
.fld { display:block; width:100%; height:44px; padding:0 14px; background: var(--bg-canvas, #F8FAFC); border: 1px solid var(--border-soft, rgba(15,23,42,0.1)); border-radius: 12px; color: var(--ink-primary, #1E293B); font-size: 13.5px; font-family: inherit; transition: border-color 200ms ease, box-shadow 200ms ease, background 200ms ease; }
.fld:focus { outline: none; border-color: #3DAFB9; background: white; box-shadow: 0 0 0 3px rgba(61,175,185,0.15); }
:root.dark .fld { background: rgba(10,23,41,0.4); color: #F1F5F9; border-color: rgba(107,200,210,0.15); }
:root.dark .fld:focus { background: rgba(18,36,64,0.6); }
</style>
