<template>
    <MainLayout>
        <PageHero
            :eyebrow="t('nav.blog')"
            :title="t('pages.blog.hero_title')"
            :subtitle="t('pages.blog.hero_subtitle')"
        />

        <section class="relative py-16 lg:py-24">
            <div class="max-w-[1300px] mx-auto px-6 lg:px-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <article v-for="(post, i) in posts" :key="i"
                             class="group relative rounded-3xl bg-elevated border border-soft hover:border-[#3DAFB9]/30 hover:shadow-card-hover overflow-hidden transition-all duration-500">
                        <!-- Thumbnail placeholder -->
                        <div class="aspect-video bg-gradient-to-br from-[#3DAFB9]/12 via-[#2D4B7E]/8 to-[#3DAFB9]/6 flex items-center justify-center border-b border-soft">
                            <div class="text-5xl opacity-40">📊</div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-[10px] font-bold text-[#3DAFB9] tracking-wider uppercase">{{ post.category }}</span>
                                <span class="w-1 h-1 rounded-full bg-ink-muted"></span>
                                <span class="text-[11px] text-ink-muted">{{ post.date }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-ink mb-2 group-hover:text-[#3DAFB9] transition-colors leading-snug">{{ post.title }}</h3>
                            <p class="text-sm text-ink-body leading-relaxed mb-4">{{ post.excerpt }}</p>
                            <a href="#" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#2D4B7E] dark:text-[#6BC8D2] hover:text-[#3DAFB9]">
                                <span>{{ t('common.read_more') }}</span>
                                <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { useI18n } from '@/composables/useI18n';

const { t } = useI18n();

const posts = [
    { category: 'اقتصاد', date: '2026/06/28', title: 'مؤشرات الاقتصاد السعودي في النصف الأول من 2026',
      excerpt: 'تحليل شامل لأداء الاقتصاد السعودي ومؤشرات النمو في القطاعات الحيوية.' },
    { category: 'دراسات جدوى', date: '2026/06/22', title: 'كيف تُعدّ دراسة جدوى مالية احترافية؟',
      excerpt: 'دليلك الشامل لخطوات إعداد دراسة جدوى مالية دقيقة تضمن نجاح مشروعك.' },
    { category: 'استراتيجية', date: '2026/06/15', title: 'رؤية 2030 وفرص الاستثمار الجديدة',
      excerpt: 'استعراض للفرص الاستثمارية النوعية في ظل رؤية المملكة 2030.' },
    { category: 'تقنية', date: '2026/06/08', title: 'الذكاء الاصطناعي وتحوّل قطاع الاستشارات',
      excerpt: 'كيف يعيد الذكاء الاصطناعي تشكيل صناعة الاستشارات الاقتصادية؟' },
    { category: 'ريادة', date: '2026/05/30', title: 'أخطاء يجب تجنّبها عند تأسيس شركتك الناشئة',
      excerpt: 'أبرز التحديات التي تواجه رواد الأعمال وكيفية تجاوزها بنجاح.' },
    { category: 'مالية', date: '2026/05/22', title: 'استراتيجيات إدارة التدفّق النقدي للشركات',
      excerpt: 'أفضل الممارسات لضمان استقرار مالي مستدام لشركتك.' },
];
</script>
