<template>
    <MainLayout>
        <PageHero
            :eyebrow="t('nav.services')"
            :title="t('pages.services.hero_title')"
            :subtitle="t('pages.services.hero_subtitle')"
        />

        <section class="relative py-16 lg:py-24">
            <div class="max-w-[1300px] mx-auto px-6 lg:px-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="(svc, i) in services" :key="i"
                         class="group relative p-8 rounded-3xl bg-elevated border border-soft hover:border-[#3DAFB9]/30 hover:shadow-card-hover transition-all duration-500 overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-40 h-40 rounded-full bg-[#3DAFB9]/6 blur-3xl group-hover:bg-[#3DAFB9]/12 transition-all"></div>
                        <div class="relative">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/8 border border-[#3DAFB9]/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <img :src="iconUrl(svc.icon, isDark)" class="w-9 h-9" :alt="svc.title" />
                            </div>
                            <h3 class="text-xl font-bold text-ink mb-3">{{ svc.title }}</h3>
                            <p class="text-sm text-ink-body leading-relaxed mb-6">{{ svc.desc }}</p>
                            <a href="/contact" class="inline-flex items-center gap-2 text-sm font-bold text-[#2D4B7E] dark:text-[#6BC8D2] group-hover:text-[#3DAFB9]">
                                <span>{{ t('services.learn_more') }}</span>
                                <svg class="w-4 h-4 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
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

const services = computed(() => [
    { icon: 'chart-2-bold-duotone',           title: t('services.economic.title'),    desc: t('services.economic.desc') },
    { icon: 'documents-bold-duotone',         title: t('services.feasibility.title'), desc: t('services.feasibility.desc') },
    { icon: 'target-bold-duotone',            title: t('services.strategic.title'),   desc: t('services.strategic.desc') },
    { icon: 'case-minimalistic-bold-duotone', title: t('services.development.title'), desc: t('services.development.desc') },
    { icon: 'wallet-money-bold-duotone',      title: t('services.financial.title'),   desc: t('services.financial.desc') },
    { icon: 'cpu-bolt-bold-duotone',          title: t('services.ai.title'),          desc: t('services.ai.desc') },
]);
</script>
