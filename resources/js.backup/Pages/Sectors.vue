<template>
    <MainLayout>
        <PageHero
            :eyebrow="t('nav.sectors')"
            :title="t('pages.sectors.hero_title')"
            :subtitle="t('pages.sectors.hero_subtitle')"
        />

        <section class="relative py-16 lg:py-24">
            <div class="max-w-[1300px] mx-auto px-6 lg:px-10">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
                    <div v-for="(s, i) in sectors" :key="i"
                         class="group relative aspect-square rounded-2xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover transition-all duration-500 flex flex-col items-center justify-center p-6 overflow-hidden">
                        <span class="absolute top-3 right-3 rtl:right-auto rtl:left-3 text-[10px] font-semibold text-ink-muted group-hover:text-[#3DAFB9] transition-colors">{{ String(i + 1).padStart(2, '0') }}</span>
                        <div class="w-16 h-16 mb-4 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/10 to-[#2D4B7E]/10 border border-[#3DAFB9]/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                            <img :src="iconUrl(s.icon, isDark)" class="w-9 h-9" :alt="s.name" />
                        </div>
                        <div class="text-sm font-bold text-ink text-center">{{ s.name }}</div>
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
    { icon: 'buildings-2-bold-duotone',      name: t('sectors.real_estate') },
    { icon: 'city-bold-duotone',             name: t('sectors.industry') },
    { icon: 'cart-large-2-bold-duotone',     name: t('sectors.retail') },
    { icon: 'health-bold-duotone',           name: t('sectors.healthcare') },
    { icon: 'square-academic-cap-bold-duotone', name: t('sectors.education') },
    { icon: 'server-square-cloud-bold-duotone', name: t('sectors.technology') },
    { icon: 'leaf-bold-duotone',             name: t('sectors.agriculture') },
    { icon: 'plane-bold-duotone',            name: t('sectors.tourism') },
]);
</script>
