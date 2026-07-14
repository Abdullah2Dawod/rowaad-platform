<template>
    <MainLayout>

        <!-- ================================================================ -->
        <!-- ABOUT HERO — editorial split + floating image collage              -->
        <!-- ================================================================ -->
        <section class="relative pt-28 sm:pt-32 lg:pt-40 pb-12 sm:pb-16 lg:pb-24 overflow-hidden bg-paper">
            <!-- Layered lively background -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-50"></div>
                <div class="absolute top-0 right-[10%] w-[560px] h-[560px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
                <div class="absolute bottom-0 left-[5%] w-[460px] h-[460px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.10), transparent 70%); animation-delay: 4s;"></div>
                <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[var(--bg-paper)] to-transparent"></div>
            </div>

            <div class="relative max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="grid grid-cols-12 gap-10 lg:gap-14 items-center">

                    <!-- TEXT (right in RTL) -->
                    <div class="col-span-12 lg:col-span-6" data-reveal>
                        <!-- Brand bar mark -->
                        <div class="flex items-center gap-3.5 mb-7">
                            <div class="flex items-end gap-[3px] h-5">
                                <span class="block w-[3px] h-3 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse"></span>
                                <span class="block w-[3px] h-5 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: .2s;"></span>
                                <span class="block w-[3px] h-3.5 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: .4s;"></span>
                            </div>
                            <span class="text-[11px] tracking-[0.25em] uppercase font-bold text-[#3DAFB9]">من نحن · قصتنا</span>
                        </div>

                        <h1 class="text-[2.2rem] md:text-[2.8rem] lg:text-[3.2rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.18] tracking-tight mb-6">
                            نسعى لأن نكون
                            <span class="relative inline-block">
                                <span class="text-gradient-brand">أيقونة رائدة</span>
                                <svg class="absolute -bottom-2 left-0 right-0 w-full pointer-events-none" viewBox="0 0 240 14" preserveAspectRatio="none">
                                    <path d="M3 9 Q 60 2, 120 7 T 237 7" stroke="#3DAFB9" stroke-width="2.5" fill="none" stroke-linecap="round" opacity="0.55"/>
                                </svg>
                            </span>
                            <br>في قطاع الاستشارات الاقتصادية
                        </h1>

                        <p class="text-[15px] lg:text-base text-ink-body leading-[1.9] mb-8 max-w-xl">
                            منذ تأسيسنا عام <span class="font-bold text-ink">2016</span> بترخيص رسمي من وزارة التجارة السعودية،
                            ونحن نقدّم الاستشارات الاقتصادية والإدارية ودراسات الجدوى لرواد الأعمال والمؤسسات،
                            بخبرة محلية عميقة ومعايير عالمية.
                        </p>

                        <!-- Credential chips -->
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-elevated border border-[#3DAFB9]/25 shadow-sm">
                                <img :src="iconUrl('diploma-verified-bold-duotone', isDark)" class="w-5 h-5" alt="" />
                                <span class="text-[12px] font-bold text-ink">ترخيص وزارة التجارة <span class="text-[#3DAFB9]" dir="ltr">#12047</span></span>
                            </div>
                            <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-elevated border border-soft shadow-sm">
                                <img :src="iconUrl('map-point-bold-duotone', isDark)" class="w-5 h-5" alt="" />
                                <span class="text-[12px] font-bold text-ink">الرياض · لندن</span>
                            </div>
                            <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-elevated border border-soft shadow-sm">
                                <img :src="iconUrl('calendar-mark-bold-duotone', isDark)" class="w-5 h-5" alt="" />
                                <span class="text-[12px] font-bold text-ink">+9 سنوات خبرة</span>
                            </div>
                        </div>
                    </div>

                    <!-- IMAGE COLLAGE (left in RTL) -->
                    <div class="col-span-12 lg:col-span-6" data-reveal style="transition-delay: 150ms;">
                        <div class="relative max-w-[520px] mx-auto" style="min-height: 420px;">
                            <!-- Decorative rotating dashed ring behind -->
                            <svg class="absolute -top-6 -right-6 w-44 h-44 opacity-60" style="animation: brandSpin 30s linear infinite;" viewBox="0 0 120 120" fill="none">
                                <circle cx="60" cy="60" r="57" stroke="#3DAFB9" stroke-width="1" stroke-dasharray="4 9" opacity="0.5"/>
                            </svg>
                            <div class="absolute -bottom-4 -left-4 w-32 h-32 dot-pattern opacity-60 rounded-2xl"></div>

                            <!-- Main image -->
                            <div class="absolute top-0 right-0 w-[72%] rounded-[1.75rem] overflow-hidden shadow-card-hover border-4 border-elevated">
                                <img :src="isDark ? '/images/about/team-consultation-dark.svg' : '/images/about/team-consultation.svg'"
                                     alt="فريق رواد الاستشاري" loading="lazy"
                                     class="w-full aspect-[4/4.4] object-cover" />
                                <div class="absolute inset-0 hidden dark:block bg-gradient-to-br dark:from-[#2D4B7E]/35 dark:to-[#3DAFB9]/20 mix-blend-multiply"></div>
                            </div>

                            <!-- Secondary overlapping image -->
                            <div class="absolute bottom-0 left-0 w-[52%] rounded-[1.5rem] overflow-hidden shadow-card-hover border-4 border-elevated animate-[float_7s_ease-in-out_infinite]">
                                <img :src="isDark ? '/images/about/partnership-dark.svg' : '/images/about/partnership.svg'"
                                     alt="شراكة موثوقة" loading="lazy"
                                     class="w-full aspect-[4/3.2] object-cover" />
                                <div class="absolute inset-0 hidden dark:block bg-gradient-to-br dark:from-[#2D4B7E]/40 dark:to-[#3DAFB9]/25 mix-blend-multiply"></div>
                            </div>

                            <!-- Floating year badge -->
                            <div class="absolute top-[38%] left-[8%] px-4 py-3 rounded-2xl bg-elevated border border-[#3DAFB9]/30 shadow-card-hover animate-[float_6s_ease-in-out_infinite_1s] z-10">
                                <div class="text-2xl font-black text-gradient-brand leading-none">2016</div>
                                <div class="text-[9px] text-ink-muted tracking-widest mt-1">سنة التأسيس</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <SectionBridge />

        <!-- ================================================================ -->
        <!-- MANIFESTO — big editorial statement                               -->
        <!-- ================================================================ -->
        <section class="relative py-14 lg:py-20 overflow-hidden">
            <div class="max-w-[1000px] mx-auto px-4 sm:px-6 lg:px-10 text-center" data-reveal>
                <p class="text-2xl md:text-3xl lg:text-[2.4rem] font-black leading-[1.6] text-ink">
                    نؤمن أن كل قرار اقتصادي ناجح يبدأ من
                    <span class="text-gradient-brand">فهمٍ عميق</span>،
                    ويُبنى على <span class="text-gradient-brand">بياناتٍ دقيقة</span>،
                    ويكتمل بـ<span class="text-gradient-brand">شراكةٍ صادقة</span>.
                </p>
            </div>
        </section>

        <!-- ================================================================ -->
        <!-- VISION & MISSION — asymmetric duo panels                           -->
        <!-- ================================================================ -->
        <section class="relative py-14 lg:py-20 bg-canvas overflow-hidden">
            <div class="absolute inset-0 dot-pattern opacity-25 pointer-events-none"></div>
            <div class="relative max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="grid grid-cols-12 gap-5 lg:gap-6">

                    <!-- VISION — dark premium panel -->
                    <div class="col-span-12 lg:col-span-7" data-reveal>
                        <div class="group relative h-full min-h-[290px] rounded-[1.75rem] bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50] p-8 lg:p-10 overflow-hidden">
                            <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full blur-[90px] aurora-drift" style="background: radial-gradient(circle, rgba(61,175,185,0.30), transparent 70%);"></div>
                            <div class="absolute inset-0 opacity-[0.07]" style="background-image: linear-gradient(rgba(107,200,210,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(107,200,210,0.5) 1px, transparent 1px); background-size: 56px 56px;"></div>
                            <!-- Oversized watermark icon -->
                            <img :src="iconUrlColor('eye-scan-bold-duotone', '6BC8D2')" alt=""
                                 class="absolute -bottom-8 -left-8 w-44 h-44 opacity-[0.10] rotate-[-10deg] group-hover:opacity-[0.18] group-hover:rotate-[-4deg] group-hover:scale-110 transition-all duration-700 pointer-events-none" />

                            <div class="relative">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 backdrop-blur flex items-center justify-center">
                                        <img :src="iconUrlColor('eye-scan-bold-duotone', '6BC8D2')" class="w-6 h-6" alt="" />
                                    </div>
                                    <span class="text-[10px] tracking-[0.3em] uppercase font-bold text-[#6BC8D2]">رؤيتنا</span>
                                </div>
                                <h2 class="text-2xl lg:text-3xl font-black text-white leading-snug mb-4">
                                    خدمات مبتكرة ومميزة<br>تصنع تطوراً ونمواً مستداماً
                                </h2>
                                <p class="text-white/65 leading-relaxed max-w-lg text-[14.5px]">
                                    نطمح أن نكون الخيار الأول لرواد الأعمال والمؤسسات في المنطقة، عبر تقديم خدمات استشارية
                                    تُحدث فرقاً حقيقياً وتقود نحو نموٍّ مستدام.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- MISSION — light elevated panel -->
                    <div class="col-span-12 lg:col-span-5" data-reveal style="transition-delay: 120ms;">
                        <div class="group relative h-full min-h-[290px] rounded-[1.75rem] bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover p-8 lg:p-10 overflow-hidden transition-all duration-500">
                            <div class="absolute -top-16 -left-16 w-56 h-56 rounded-full bg-[#3DAFB9]/8 blur-3xl group-hover:bg-[#3DAFB9]/15 transition-all duration-700"></div>
                            <img :src="iconUrl('target-bold-duotone', isDark)" alt=""
                                 class="absolute -bottom-7 -right-7 w-40 h-40 opacity-[0.06] dark:opacity-[0.10] rotate-[10deg] group-hover:opacity-[0.14] group-hover:rotate-[4deg] group-hover:scale-110 transition-all duration-700 pointer-events-none" />

                            <div class="relative">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/10 border border-[#3DAFB9]/25 flex items-center justify-center">
                                        <img :src="iconUrl('target-bold-duotone', isDark)" class="w-6 h-6" alt="" />
                                    </div>
                                    <span class="text-[10px] tracking-[0.3em] uppercase font-bold text-[#3DAFB9]">رسالتنا</span>
                                </div>
                                <h2 class="text-2xl lg:text-[1.65rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-snug mb-4">
                                    أفضل الأساليب الإدارية<br>والتدريبية المساندة للأعمال
                                </h2>
                                <p class="text-ink-body leading-relaxed text-[14.5px]">
                                    إيجاد وتطوير أفضل الطرق والمنهجيات التي تدعم أعمال عملائنا وتُمكّنهم من اتخاذ قرارات واثقة.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================================================================ -->
        <!-- VALUES — editorial credo rows (no cards)                           -->
        <!-- ================================================================ -->
        <section class="relative py-16 lg:py-24 overflow-hidden">
            <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="text-center mb-12" data-reveal>
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="h-px w-10 bg-gradient-to-r from-transparent to-[#3DAFB9]"></div>
                        <span class="text-[10px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">قيمنا</span>
                        <div class="h-px w-10 bg-gradient-to-l from-transparent to-[#3DAFB9]"></div>
                    </div>
                    <h2 class="text-[1.6rem] sm:text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF]">مبادئ لا نساوم عليها</h2>
                </div>

                <div class="divide-y divide-[var(--border-soft)]">
                    <div v-for="(v, i) in values" :key="i"
                         class="group relative flex items-center gap-5 lg:gap-8 py-6 lg:py-7 transition-all duration-500 hover:pr-3 rtl:hover:pr-3"
                         data-reveal :style="{ transitionDelay: (i * 80) + 'ms' }">
                        <!-- Number -->
                        <span class="text-4xl lg:text-5xl font-black shrink-0 w-16 text-[#2D4B7E]/25 dark:text-[#6BC8D2]/30 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-br group-hover:from-[#2D4B7E] group-hover:to-[#3DAFB9] transition-all duration-500"
                              style="-webkit-background-clip: text;">
                            {{ String(i + 1).padStart(2, '0') }}
                        </span>
                        <!-- Icon -->
                        <div class="shrink-0 w-11 h-11 rounded-xl bg-gradient-to-br from-[#3DAFB9]/10 to-[#2D4B7E]/8 border border-[#3DAFB9]/20 flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <img :src="iconUrl(v.icon, isDark)" class="w-5.5 h-5.5" :alt="v.title" />
                        </div>
                        <!-- Text -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg lg:text-xl font-black text-ink group-hover:text-[#2D4B7E] dark:group-hover:text-[#6BC8D2] transition-colors duration-500">{{ v.title }}</h3>
                            <p class="text-[13px] text-ink-body mt-1">{{ v.desc }}</p>
                        </div>
                        <!-- Grow line -->
                        <div class="hidden md:block relative h-px flex-1 max-w-[180px] overflow-hidden">
                            <div class="absolute inset-y-0 right-0 rtl:right-0 w-0 group-hover:w-full bg-gradient-to-l rtl:bg-gradient-to-l from-[#3DAFB9] to-transparent transition-all duration-700"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <SectionBridge />

        <!-- ================================================================ -->
        <!-- WHY US — advantages compact grid                                   -->
        <!-- ================================================================ -->
        <section class="relative py-16 lg:py-20 bg-canvas overflow-hidden">
            <div class="absolute top-0 left-[15%] w-[420px] h-[420px] rounded-full aurora-drift pointer-events-none"
                 style="background: radial-gradient(circle, rgba(61,175,185,0.09), transparent 70%);"></div>
            <div class="relative max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="text-center mb-12" data-reveal>
                    <span class="text-[10px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">لماذا رواد؟</span>
                    <h2 class="mt-3 text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF]">مزايا تجعلنا شريكك الأمثل</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div v-for="(a, i) in advantages" :key="i"
                         class="group relative p-6 rounded-2xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover hover:-translate-y-1.5 transition-all duration-500 overflow-hidden"
                         data-reveal :style="{ transitionDelay: (i * 80) + 'ms' }">
                        <img :src="iconUrl(a.icon, isDark)" alt=""
                             class="absolute -bottom-5 -left-5 w-24 h-24 opacity-[0.05] dark:opacity-[0.08] rotate-[-10deg] group-hover:opacity-[0.13] group-hover:scale-110 transition-all duration-700 pointer-events-none" />
                        <div class="relative">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/10 border border-[#3DAFB9]/25 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                <img :src="iconUrl(a.icon, isDark)" class="w-6 h-6" :alt="a.title" />
                            </div>
                            <h3 class="text-[15px] font-black text-ink mb-1.5">{{ a.title }}</h3>
                            <p class="text-[12.5px] text-ink-body leading-relaxed">{{ a.desc }}</p>
                            <div class="mt-4 h-0.5 w-7 rounded-full bg-gradient-to-r from-[#3DAFB9] to-[#2D4B7E] group-hover:w-full transition-all duration-500"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================================================================ -->
        <!-- JOURNEY TIMELINE                                                   -->
        <!-- ================================================================ -->
        <section class="relative py-16 lg:py-24">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="text-center mb-14" data-reveal>
                    <span class="text-[10px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">مسيرتنا</span>
                    <h2 class="mt-3 text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF]">من 2016 إلى اليوم</h2>
                </div>
                <div class="relative">
                    <!-- Connector line -->
                    <div class="hidden lg:block absolute top-[26px] left-10 right-10 h-px bg-gradient-to-r from-transparent via-[#3DAFB9]/40 to-transparent"></div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div v-for="(m, i) in milestones" :key="i"
                             class="group relative p-6 rounded-2xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover transition-all duration-500"
                             data-reveal :style="{ transitionDelay: (i * 90) + 'ms' }">
                            <div class="relative inline-flex items-center gap-2 mb-4">
                                <span class="relative flex w-3.5 h-3.5">
                                    <span class="absolute inset-0 rounded-full bg-[#3DAFB9]/40 group-hover:animate-ping"></span>
                                    <span class="relative w-3.5 h-3.5 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E]"></span>
                                </span>
                                <span class="text-[12px] text-[#3DAFB9] tracking-widest font-black">{{ m.year }}</span>
                            </div>
                            <div class="text-[16px] font-black text-ink mb-1">{{ m.title }}</div>
                            <div class="text-[13px] text-ink-body leading-relaxed">{{ m.desc }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </MainLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import SectionBridge from '@/Components/SectionBridge.vue';
import { useTheme } from '@/composables/useTheme';

const { isDark } = useTheme();

const iconUrl = (slug, dark = false) => {
    const color = dark ? '6BC8D2' : '2D4B7E';
    return `https://api.iconify.design/solar:${slug}.svg?color=%23${color}&width=64`;
};
const iconUrlColor = (slug, hex) =>
    `https://api.iconify.design/solar:${slug}.svg?color=%23${hex}&width=64`;

// === Values (from the legacy rowaad.org about page) ===
const values = [
    { icon: 'shield-check-bold-duotone',   title: 'المصداقية',        desc: 'الصدق والشفافية أساس تعاملنا مع كل عميل.' },
    { icon: 'hand-money-bold-duotone',     title: 'الأمانة',          desc: 'وضوح كامل في عروض الأسعار دون تكاليف خفية.' },
    { icon: 'medal-ribbons-star-bold-duotone', title: 'رضا العملاء', desc: 'نبذل الجهد الكافي المُنتج لنصل إلى رضاكم الكامل.' },
    { icon: 'refresh-square-bold-duotone', title: 'المرونة',          desc: 'نتكيّف مع احتياجاتك ونعدّل مسارنا لخدمة أهدافك.' },
];

// === Competitive advantages (legacy content, modernized) ===
const advantages = [
    { icon: 'clipboard-list-bold-duotone', title: 'تخطيط مرن',     desc: 'خطط أعمال مخصصة قابلة للتكيّف مع تغيّرات السوق.' },
    { icon: 'users-group-two-rounded-bold-duotone', title: 'شراكة موثوقة', desc: 'التزام حقيقي بنجاح عملائنا كشركاء لا كمزوّدين.' },
    { icon: 'lightbulb-bolt-bold-duotone', title: 'حلول مبتكرة',   desc: 'أفكار وتقنيات متقدمة تواكب تحوّلات الاقتصاد.' },
    { icon: 'scale-bold-duotone',          title: 'التزام مهني',   desc: 'معايير أخلاقية ومهنية صارمة في كل مشروع.' },
];

const milestones = [
    { year: '2016', title: 'التأسيس',        desc: 'انطلاق رواد بترخيص وزارة التجارة رقم 12047.' },
    { year: '2019', title: 'توسّع إقليمي',    desc: 'خدمة عملاء في عدة دول عربية وافتتاح شراكات.' },
    { year: '2022', title: 'مكتب لندن',      desc: 'حضور دولي يربط عملاءنا بالأسواق العالمية.' },
    { year: '2026', title: 'الجيل الجديد',    desc: 'منصة رقمية متكاملة مدعومة بالذكاء الاصطناعي.' },
];

// Generic scroll-reveal for [data-reveal]
onMounted(() => {
    const els = document.querySelectorAll('[data-reveal]');
    const io = new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (e.isIntersecting) {
                e.target.classList.add('revealed');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });
    els.forEach((el) => io.observe(el));
});
</script>
