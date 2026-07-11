<template>
    <MainLayout>
        <PageHero
            :eyebrow="t('nav.consultants')"
            :title="t('pages.consultants.hero_title')"
            :subtitle="t('pages.consultants.hero_subtitle')"
        />

        <section class="relative py-16 lg:py-24">
            <div class="max-w-[1300px] mx-auto px-6 lg:px-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="(c, i) in consultants" :key="i"
                         class="group relative rounded-3xl bg-elevated border border-soft hover:border-[#3DAFB9]/30 hover:shadow-card-hover overflow-hidden transition-all duration-500">
                        <!-- Avatar placeholder with gradient -->
                        <div class="aspect-square bg-gradient-to-br from-[#3DAFB9]/15 via-[#2D4B7E]/10 to-[#3DAFB9]/8 flex items-center justify-center">
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center text-white text-4xl font-black shadow-brand">
                                {{ c.initials }}
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-ink mb-1">{{ c.name }}</h3>
                            <div class="text-xs text-[#3DAFB9] font-semibold mb-3 tracking-wider">{{ c.title }}</div>
                            <p class="text-sm text-ink-body leading-relaxed">{{ c.bio }}</p>
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

const { t } = useI18n();

const consultants = [
    { initials: 'م.ع', name: 'د. محمد العتيبي',   title: 'الرئيس التنفيذي · اقتصاد كلي',      bio: 'أكثر من 20 عاماً من الخبرة في الاستشارات الاقتصادية والاستراتيجية.' },
    { initials: 'س.ح', name: 'د. سارة الحربي',    title: 'رئيسة قسم دراسات الجدوى',        bio: 'خبيرة في تقييم المشاريع الاستثمارية والتحليل المالي.' },
    { initials: 'أ.ق', name: 'أ. أحمد القحطاني',  title: 'مستشار التخطيط الاستراتيجي',      bio: 'متخصص في بناء الخطط الاستراتيجية للمؤسسات الكبرى.' },
    { initials: 'ن.ز', name: 'د. نورة الزهراني',  title: 'خبيرة الاستشارات المالية',        bio: 'شهادات دولية في التحليل المالي وإدارة المخاطر.' },
    { initials: 'خ.س', name: 'م. خالد السالم',    title: 'مدير قسم الذكاء الاصطناعي',        bio: 'رائد في دمج تقنيات AI في الاستشارات الاقتصادية.' },
    { initials: 'ف.ع', name: 'أ. فهد العمري',    title: 'مستشار تطوير الأعمال',            bio: 'متخصص في نمو الشركات الناشئة والتوسع الإقليمي.' },
];
</script>
