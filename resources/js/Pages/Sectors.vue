<template>
    <MainLayout>
        <PageHero
            :eyebrow="t('nav.sectors')"
            :title="t('pages.sectors.hero_title')"
            :subtitle="t('pages.sectors.hero_subtitle')"
        />

        <section class="relative py-14 lg:py-20 bg-paper overflow-hidden">
            <!-- Ambient background -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-20 right-[8%] w-[380px] h-[380px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>
                <div class="absolute bottom-16 left-[10%] w-[320px] h-[320px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.08), transparent 70%); animation-delay: 3s;"></div>
                <div class="absolute inset-0 grid-pattern opacity-30"></div>
            </div>

            <div class="relative max-w-[1300px] mx-auto px-5 lg:px-10">
                <!-- Stats strip -->
                <div class="grid grid-cols-3 gap-3 mb-10 lg:mb-12 max-w-2xl mx-auto">
                    <div class="text-center p-4 rounded-2xl bg-elevated border border-soft">
                        <div class="text-[22px] lg:text-[26px] font-black text-gradient-brand leading-none">{{ sectors.length }}</div>
                        <div class="text-[10.5px] text-ink-muted mt-1.5 tracking-wide">قطاع رئيسي</div>
                    </div>
                    <div class="text-center p-4 rounded-2xl bg-elevated border border-soft">
                        <div class="text-[22px] lg:text-[26px] font-black text-gradient-brand leading-none">+120</div>
                        <div class="text-[10.5px] text-ink-muted mt-1.5 tracking-wide">مشروع منجَز</div>
                    </div>
                    <div class="text-center p-4 rounded-2xl bg-elevated border border-soft">
                        <div class="text-[22px] lg:text-[26px] font-black text-gradient-brand leading-none">2030</div>
                        <div class="text-[10.5px] text-ink-muted mt-1.5 tracking-wide">رؤية المملكة</div>
                    </div>
                </div>

                <!-- Sector cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
                    <div v-for="(s, i) in sectors" :key="i"
                         class="group relative rounded-[1.25rem] bg-elevated border border-soft hover:border-[#3DAFB9]/45 hover:-translate-y-1 hover:shadow-card-hover transition-all duration-500 p-5 overflow-hidden">
                        <!-- Corner accent gradient -->
                        <div class="absolute -top-16 -left-16 w-32 h-32 rounded-full bg-gradient-to-br from-[#3DAFB9]/12 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        <!-- Number chip -->
                        <span class="absolute top-3.5 right-3.5 rtl:right-auto rtl:left-3.5 text-[10px] font-black text-ink-muted/70 group-hover:text-[#3DAFB9] transition-colors">
                            {{ String(i + 1).padStart(2, '0') }}
                        </span>

                        <!-- Icon tile -->
                        <div class="relative w-14 h-14 mb-4 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/8 border border-[#3DAFB9]/25 flex items-center justify-center group-hover:scale-[1.08] group-hover:rotate-[-3deg] transition-transform duration-500">
                            <img :src="iconUrl(s.icon, isDark)" class="w-7 h-7" :alt="s.name" loading="lazy" />
                            <span class="absolute -inset-1 rounded-2xl border border-[#3DAFB9]/0 group-hover:border-[#3DAFB9]/30 transition-colors duration-500"></span>
                        </div>

                        <!-- Title + hairline -->
                        <h3 class="text-[13.5px] lg:text-[14px] font-black text-ink leading-snug mb-1.5 group-hover:text-[#2D4B7E] dark:group-hover:text-[#6BC8D2] transition-colors">
                            {{ s.name }}
                        </h3>
                        <div class="h-0.5 w-6 rounded-full bg-gradient-to-l from-[#3DAFB9] to-[#2D4B7E] group-hover:w-12 transition-all duration-500"></div>

                        <!-- Brief blurb -->
                        <p v-if="s.desc" class="text-[11.5px] text-ink-body leading-relaxed mt-2.5 line-clamp-2">{{ s.desc }}</p>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { useI18n } from '@/composables/useI18n';
import { useTheme } from '@/composables/useTheme';
import { computed } from 'vue';

const { t } = useI18n();
const { isDark } = useTheme();

const iconUrl = (slug, dark = false) => {
    const color = dark ? '6BC8D2' : '2D4B7E';
    return `https://api.iconify.design/solar:${slug}.svg?color=%23${color}&width=64`;
};

const sectors = computed(() => [
    { icon: 'buildings-2-bold-duotone',         name: t('sectors.real_estate'), desc: 'التطوير العقاري والاستثمار طويل الأمد وفق رؤية 2030.' },
    { icon: 'city-bold-duotone',                name: t('sectors.industry'),    desc: 'الصناعات التحويلية والصناعات المتقدمة والتصنيع الذكي.' },
    { icon: 'cart-large-2-bold-duotone',        name: t('sectors.retail'),      desc: 'التجزئة الحديثة والتجارة الإلكترونية والعلامات المحلية.' },
    { icon: 'health-bold-duotone',              name: t('sectors.healthcare'),  desc: 'الخدمات الصحية والتقنيات الطبية والعناية المتخصصة.' },
    { icon: 'square-academic-cap-bold-duotone', name: t('sectors.education'),   desc: 'التعليم النوعي والتدريب المهني والمهارات الرقمية.' },
    { icon: 'server-square-cloud-bold-duotone', name: t('sectors.technology'),  desc: 'الذكاء الاصطناعي والحوسبة السحابية والتحوّل الرقمي.' },
    { icon: 'leaf-bold-duotone',                name: t('sectors.agriculture'), desc: 'الأمن الغذائي والزراعة العضوية والاستدامة.' },
    { icon: 'suitcase-tag-bold-duotone',        name: t('sectors.tourism'),     desc: 'السياحة والضيافة والفعاليات الترفيهية والثقافية.' },
]);
</script>
