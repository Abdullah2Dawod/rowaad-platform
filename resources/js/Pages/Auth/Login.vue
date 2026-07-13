<template>
    <Head title="تسجيل الدخول" />

    <div class="min-h-screen flex flex-col lg:flex-row bg-paper" dir="rtl">
        <!-- ================== BRAND PANEL (LEFT in RTL / hidden on mobile) ================== -->
        <aside class="hidden lg:flex lg:w-[46%] xl:w-[42%] relative overflow-hidden">
            <!-- Gradient base -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50]"></div>
            <!-- Aurora orbs -->
            <div class="absolute -top-24 -right-24 w-[520px] h-[520px] rounded-full aurora-drift"
                 style="background: radial-gradient(circle, rgba(61,175,185,0.35), transparent 70%);"></div>
            <div class="absolute -bottom-24 -left-24 w-[480px] h-[480px] rounded-full aurora-drift"
                 style="background: radial-gradient(circle, rgba(45,75,126,0.45), transparent 70%); animation-delay: 3s;"></div>
            <!-- Grid overlay -->
            <div class="absolute inset-0 opacity-[0.07]" style="background-image: linear-gradient(rgba(107,200,210,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(107,200,210,0.5) 1px, transparent 1px); background-size: 56px 56px;"></div>

            <!-- Content -->
            <div class="relative flex flex-col justify-between w-full p-12 xl:p-16">
                <!-- Logo -->
                <a href="/" class="inline-flex items-center gap-3 self-start group">
                    <img src="/images/rowaad-logo-symbol-dark.png" alt="رواد" class="h-12 w-auto object-contain logo-glow group-hover:scale-105 transition-transform" />
                    <span class="text-white/95 font-black text-lg tracking-tight">رواد بلا حدود</span>
                </a>

                <!-- Middle: rotating decoration + tagline -->
                <div class="relative">
                    <!-- Decorative rotating ring -->
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
                        <span class="text-[10px] tracking-[0.3em] uppercase font-bold text-[#6BC8D2]">بوابة العمل</span>
                    </div>

                    <h1 class="relative text-white text-3xl xl:text-[2.4rem] font-black leading-tight mb-4">
                        أهلاً بعودتك<br>
                        <span class="bg-clip-text text-transparent bg-gradient-to-l from-[#6BC8D2] to-[#3DAFB9]">إلى منصة رواد</span>
                    </h1>
                    <p class="relative text-white/60 text-[14px] leading-[1.9] max-w-md">
                        نافذتك لإدارة الاستشارات والحجوزات والعملاء بأدوات احترافية،
                        مصمّمة خصيصاً لتُبسّط عملك اليومي وتُطلق العنان لخبرتك.
                    </p>
                </div>

                <!-- Bottom stats -->
                <div class="relative grid grid-cols-3 gap-4 pt-8 border-t border-white/10">
                    <div>
                        <div class="text-2xl font-black text-white">+500</div>
                        <div class="text-[10px] text-white/50 tracking-wider mt-1">مشروع ناجح</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-l from-[#6BC8D2] to-[#3DAFB9]">98%</div>
                        <div class="text-[10px] text-white/50 tracking-wider mt-1">رضا العملاء</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-white">+200M</div>
                        <div class="text-[10px] text-white/50 tracking-wider mt-1">قيمة المشاريع</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ================== FORM PANEL (RIGHT in RTL) ================== -->
        <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-10 py-8 sm:py-10 lg:py-0 relative">
            <!-- Background decoration (mobile only for depth) -->
            <div class="lg:hidden absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[10%] w-[400px] h-[400px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
            </div>

            <div class="relative w-full max-w-md">
                <!-- Mobile logo -->
                <a href="/" class="flex lg:hidden items-center gap-3 mb-8 justify-center group">
                    <img :src="isDark ? '/images/rowaad-logo-symbol-dark.png' : '/images/rowaad-logo-symbol.png'"
                         alt="رواد" class="h-11 w-auto object-contain logo-glow" />
                    <span class="font-black text-lg text-ink">رواد بلا حدود</span>
                </a>

                <!-- Status flash -->
                <div v-if="status" class="mb-6 p-4 rounded-2xl bg-green-500/10 border border-green-500/25 text-[13px] text-green-700 dark:text-green-400 font-bold">
                    {{ status }}
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#3DAFB9]/10 border border-[#3DAFB9]/25 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#3DAFB9] animate-pulse"></span>
                        <span class="text-[10.5px] font-black text-[#3DAFB9] tracking-widest">تسجيل الدخول</span>
                    </div>
                    <h2 class="text-[1.5rem] sm:text-2xl lg:text-3xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-tight mb-2">
                        {{ headings.title }}
                    </h2>
                    <p class="text-[13px] text-ink-body">
                        {{ headings.subtitle }}
                    </p>
                </div>

                <!-- ==================== LOGIN FORM ==================== -->
                <form v-if="mode === 'login'" @submit.prevent="submitLogin" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">البريد الإلكتروني</label>
                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input v-model="loginForm.email" type="email" dir="ltr" autofocus autocomplete="username"
                                   class="fld pr-11" placeholder="you@example.com" />
                        </div>
                        <span v-if="loginForm.errors.email" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ loginForm.errors.email }}</span>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-baseline justify-between mb-1.5">
                            <label class="text-[12.5px] font-bold text-ink">كلمة المرور</label>
                            <button type="button" @click="mode = 'forgot'"
                                    class="text-[11px] text-[#3DAFB9] font-bold hover:text-[#2D4B7E] dark:hover:text-[#6BC8D2] transition-colors">
                                نسيت كلمة المرور؟
                            </button>
                        </div>
                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input v-model="loginForm.password" :type="showPassword ? 'text' : 'password'" dir="ltr" autocomplete="current-password"
                                   class="fld pr-11 pl-11" placeholder="••••••••" />
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute top-1/2 -translate-y-1/2 left-4 text-ink-muted hover:text-ink-body transition-colors">
                                <svg v-if="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <span v-if="loginForm.errors.password" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ loginForm.errors.password }}</span>
                    </div>

                    <!-- Remember -->
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" v-model="loginForm.remember" class="peer sr-only" />
                        <span class="w-4 h-4 rounded-md border border-soft peer-checked:bg-gradient-to-br peer-checked:from-[#2D4B7E] peer-checked:to-[#3DAFB9] peer-checked:border-transparent flex items-center justify-center transition-all">
                            <svg v-if="loginForm.remember" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-[12.5px] text-ink-body font-medium">تذكّرني على هذا الجهاز</span>
                    </label>

                    <!-- Submit -->
                    <button type="submit" :disabled="loginForm.processing"
                            class="group relative w-full py-3.5 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[14px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.02] disabled:opacity-60 disabled:hover:scale-100 transition-transform overflow-hidden">
                        <span class="absolute inset-0 bg-gradient-to-l from-[#6BC8D2] to-[#3DAFB9] opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        <span class="relative inline-flex items-center justify-center gap-2">
                            <span v-if="loginForm.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            دخول إلى لوحة التحكم
                        </span>
                    </button>

                    <!-- Not registered hint -->
                    <div class="pt-5 border-t border-soft space-y-3">
                        <p class="text-[12px] text-ink-body text-center leading-relaxed">
                            جديد على المنصة؟
                            <Link href="/register" class="text-[#3DAFB9] font-bold hover:underline">أنشئ حساباً</Link>
                            لحجز الاستشارات.
                        </p>
                        <p class="text-[12px] text-ink-body text-center leading-relaxed">
                            هل تريد الانضمام كمستشار؟
                            <a href="/become-a-consultant" class="text-[#3DAFB9] font-bold hover:underline">قدّم طلبك من هنا</a>
                        </p>
                    </div>
                </form>

                <!-- ==================== QUICK SIGNUP FORM ==================== -->
                <form v-else-if="mode === 'signup'" @submit.prevent="submitSignup" class="space-y-4">
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">الاسم الكامل</label>
                        <input v-model="signupForm.name" type="text" class="fld" placeholder="اسمك الكامل" autofocus />
                        <span v-if="signupForm.errors.name" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ signupForm.errors.name }}</span>
                    </div>
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">البريد الإلكتروني</label>
                        <input v-model="signupForm.email" type="email" dir="ltr" class="fld" placeholder="you@example.com" autocomplete="username" />
                        <span v-if="signupForm.errors.email" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ signupForm.errors.email }}</span>
                    </div>
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">رقم الجوال (اختياري)</label>
                        <input v-model="signupForm.phone" type="tel" dir="ltr" class="fld" placeholder="+9665XXXXXXXX" />
                    </div>
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">كلمة المرور</label>
                        <input v-model="signupForm.password" type="password" dir="ltr" class="fld" placeholder="8 أحرف على الأقل" autocomplete="new-password" />
                        <span v-if="signupForm.errors.password" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ signupForm.errors.password }}</span>
                    </div>
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">تأكيد كلمة المرور</label>
                        <input v-model="signupForm.password_confirmation" type="password" dir="ltr" class="fld" autocomplete="new-password" />
                    </div>

                    <button type="submit" :disabled="signupForm.processing"
                            class="w-full py-3.5 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[14px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.02] disabled:opacity-60 transition-transform">
                        <span class="inline-flex items-center justify-center gap-2">
                            <span v-if="signupForm.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            إنشاء الحساب
                        </span>
                    </button>

                    <button type="button" @click="mode = 'login'"
                            class="w-full inline-flex items-center justify-center gap-1.5 text-[13px] text-ink-body hover:text-ink font-bold">
                        <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                        لديك حساب؟ سجّل دخول
                    </button>
                </form>

                <!-- ==================== FORGOT PASSWORD FORM ==================== -->
                <form v-else-if="mode === 'forgot'" @submit.prevent="submitForgot" class="space-y-5">
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">البريد الإلكتروني</label>
                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input v-model="forgotForm.email" type="email" dir="ltr" autofocus
                                   class="fld pr-11" placeholder="you@example.com" />
                        </div>
                        <span v-if="forgotForm.errors.email" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ forgotForm.errors.email }}</span>
                    </div>

                    <button type="submit" :disabled="forgotForm.processing"
                            class="w-full py-3.5 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[14px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.02] disabled:opacity-60 transition-transform">
                        <span class="inline-flex items-center justify-center gap-2">
                            <span v-if="forgotForm.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            إرسال رابط إعادة التعيين
                        </span>
                    </button>

                    <button type="button" @click="mode = 'login'"
                            class="w-full inline-flex items-center justify-center gap-1.5 text-[13px] text-ink-body hover:text-ink font-bold">
                        <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                        عودة لتسجيل الدخول
                    </button>
                </form>

                <!-- Footer note -->
                <p class="mt-8 text-center text-[11px] text-ink-muted">
                    بالدخول أنت توافق على
                    <Link href="/terms" class="text-[#3DAFB9] hover:text-[#2D4B7E] dark:hover:text-[#6BC8D2] font-bold underline underline-offset-2 decoration-[#3DAFB9]/30 hover:decoration-[#3DAFB9]">شروط الاستخدام</Link>
                    و
                    <Link href="/privacy" class="text-[#3DAFB9] hover:text-[#2D4B7E] dark:hover:text-[#6BC8D2] font-bold underline underline-offset-2 decoration-[#3DAFB9]/30 hover:decoration-[#3DAFB9]">سياسة الخصوصية</Link>.
                </p>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useTheme } from '@/composables/useTheme';

