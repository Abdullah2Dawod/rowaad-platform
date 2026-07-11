<template>
    <MainLayout>
        <PageHero
            :eyebrow="t('nav.contact')"
            :title="t('pages.contact.hero_title')"
            :subtitle="t('pages.contact.hero_subtitle')"
        />

        <section class="relative py-16 lg:py-24">
            <div class="max-w-[1200px] mx-auto px-6 lg:px-10 grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Info sidebar -->
                <aside class="space-y-4">
                    <div v-for="(item, i) in contactInfo" :key="i"
                         class="group flex items-start gap-4 p-5 rounded-2xl bg-elevated border border-soft hover:border-[#3DAFB9]/30 transition-all">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/10 border border-[#3DAFB9]/20 flex items-center justify-center shrink-0">
                            <img :src="iconUrl(item.icon, isDark)" class="w-6 h-6" :alt="item.label" />
                        </div>
                        <div>
                            <div class="text-[10px] tracking-widest uppercase text-ink-muted mb-1 font-semibold">{{ item.label }}</div>
                            <div class="text-sm font-bold text-ink" :dir="item.dir || null">{{ item.value }}</div>
                        </div>
                    </div>
                </aside>

                <!-- Form -->
                <form class="lg:col-span-2 p-8 lg:p-10 rounded-3xl bg-elevated border border-soft space-y-5" @submit.prevent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-ink-body mb-2 block">{{ t('pages.contact.form_name') }}</label>
                            <input type="text" class="w-full px-4 py-3 rounded-xl bg-paper border border-soft text-ink focus:border-[#3DAFB9] focus:outline-none transition-colors" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-ink-body mb-2 block">{{ t('pages.contact.form_email') }}</label>
                            <input type="email" class="w-full px-4 py-3 rounded-xl bg-paper border border-soft text-ink focus:border-[#3DAFB9] focus:outline-none transition-colors" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-ink-body mb-2 block">{{ t('pages.contact.form_phone') }}</label>
                            <input type="tel" class="w-full px-4 py-3 rounded-xl bg-paper border border-soft text-ink focus:border-[#3DAFB9] focus:outline-none transition-colors" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-ink-body mb-2 block">{{ t('pages.contact.form_subject') }}</label>
                            <input type="text" class="w-full px-4 py-3 rounded-xl bg-paper border border-soft text-ink focus:border-[#3DAFB9] focus:outline-none transition-colors" />
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-ink-body mb-2 block">{{ t('pages.contact.form_message') }}</label>
                        <textarea rows="5" class="w-full px-4 py-3 rounded-xl bg-paper border border-soft text-ink focus:border-[#3DAFB9] focus:outline-none transition-colors resize-none"></textarea>
                    </div>
                    <button type="submit"
                            class="group relative inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full text-white overflow-hidden bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] shadow-lg shadow-[#3DAFB9]/25 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        <span class="text-[14px] font-bold whitespace-nowrap">{{ t('pages.contact.form_submit') }}</span>
                        <svg class="w-4 h-4 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                </form>
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

const contactInfo = computed(() => [
    { icon: 'letter-bold-duotone',     label: t('cta.email'), value: 'info@rowaad.org' },
    { icon: 'phone-bold-duotone',      label: t('cta.call'),  value: '+966 5XX XXX XXX', dir: 'ltr' },
    { icon: 'map-point-bold-duotone',  label: t('cta.location'), value: t('common.location') },
]);
</script>
