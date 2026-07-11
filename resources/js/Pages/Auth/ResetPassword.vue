<template>
    <Head title="إعادة تعيين كلمة المرور" />

    <div class="min-h-screen flex items-center justify-center bg-paper px-6 py-10" dir="rtl">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute inset-0 grid-pattern opacity-40"></div>
            <div class="absolute top-0 right-[15%] w-[520px] h-[520px] rounded-full aurora-drift"
                 style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
        </div>

        <div class="relative w-full max-w-md">
            <a href="/" class="flex items-center justify-center gap-3 mb-8 group">
                <img :src="isDark ? '/images/rowaad-logo-symbol-dark.png' : '/images/rowaad-logo-symbol.png'"
                     alt="رواد" class="h-11 w-auto object-contain logo-glow" />
                <span class="font-black text-lg text-ink">رواد بلا حدود</span>
            </a>

            <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-9 shadow-card">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#3DAFB9]/10 border border-[#3DAFB9]/25 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#3DAFB9] animate-pulse"></span>
                    <span class="text-[10.5px] font-black text-[#3DAFB9] tracking-widest">استعادة الحساب</span>
                </div>
                <h1 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-2">تعيين كلمة مرور جديدة</h1>
                <p class="text-[13px] text-ink-body mb-6">أدخل كلمة مرور جديدة لحسابك.</p>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">البريد الإلكتروني</label>
                        <input v-model="form.email" type="email" dir="ltr" class="fld" required autocomplete="username" />
                        <span v-if="form.errors.email" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ form.errors.email }}</span>
                    </div>
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">كلمة المرور الجديدة</label>
                        <input v-model="form.password" type="password" dir="ltr" class="fld" required autocomplete="new-password" />
                        <span v-if="form.errors.password" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ form.errors.password }}</span>
                    </div>
                    <div>
                        <label class="block text-[12.5px] font-bold text-ink mb-1.5">تأكيد كلمة المرور</label>
                        <input v-model="form.password_confirmation" type="password" dir="ltr" class="fld" required autocomplete="new-password" />
                    </div>
                    <button type="submit" :disabled="form.processing"
                            class="w-full py-3 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[14px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.02] disabled:opacity-60 transition-transform">
                        تحديث كلمة المرور
                    </button>
                    <a href="/login" class="block text-center text-[12px] text-ink-body hover:text-ink font-bold">عودة لتسجيل الدخول</a>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { useTheme } from '@/composables/useTheme';

const props = defineProps({
    email: { type: String, required: true },
    token: { type: String, required: true },
});

const { isDark } = useTheme();

const form = useForm({
    token:                 props.token,
    email:                 props.email,
    password:              '',
    password_confirmation: '',
});

const submit = () => form.post('/reset-password', {
    onFinish: () => form.reset('password', 'password_confirmation'),
});
</script>

<style scoped>
.fld {
    display: block; width: 100%; height: 44px; padding: 0 14px;
    background: var(--bg-canvas); border: 1px solid var(--border-soft);
    border-radius: 12px; color: var(--ink-primary);
    font-size: 13.5px; font-family: inherit;
    transition: border-color 200ms ease, box-shadow 200ms ease;
}
.fld:focus { outline: none; border-color: #3DAFB9; box-shadow: 0 0 0 3px rgba(61,175,185,0.15); }
</style>
