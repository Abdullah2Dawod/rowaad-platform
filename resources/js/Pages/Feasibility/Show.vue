<template>
    <MainLayout>
        <Head :title="study.title" />

        <!-- ═══════════ HERO ═══════════ -->
        <section class="relative pt-28 lg:pt-36 pb-10 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[8%] w-[500px] h-[500px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.14), transparent 70%);"></div>
                <div class="absolute bottom-0 left-[10%] w-[420px] h-[420px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.10), transparent 70%); animation-delay: 3s;"></div>
            </div>

            <div class="relative max-w-[1200px] mx-auto px-5 lg:px-10">
                <Link href="/feasibility-studies"
                      class="inline-flex items-center gap-2 text-[12px] text-ink-body hover:text-[#3DAFB9] font-bold mb-6 transition-colors">
                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    كل دراسات الجدوى
                </Link>

                <div class="grid grid-cols-12 gap-8 items-start">
                    <!-- Left column -->
                    <div class="col-span-12 lg:col-span-8">
                        <div class="flex items-center gap-2 flex-wrap mb-4">
                            <span v-if="study.source === 'admin'"
                                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gradient-to-l from-[#2D4B7E]/12 to-[#3DAFB9]/12 text-[10.5px] font-black text-[#2D4B7E] dark:text-[#6BC8D2] border border-[#3DAFB9]/30">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                من طرف رواد
                            </span>
                            <span v-if="study.specialization"
                                  class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-[#3DAFB9]/10 border border-[#3DAFB9]/25 text-[10.5px] font-black text-[#3DAFB9] tracking-wider">
                                {{ study.specialization.name_ar }}
                            </span>
                            <span v-if="study.sector"
                                  class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-paper border border-soft text-[10.5px] font-bold text-ink-body">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                {{ study.sector }}
                            </span>
                        </div>

                        <h1 class="text-[1.7rem] sm:text-3xl lg:text-[2.4rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-tight mb-4">
                            {{ study.title }}
                        </h1>
                        <p class="text-[15px] text-ink-body leading-[1.9]">{{ study.excerpt }}</p>

                        <!-- Quick stats bar -->
                        <div class="grid grid-cols-3 gap-3 mt-6">
                            <div class="rounded-xl bg-elevated border border-soft px-3 py-2.5 text-center">
                                <div class="text-[10px] text-ink-muted mb-1">صفحات</div>
                                <div class="text-[15px] font-black text-ink">{{ study.pages_count || '—' }}</div>
                            </div>
                            <div class="rounded-xl bg-elevated border border-soft px-3 py-2.5 text-center">
                                <div class="text-[10px] text-ink-muted mb-1">مشاهدات</div>
                                <div class="text-[15px] font-black text-ink">{{ study.views_count }}</div>
                            </div>
                            <div class="rounded-xl bg-elevated border border-soft px-3 py-2.5 text-center">
                                <div class="text-[10px] text-ink-muted mb-1">تحميلات</div>
                                <div class="text-[15px] font-black text-ink">{{ study.purchases_count }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right column: purchase card -->
                    <aside class="col-span-12 lg:col-span-4">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card sticky top-28">
                            <div class="text-center mb-5">
                                <div v-if="study.is_free">
                                    <span class="text-3xl font-black text-green-600">مجاناً</span>
                                    <p class="text-[11px] text-ink-muted mt-1">تحميل فوري بدون تسجيل</p>
                                </div>
                                <template v-else>
                                    <div class="text-[10.5px] text-ink-muted mb-1">السعر شامل</div>
                                    <span class="text-3xl font-black text-gradient-brand">{{ formatPrice(study.price) }}</span>
                                    <span class="text-[13px] text-ink-muted mr-1">ر.س</span>
                                </template>
                            </div>
                            <button class="w-full py-3 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-black shadow-md hover:scale-[1.02] transition-transform">
                                {{ study.is_free ? 'حمّل الآن مجاناً' : 'شراء وتحميل' }}
                            </button>
                            <p class="text-[10.5px] text-ink-muted text-center mt-2">دفع آمن · Mada · Apple Pay · STC Pay</p>

                            <ul class="mt-5 pt-5 border-t border-soft space-y-2.5 text-[12px]">
                                <li v-if="study.pages_count" class="flex items-center justify-between">
                                    <span class="text-ink-muted">عدد الصفحات</span>
                                    <span class="font-bold text-ink">{{ study.pages_count }}</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-ink-muted">اللغة</span>
                                    <span class="font-bold text-ink">{{ study.language === 'ar' ? 'العربية' : 'English' }}</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-ink-muted">التنسيق</span>
                                    <span class="font-bold text-ink">PDF + Excel</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-ink-muted">آخر تحديث</span>
                                    <span class="font-bold text-ink">2026</span>
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <!-- ═══════════ RICH CONTENT SECTIONS ═══════════ -->
        <div class="bg-paper">
            <div class="max-w-[1200px] mx-auto px-5 lg:px-10 pb-16 grid grid-cols-12 gap-8">
                <div class="col-span-12 lg:col-span-8 space-y-10">

                    <!-- Executive Summary -->
                    <section v-if="rich.summary" class="rounded-[1.5rem] bg-elevated border border-soft p-6 lg:p-8">
                        <SectionHeader icon="clipboard" title="الملخص التنفيذي" />
                        <p class="text-[14px] text-ink-body leading-[2] mt-4">{{ rich.summary }}</p>
                    </section>

                    <!-- Financial highlights (cards grid) -->
                    <section v-if="rich.financials?.length" class="rounded-[1.5rem] bg-gradient-to-br from-[#EAF6F7] to-white dark:from-[#0F2340]/60 dark:to-[#122440]/40 border border-[#3DAFB9]/25 p-6 lg:p-8">
                        <SectionHeader icon="coin" title="المؤشرات المالية الرئيسية" subtitle="أرقام مدروسة بعناية بناءً على تحليل السوق" />
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-5">
                            <div v-for="(f, i) in rich.financials" :key="i"
                                 class="group relative rounded-xl bg-white dark:bg-[#0A1729]/60 border border-[#3DAFB9]/20 p-4 hover:border-[#3DAFB9]/50 hover:shadow-card transition-all duration-300">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/10 flex items-center justify-center">
                                        <MetricIcon :name="f.icon" />
                                    </div>
                                </div>
                                <div class="text-[11px] text-ink-muted mb-1 font-medium">{{ f.label }}</div>
                                <div class="text-[17px] font-black text-[#2D4B7E] dark:text-[#6BC8D2] leading-tight">{{ f.value }}</div>
                            </div>
                        </div>
                    </section>

                    <!-- Target Market -->
                    <section v-if="rich.target_market?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-6 lg:p-8">
                        <SectionHeader icon="users" title="السوق المستهدف" subtitle="الشرائح المستهدفة الرئيسية لهذا المشروع" />
                        <ul class="mt-4 space-y-2.5">
                            <li v-for="(t, i) in rich.target_market" :key="i"
                                class="flex items-start gap-3 p-3 rounded-lg bg-paper/60 border border-soft/60">
                                <span class="mt-0.5 w-6 h-6 shrink-0 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] text-white text-[10px] font-black flex items-center justify-center">{{ i+1 }}</span>
                                <span class="text-[13.5px] text-ink-body leading-relaxed">{{ t }}</span>
                            </li>
                        </ul>
                    </section>

                    <!-- Key advantages -->
                    <section v-if="rich.advantages?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-6 lg:p-8">
                        <SectionHeader icon="star" title="مميزات المشروع" subtitle="ما الذي يجعل هذه الفرصة استثنائية" />
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                            <div v-for="(a, i) in rich.advantages" :key="i"
                                 class="p-4 rounded-xl bg-gradient-to-br from-white to-paper border border-soft hover:border-[#3DAFB9]/40 transition-colors">
                                <div class="flex items-start gap-2 mb-2">
                                    <svg class="w-4 h-4 mt-0.5 text-[#3DAFB9] shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <h4 class="text-[13.5px] font-black text-ink">{{ a.title }}</h4>
                                </div>
                                <p class="text-[12.5px] text-ink-body leading-[1.8]">{{ a.desc }}</p>
                            </div>
                        </div>
                    </section>

                    <!-- Implementation phases (timeline) -->
                    <section v-if="rich.phases?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-6 lg:p-8">
                        <SectionHeader icon="path" title="مراحل التنفيذ" subtitle="خارطة الطريق من الفكرة إلى الافتتاح" />
                        <div class="relative mt-6 pr-6 rtl:pr-0 rtl:pl-6">
                            <div class="absolute right-2 rtl:right-auto rtl:left-2 top-2 bottom-2 w-0.5 bg-gradient-to-b from-[#3DAFB9] via-[#2D4B7E] to-[#3DAFB9]/30"></div>
                            <div v-for="(p, i) in rich.phases" :key="i" class="relative flex items-start gap-4 pb-6 last:pb-0">
                                <div class="relative shrink-0 -mr-6 rtl:mr-0 rtl:-ml-6">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] text-white text-[11px] font-black flex items-center justify-center shadow-md ring-4 ring-elevated">
                                        {{ String(i+1).padStart(2, '0') }}
                                    </div>
                                </div>
                                <div class="flex-1 pt-1">
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <h4 class="text-[13.5px] font-black text-ink">{{ p.title }}</h4>
                                        <span class="text-[10.5px] px-2 py-0.5 rounded-full bg-[#3DAFB9]/12 text-[#3DAFB9] font-bold">{{ p.duration }}</span>
                                    </div>
                                    <p class="text-[12.5px] text-ink-body leading-relaxed">{{ p.desc }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Risks & Mitigation -->
                    <section v-if="rich.risks?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-6 lg:p-8">
                        <SectionHeader icon="shield" title="المخاطر وطرق تجاوزها" subtitle="تحليل واقعي لأبرز التحديات والحلول المقترحة" />
                        <div class="mt-4 space-y-3">
                            <div v-for="(r, i) in rich.risks" :key="i"
                                 class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-xl bg-paper/60 border border-soft/60">
                                <div class="flex items-start gap-2">
                                    <div class="w-6 h-6 shrink-0 rounded-full bg-amber-500/15 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.48 0l-7.1 12.25A2 2 0 005 19z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-[10.5px] text-amber-700 dark:text-amber-400 font-bold mb-1">الخطر</div>
                                        <p class="text-[12.5px] text-ink-body leading-[1.7]">{{ r.risk }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <div class="w-6 h-6 shrink-0 rounded-full bg-emerald-500/15 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-[10.5px] text-emerald-700 dark:text-emerald-400 font-bold mb-1">الحل المقترح</div>
                                        <p class="text-[12.5px] text-ink-body leading-[1.7]">{{ r.mitigation }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Fallback: raw description if no rich -->
                    <section v-if="!hasRich && study.description" class="rounded-[1.5rem] bg-elevated border border-soft p-6 lg:p-8">
                        <SectionHeader icon="clipboard" title="تفاصيل الدراسة" />
                        <div class="mt-4 text-[14px] text-ink-body leading-[2] whitespace-pre-line">{{ study.description }}</div>
                    </section>
                </div>

                <!-- Right sidebar: What's included -->
                <aside class="col-span-12 lg:col-span-4">
                    <div class="sticky top-[8rem] space-y-5">
                        <div v-if="rich.includes?.length" class="rounded-[1.5rem] bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] text-white p-6 shadow-card">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <h3 class="text-[15px] font-black">ماذا ستحصل عليه؟</h3>
                            </div>
                            <ul class="space-y-2.5 text-[13px]">
                                <li v-for="(item, i) in rich.includes" :key="i" class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 shrink-0 text-[#C2EBEF]" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span class="leading-relaxed">{{ item }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Trust card -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card">
                            <h4 class="text-[13.5px] font-black text-ink mb-3">لماذا دراسات رواد؟</h4>
                            <ul class="space-y-2.5 text-[12.5px] text-ink-body">
                                <li class="flex items-start gap-2">
                                    <span class="w-1.5 h-1.5 mt-1.5 shrink-0 rounded-full bg-[#3DAFB9]"></span>
                                    <span>أعدّها خبراء استشاريون معتمدون من هيئة السوق المالية</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="w-1.5 h-1.5 mt-1.5 shrink-0 rounded-full bg-[#3DAFB9]"></span>
                                    <span>بيانات محدّثة تعكس واقع السوق السعودي 2026</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="w-1.5 h-1.5 mt-1.5 shrink-0 rounded-full bg-[#3DAFB9]"></span>
                                    <span>متوافقة مع رؤية 2030 والمبادرات الحكومية</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="w-1.5 h-1.5 mt-1.5 shrink-0 rounded-full bg-[#3DAFB9]"></span>
                                    <span>دعم فني مباشر عبر الشات لأي استفسار</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({ study: Object });
// Normalize: target_market / includes may be array of strings OR array of {item: string}
const normalize = (arr) => Array.isArray(arr)
    ? arr.map(x => (typeof x === 'string' ? x : (x?.item ?? ''))).filter(Boolean)
    : [];
const rich = computed(() => {
    const r = props.study.rich_content || {};
    return {
        ...r,
        target_market: normalize(r.target_market),
        includes: normalize(r.includes),
    };
});
const hasRich = computed(() =>
    !!(rich.value.summary || rich.value.financials?.length || rich.value.target_market?.length || rich.value.advantages?.length)
);
const formatPrice = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));
</script>

<script>
// Section header component + metric icon (declared as separate components for reuse)
import { h, defineComponent } from 'vue';

const SectionHeader = defineComponent({
    props: ['icon', 'title', 'subtitle'],
    setup(p) {
        return () => h('div', { class: 'flex items-start gap-3' }, [
            h('div', { class: 'shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center text-white' }, [
                h(MetricIcon, { name: p.icon }),
            ]),
            h('div', { class: 'flex-1 min-w-0' }, [
                h('h3', { class: 'text-[16.5px] lg:text-[18px] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-tight' }, p.title),
                p.subtitle ? h('p', { class: 'text-[12px] text-ink-muted mt-1' }, p.subtitle) : null,
            ]),
        ]);
    },
});

const iconPaths = {
    wallet:    'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5v14m12-7h-6a2 2 0 00-2 2v2a2 2 0 002 2h6',
    trend:     'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
    clock:     'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    coin:      'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z',
    chart:     'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    balance:   'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
    clipboard: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
    users:     'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    star:      'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
    path:      'M13 5l7 7-7 7M5 5l7 7-7 7',
    shield:    'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
};

const MetricIcon = defineComponent({
    props: ['name'],
    setup(p) {
        return () => h('svg', {
            class: 'w-4 h-4', fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor', 'stroke-width': '2',
        }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: iconPaths[p.name] || iconPaths.chart }),
        ]);
    },
});

export default { components: { SectionHeader, MetricIcon } };
</script>