defineProps({ status: String });

const { isDark } = useTheme();

const mode         = ref('login'); // 'login' | 'signup' | 'forgot'
const showPassword = ref(false);

const loginForm = useForm({ email: '', password: '', remember: false });
const forgotForm = useForm({ email: '' });
const signupForm = useForm({
    name: '', email: '', phone: '', password: '', password_confirmation: '',
});

const submitLogin  = () => loginForm.post('/login', { onFinish: () => loginForm.reset('password') });
const submitForgot = () => forgotForm.post('/forgot-password', {
    onSuccess: () => forgotForm.reset('email'),
});
const submitSignup = () => signupForm.post('/signup', {
    onError: () => signupForm.reset('password', 'password_confirmation'),
});

const headings = computed(() => ({
    login:  { title: 'ادخل إلى حسابك', subtitle: 'ادخل بريدك وكلمة المرور للمتابعة.' },
    signup: { title: 'إنشاء حساب جديد', subtitle: 'خطوات بسيطة لتبدأ رحلتك مع رواد.' },
    forgot: { title: 'استعادة كلمة المرور', subtitle: 'أدخل بريدك وسنرسل لك رابط إعادة التعيين.' },
}[mode.value]));
</script>

<style scoped>
.fld {
    display: block; width: 100%; height: 46px; padding: 0 14px;
    background: var(--bg-canvas); border: 1px solid var(--border-soft);
    border-radius: 12px; color: var(--ink-primary);
    font-size: 13.5px; font-family: inherit;
    transition: border-color 200ms ease, box-shadow 200ms ease;
}
.fld:focus { outline: none; border-color: #3DAFB9; box-shadow: 0 0 0 3px rgba(61,175,185,0.15); }
.fld::placeholder { color: var(--ink-muted); }
</style>
