<template>
    <MainLayout>
        <!-- ═══════════ HERO ═══════════ -->
        <section class="relative pt-28 sm:pt-32 lg:pt-40 pb-10 sm:pb-14 overflow-hidden bg-paper">
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
                    <span class="text-[10.5px] font-black text-[#3DAFB9] tracking-widest">خدماتنا · B2B</span>
                </div>
                <h1 class="text-[1.75rem] sm:text-3xl lg:text-[2.8rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.15] mb-4 max-w-3xl">
                    خدمات استشارية متكاملة<br>
                    <span class="text-gradient-brand">مصمّمة لطموحات الشركات والمؤسسات</span>
                </h1>
                <p class="text-[14.5px] text-ink-body leading-[1.9] max-w-2xl">
                    من الاستشارات الاقتصادية إلى تأسيس الشركات الأجنبية والحوكمة والتدريب — نقدّم منظومة خدمات مكاملة
                    تُلبّي احتياجات الشركات (B2B) في كل مرحلة من رحلة النمو.
                </p>
            </div>
        </section>

        <!-- ═══════════ GRID ═══════════ -->
        <section class="relative pb-16 sm:pb-20 lg:pb-24">
            <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <Link v-for="s in catalog" :key="s.slug" :href="`/services/${s.slug}`"
                          class="group relative flex flex-col rounded-3xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover overflow-hidden transition-all duration-500 hover:-translate-y-1.5">
                        <!-- Cover -->
                        <div class="relative aspect-[16/9] overflow-hidden bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9]">
                            <img :src="s.hero_image" :alt="s.title" loading="lazy"
                                 class="w-full h-full object-cover saturate-[0.7] group-hover:saturate-100 group-hover:scale-[1.05] transition-all duration-[900ms]" />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A1729]/80 via-[#0A1729]/20 to-transparent"></div>

                            <div v-if="s.featured" class="absolute top-3 left-3 rtl:left-auto rtl:right-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white text-[10px] font-black shadow-md">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                خدمة رئيسية
                            </div>

                            <div class="absolute bottom-3 right-3 rtl:right-3 w-12 h-12 rounded-2xl bg-white/95 backdrop-blur border border-white flex items-center justify-center shadow-md">
                                <img :src="iconUrl(s.icon)" class="w-6 h-6" alt="" />
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="text-[16px] font-black text-ink group-hover:text-[#2D4B7E] dark:group-hover:text-[#6BC8D2] transition-colors leading-snug">{{ s.title }}</h3>
                            <p class="text-[12px] text-[#3DAFB9] font-bold mt-1">{{ s.tagline }}</p>
                            <p class="text-[12.5px] text-ink-body leading-relaxed mt-3 line-clamp-3 flex-1">{{ s.summary }}</p>

                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-soft">
                                <span class="inline-flex items-center gap-1.5 text-[12.5px] font-black text-[#3DAFB9] group-hover:gap-2.5 transition-all">
                                    اطّلع على التفاصيل
                                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </span>
                                <span class="text-[10.5px] text-ink-muted">{{ s.includes?.length || 0 }} عناصر</span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useTheme } from '@/composables/useTheme';

defineProps({ catalog: Array });
const { isDark } = useTheme();

const iconUrl = (slug) => {
    const color = isDark.value ? '6BC8D2' : '3DAFB9';
    return `https://api.iconify.design/solar:${slug}.svg?color=%23${color}&width=48`;
};
</script>
