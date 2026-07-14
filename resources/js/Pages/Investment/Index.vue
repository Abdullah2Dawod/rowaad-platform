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
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#3DAFB9]/10 border border-[#3DAFB9]/25 mb-4">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#3DAFB9] animate-pulse"></span>
                            <span class="text-[10.5px] font-black text-[#3DAFB9] tracking-widest">فرص استثمارية · B2B</span>
                        </div>
                        <h1 class="text-3xl lg:text-[2.6rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.15] mb-4">
                            استثمر بذكاء<br>
                            <span class="text-gradient-brand">في مشاريع مدروسة ومعتمدة</span>
                        </h1>
                        <p class="text-[14.5px] text-ink-body leading-[1.9] max-w-xl">
                            فرص استثمارية مختارة بعناية من فريق رواد + شراكات مع الجهات الحكومية،
                            مصمّمة للمستثمرين والشركات (B2B) الذين يبحثون عن قيمة حقيقية وعائدات مستدامة.
                        </p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-5 lg:gap-8">
                        <div>
                            <div class="text-3xl lg:text-4xl font-black text-gradient-brand">{{ stats.total_open }}</div>
                            <div class="text-[10.5px] text-ink-muted tracking-widest mt-1">فرصة مفتوحة</div>
                        </div>
                        <div>
                            <div class="text-3xl lg:text-4xl font-black text-gradient-brand">+{{ formatBillions(stats.total_value) }}</div>
                            <div class="text-[10.5px] text-ink-muted tracking-widest mt-1">حجم الاستثمارات</div>
                        </div>
                        <div>
                            <div class="text-3xl lg:text-4xl font-black text-gradient-brand">{{ stats.sectors }}</div>
                            <div class="text-[10.5px] text-ink-muted tracking-widest mt-1">قطاعات نشطة</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════ FILTERS ═══════════ -->
        <section class="relative pt-4 pb-4">
            <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="rounded-2xl bg-elevated border border-soft p-4 lg:p-5 shadow-card">
                    <div class="flex flex-col lg:flex-row gap-3 lg:items-center">
                        <div class="relative flex-1">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input v-model="q" @input="debouncedApply" type="text" placeholder="ابحث بالعنوان أو القطاع أو المدينة..."
                                   class="w-full pr-11 pl-4 py-2.5 rounded-full bg-canvas border border-soft text-[14px] text-ink placeholder:text-ink-muted focus:outline-none focus:border-[#3DAFB9] transition-colors" />
                        </div>
                        <select v-model="sort" @change="apply()"
                                class="rounded-full bg-canvas border border-soft px-4 py-2.5 text-[13px] font-bold text-ink focus:outline-none focus:border-[#3DAFB9]">
                            <option value="featured">المميّزة أولاً</option>
                            <option value="roi">الأعلى عائداً</option>
                            <option value="newest">الأحدث</option>
                            <option value="amount">الأقل استثماراً</option>
                            <option value="deadline">أقرب موعد إغلاق</option>
                        </select>
                        <select v-model="risk" @change="apply()"
                                class="rounded-full bg-canvas border border-soft px-4 py-2.5 text-[13px] font-bold text-ink focus:outline-none focus:border-[#3DAFB9]">
                            <option :value="null">كل المخاطر</option>
                            <option value="low">منخفضة</option>
                            <option value="medium">متوسطة</option>
                            <option value="high">مرتفعة</option>
                        </select>
                    </div>
                    <div class="mt-4 flex items-center gap-2 overflow-x-auto pb-1 -mb-1">
                        <button @click="setSector(null)"
                                :class="['shrink-0 px-4 py-2 rounded-full text-[12px] font-bold border transition-all whitespace-nowrap',
                                         !sector ? 'bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white border-transparent shadow-sm' : 'bg-transparent border-soft text-ink-body hover:border-[#3DAFB9]/40 hover:text-ink']">
                            كل القطاعات
                        </button>
                        <button v-for="s in sectors" :key="s" @click="setSector(s)"
                                :class="['shrink-0 px-4 py-2 rounded-full text-[12px] font-bold border transition-all whitespace-nowrap',
                                         sector === s ? 'bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white border-transparent shadow-sm' : 'bg-transparent border-soft text-ink-body hover:border-[#3DAFB9]/40 hover:text-ink']">
                            {{ s }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════ GRID ═══════════ -->
        <section class="relative pt-4 pb-24">
            <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="flex items-baseline justify-between mb-6">
                    <div class="text-[13px] text-ink-muted">
                        <span class="font-black text-ink text-lg">{{ opportunities.total }}</span>
                        فرصة استثمارية مطابقة
                    </div>
                </div>

                <div v-if="!opportunities.data.length" class="text-center py-20">
                    <div class="w-16 h-16 rounded-full bg-[#3DAFB9]/10 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <p class="text-ink-body">لا توجد فرص مطابقة. جرّب فلاتر أخرى.</p>
                </div>

                <!-- Cinematic horizontal card grid (2/row) — completely different from consultants/feasibility -->
                <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-6">
                    <Link v-for="o in opportunities.data" :key="o.id" :href="`/investments/${o.id}`"
                          class="group relative flex flex-col sm:flex-row rounded-3xl bg-elevated border border-soft hover:border-[#3DAFB9]/45 hover:shadow-card-hover overflow-hidden transition-all duration-500 hover:-translate-y-1 min-h-[240px]">
                        <!-- LEFT: cinematic cover (40%) -->
                        <div class="relative sm:w-2/5 shrink-0 overflow-hidden bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] min-h-[180px] sm:min-h-0">
                            <img v-if="o.cover_image" :src="o.cover_image" :alt="o.title" loading="lazy"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.08] transition-transform duration-[1200ms]" />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A1729]/85 via-[#0A1729]/35 to-[#0A1729]/10"></div>
                            <div class="absolute inset-0 bg-gradient-to-l rtl:bg-gradient-to-r from-elevated via-transparent to-transparent sm:opacity-100 opacity-0"></div>

                            <!-- Top-left badges -->
                            <div class="absolute top-3 left-3 rtl:left-auto rtl:right-3 flex flex-col gap-1.5">
                                <span v-if="o.is_featured"
                                      class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white text-[9.5px] font-black shadow-md">
                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    مميّزة
                                </span>
                                <span :class="['inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9.5px] font-black shadow-sm', riskClass(o.risk_level)]">
                                    {{ riskLabel(o.risk_level) }}
                                </span>
                            </div>

                            <!-- Big ROI ring at bottom-left corner -->
                            <div v-if="o.expected_roi" class="absolute bottom-3 left-3 rtl:left-auto rtl:right-3 w-16 h-16 rounded-full bg-white/95 dark:bg-[#0A1729]/95 backdrop-blur border-2 border-[#3DAFB9] flex flex-col items-center justify-center shadow-lg">
                                <div class="text-[16px] font-black leading-none text-[#3DAFB9]">{{ o.expected_roi }}%</div>
                                <div class="text-[8px] text-ink-muted mt-0.5 tracking-wider">عائد</div>
                            </div>
                        </div>

                        <!-- RIGHT: content -->
                        <div class="flex-1 p-5 flex flex-col justify-between">
                            <!-- Top block: sector + title + tagline -->
                            <div>
                                <div class="flex items-center gap-2 flex-wrap mb-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#3DAFB9]/12 border border-[#3DAFB9]/25 text-[10px] font-black text-[#2D4B7E] dark:text-[#6BC8D2]">
                                        {{ o.sector }}
                                    </span>
                                    <span v-if="o.city" class="text-[10.5px] text-ink-muted inline-flex items-center gap-0.5">
                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ o.city }}
                                    </span>
                                    <span v-if="o.source === 'gov_api'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#2D4B7E]/10 border border-[#2D4B7E]/25 text-[9.5px] font-black text-[#2D4B7E] dark:text-[#6BC8D2]">
                                        🏛️ حكومية
                                    </span>
                                </div>
                                <h3 class="text-[15.5px] lg:text-[16.5px] font-black text-ink group-hover:text-[#2D4B7E] dark:group-hover:text-[#6BC8D2] transition-colors line-clamp-2 leading-snug">{{ o.title }}</h3>
                                <p v-if="o.subtitle" class="text-[11.5px] text-[#3DAFB9] font-bold mt-1 line-clamp-1">{{ o.subtitle }}</p>
                                <p class="text-[12.5px] text-ink-body leading-relaxed mt-2 line-clamp-2">{{ o.summary }}</p>
                            </div>

                            <!-- Bottom block: metrics + CTA -->
                            <div class="mt-4 pt-3 border-t border-dashed border-soft">
                                <div class="grid grid-cols-3 gap-2 text-center">
                                    <div>
                                        <div class="text-[9.5px] text-ink-muted uppercase tracking-wider mb-0.5">استثمار من</div>
                                        <div class="text-[12.5px] font-black text-ink">{{ formatMoney(o.investment_min) }}</div>
                                    </div>
                                    <div v-if="o.duration_years" class="border-x border-soft">
                                        <div class="text-[9.5px] text-ink-muted uppercase tracking-wider mb-0.5">مدة</div>
                                        <div class="text-[12.5px] font-black text-ink">{{ o.duration_years }} سنوات</div>
                                    </div>
                                    <div>
                                        <div class="text-[9.5px] text-ink-muted uppercase tracking-wider mb-0.5">طلبات</div>
                                        <div class="text-[12.5px] font-black text-ink">{{ o.applications_count }}</div>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div class="text-[10px] text-ink-muted inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ o.views_count }} مشاهدة
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 text-[11.5px] font-black text-[#3DAFB9] group-hover:gap-2.5 transition-all">
                                        عرض التفاصيل
                                        <svg class="w-3 h-3 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="opportunities.last_page > 1" class="mt-10 flex items-center justify-center gap-1.5">
                    <Link v-for="link in opportunities.links" :key="link.label"
                          :href="link.url || '#'"
                          :class="[
                              'min-w-9 h-9 inline-flex items-center justify-center rounded-full text-[12px] font-bold transition-all',
                              link.active ? 'bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] text-white shadow-md'
                                         : link.url ? 'bg-elevated border border-soft text-ink hover:border-[#3DAFB9]/40'
                                                    : 'bg-transparent text-ink-muted/40 cursor-not-allowed'
                          ]"
                          v-html="link.label"
                          preserve-scroll />
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    opportunities: Object,
    filters:       Object,
    sectors:       Array,
    stats:         Object,
});

