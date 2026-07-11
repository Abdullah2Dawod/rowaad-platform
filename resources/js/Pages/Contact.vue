<template>
    <MainLayout>
        <!-- ═══════════ HERO ═══════════ -->
        <section class="relative pt-28 sm:pt-32 lg:pt-40 pb-8 sm:pb-10 overflow-hidden bg-paper">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[10%] w-[560px] h-[560px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.13), transparent 70%);"></div>
                <div class="absolute bottom-0 left-[5%] w-[420px] h-[420px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.10), transparent 70%); animation-delay: 3s;"></div>
            </div>

            <div class="relative max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#3DAFB9]/10 border border-[#3DAFB9]/25 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#3DAFB9] animate-pulse"></span>
                    <span class="text-[10.5px] font-black text-[#3DAFB9] tracking-widest">تواصل معنا</span>
                </div>
                <h1 class="text-3xl lg:text-[2.8rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.15] mb-4 max-w-3xl">
                    نحن هنا لخدمتك<br>
                    <span class="text-gradient-brand">جاهزون للاستماع لطموحاتك</span>
                </h1>
                <p class="text-[14.5px] text-ink-body leading-[1.9] max-w-2xl">
                    اترك رسالتك وسنتواصل معك خلال 24 ساعة عمل. فريقنا الاستشاري جاهز للإجابة على استفساراتك
                    ومناقشة كيف يمكننا مساعدتك في تحقيق أهدافك.
                </p>
            </div>
        </section>

        <!-- ═══════════ BODY ═══════════ -->
        <section class="relative pb-24">
            <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <!-- Flash message -->
                <transition
                    enter-active-class="transition duration-500 ease-out"
                    enter-from-class="opacity-0 -translate-y-4"
                    enter-to-class="opacity-100 translate-y-0"
                >
                    <div v-if="successMsg" class="mb-6 p-4 rounded-2xl bg-green-500/10 border border-green-500/25 flex items-center gap-3">
                        <img :src="iconCheck" alt="" class="w-8 h-8 shrink-0" />
                        <div class="text-[13px] font-bold text-green-700 dark:text-green-400">{{ successMsg }}</div>
                    </div>
                </transition>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-7">
                    <!-- ═══════════ INFO SIDEBAR ═══════════ -->
                    <aside class="lg:col-span-2 space-y-4">
                        <!-- Info cards with 3D icons -->
                        <a v-for="(item, i) in contactInfo" :key="i"
                           :href="item.href"
                           :target="item.href?.startsWith('http') ? '_blank' : null"
                           class="contact-card group flex items-start gap-4 p-5 rounded-2xl bg-elevated border border-soft transition-all">
                            <div class="contact-card-icon relative w-14 h-14 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/10 to-[#2D4B7E]/8 border border-[#3DAFB9]/20 flex items-center justify-center shrink-0">
                                <img :src="item.icon3d" class="w-9 h-9 object-contain" :alt="item.label" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-[10px] tracking-[0.2em] uppercase text-[#3DAFB9] mb-1.5 font-black">{{ item.label }}</div>
                                <div class="text-[14.5px] font-black text-ink" :dir="item.dir || null">{{ item.value }}</div>
                                <div v-if="item.sub" class="text-[11.5px] text-ink-body mt-1">{{ item.sub }}</div>
                            </div>
                            <svg v-if="item.href" class="w-4 h-4 text-ink-muted group-hover:text-[#3DAFB9] group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-all mt-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>

                        <!-- Working hours card -->
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50] relative overflow-hidden">
                            <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full aurora-drift"
                                 style="background: radial-gradient(circle, rgba(61,175,185,0.30), transparent 70%);"></div>
                            <div class="relative flex items-start gap-3">
                                <img :src="iconClock" alt="" class="w-10 h-10 object-contain shrink-0" />
                                <div>
                                    <div class="text-[10px] tracking-widest uppercase text-[#6BC8D2] mb-1 font-black">ساعات العمل</div>
                                    <div class="text-[13.5px] font-black text-white">الأحد – الخميس</div>
                                    <div class="text-[12px] text-white/70 mt-0.5" dir="ltr">09:00 – 18:00 (GMT+3)</div>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <!-- ═══════════ FORM ═══════════ -->
                    <div class="lg:col-span-3">
                        <div class="relative rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-10 shadow-card overflow-hidden">
                            <!-- Decorative gradient bar top -->
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] via-[#3DAFB9] to-[#6BC8D2]"></div>

                            <div class="flex items-center gap-3 mb-6">
                                <img :src="iconMail" alt="" class="w-10 h-10 object-contain shrink-0" />
                                <div>
                                    <h2 class="text-xl font-black text-ink">أرسل رسالتك</h2>
                                    <p class="text-[12.5px] text-ink-body mt-0.5">جميع الحقول المُعلَّمة بـ * إلزامية</p>
                                </div>
                            </div>

                            <form @submit.prevent="submit" class="space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                    <PremiumField v-model="form.name" label="الاسم الكامل" required
                                                  icon="user" placeholder="اسمك الكامل"
                                                  :error="form.errors.name" />
                                    <PremiumField v-model="form.email" label="البريد الإلكتروني" required type="email"
                                                  icon="letter" dir="ltr" placeholder="you@example.com"
                                                  :error="form.errors.email" />
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                    <PremiumField v-model="form.phone" label="رقم الجوال" type="tel"
                                                  icon="phone" dir="ltr" placeholder="+9665XXXXXXXX"
                                                  :error="form.errors.phone" />
                                    <PremiumField v-model="form.subject" label="الموضوع" required
                                                  icon="tag" placeholder="ما موضوع رسالتك؟"
                                                  :error="form.errors.subject" />
                                </div>

                                <PremiumField v-model="form.message" label="رسالتك" required type="textarea"
                                              icon="document-text" placeholder="اكتب رسالتك هنا... كن مفصّلاً قدر الإمكان لنتمكّن من مساعدتك بشكل أفضل."
                                              :maxlength="2000" show-counter :rows="6"
                                              :error="form.errors.message" />

                                <div class="flex items-start gap-3 p-4 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5 border border-[#3DAFB9]/20">
                                    <img :src="iconLocked" alt="" class="w-6 h-6 object-contain shrink-0 mt-0.5" />
                                    <div class="text-[12px] text-ink-body leading-relaxed">
                                        <span class="font-bold text-ink">بياناتك مؤمّنة.</span>
                                        نحترم خصوصيتك ولن نشارك معلوماتك مع أي جهة خارجية.
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-soft">
                                    <p class="text-[11.5px] text-ink-muted">نرد خلال 24 ساعة عمل</p>
                                    <button type="submit" :disabled="form.processing"
                                            class="premium-submit inline-flex items-center gap-2 px-8 py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13.5px] font-black shadow-lg shadow-[#3DAFB9]/30 disabled:opacity-60 transition-all">
                                        <span v-if="form.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                        <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        إرسال الرسالة
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import PremiumField from '@/Components/UI/PremiumField.vue';

const page = usePage();
const successMsg = ref(page.props.flash?.success ?? null);

const form = useForm({
    name:    '',
    email:   '',
    phone:   '',
    subject: '',
    message: '',
});

const submit = () => form.post('/contact', {
    preserveScroll: true,
    onSuccess: () => {
        form.reset();
        successMsg.value = 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.';
        setTimeout(() => (successMsg.value = null), 6000);
    },
});

import { computed as vueComputed } from 'vue';
import { useTheme } from '@/composables/useTheme';
const { isDark } = useTheme();

// Solar icons in brand color — consistent monochrome look
const solarIcon = (slug) => {
    const color = isDark.value ? '6BC8D2' : '3DAFB9';
    return `https://api.iconify.design/solar:${slug}-bold-duotone.svg?color=%23${color}&width=64`;
};

const contactInfo = vueComputed(() => [
    {
        icon3d: solarIcon('letter'),
        label:  'البريد الإلكتروني',
        value:  'info@rowaad.org',
        dir:    'ltr',
        sub:    'للاستفسارات العامة',
        href:   'mailto:info@rowaad.org',
    },
    {
        icon3d: solarIcon('phone'),
        label:  'اتصل بنا',
        value:  '+966 54 758 6791',
        dir:    'ltr',
        sub:    'المقر الرئيسي · الرياض',
        href:   'tel:+966547586791',
    },
    {
        icon3d: solarIcon('map-point'),
        label:  'موقعنا',
        value:  'الرياض، المملكة العربية السعودية',
        sub:    'حي المروج · طريق الملك فهد',
    },
]);

// Brand-recolored icons for privacy notices and body embellishments
const iconLocked  = vueComputed(() => solarIcon('shield-keyhole'));
const iconMail    = vueComputed(() => solarIcon('letter'));
const iconClock   = vueComputed(() => solarIcon('clock-circle'));
const iconCheck   = vueComputed(() => solarIcon('check-circle'));
</script>

<style scoped>
.contact-card {
    position: relative;
}
.contact-card:hover {
    border-color: rgba(61, 175, 185, 0.4);
    transform: translateY(-2px);
    box-shadow: 0 12px 28px -10px rgba(45, 75, 126, 0.15);
}
.contact-card:hover .contact-card-icon {
    background: linear-gradient(135deg, rgba(61, 175, 185, 0.15), rgba(45, 75, 126, 0.12));
    box-shadow: 0 6px 18px -6px rgba(61, 175, 185, 0.35);
}
.contact-card-icon {
    transition: all 320ms cubic-bezier(0.22, 1, 0.36, 1);
}
.contact-card-icon img {
    transition: transform 400ms cubic-bezier(0.34, 1.5, 0.5, 1);
}
.contact-card:hover .contact-card-icon img {
    transform: scale(1.1) rotate(-4deg);
}

.premium-submit:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 14px 36px -8px rgba(61, 175, 185, 0.45);
}
</style>