const q      = ref(props.filters?.q ?? '');
const sector = ref(props.filters?.sector ?? null);
const risk   = ref(props.filters?.risk ?? null);
const sort   = ref(props.filters?.sort ?? 'featured');

const apply = () => router.get('/investments', {
    q: q.value || undefined,
    sector: sector.value || undefined,
    risk: risk.value || undefined,
    sort: sort.value !== 'featured' ? sort.value : undefined,
}, { preserveState: true, preserveScroll: true, replace: true });

let t;
const debouncedApply = () => { clearTimeout(t); t = setTimeout(apply, 400); };

const setSector = (s) => { sector.value = s; apply(); };

const formatMoney = (n) => {
    if (n >= 1_000_000_000) return (n / 1_000_000_000).toFixed(1) + ' مليار ر.س';
    if (n >= 1_000_000)     return (n / 1_000_000).toFixed(1) + ' مليون ر.س';
    return new Intl.NumberFormat('ar-SA').format(n) + ' ر.س';
};
const formatBillions = (n) => (n / 1_000_000_000).toFixed(1) + ' مليار';

const riskLabel = (r) => ({ low: 'منخفضة', medium: 'متوسطة', high: 'مرتفعة' }[r] || r);
const riskClass = (r) => ({
    low:    'bg-green-500/95 text-white',
    medium: 'bg-orange-500/95 text-white',
    high:   'bg-red-500/95 text-white',
}[r] || 'bg-gray-500/95 text-white');
</script>
