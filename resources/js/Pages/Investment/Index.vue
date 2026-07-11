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

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <Link v-for="o in opportunities.data" :key="o.id" :href="`/investments/${o.id}`"
                          class="group relative flex flex-col rounded-3xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover overflow-hidden transition-all duration-500 hover:-translate-y-1.5">
                        <!-- Cover -->
                        <div class="relative aspect-[16/9.5] overflow-hidden bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9]">
                            <img v-if="o.cover_image" :src="o.cover_image" :alt="o.title" loading="lazy"
                                 class="w-full h-full object-cover saturate-[0.85] group-hover:saturate-100 group-hover:scale-[1.05] transition-all duration-[900ms]" />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A1729]/70 via-[#0A1729]/10 to-transparent"></div>

                            <!-- Badges -->
                            <div class="absolute top-3 left-3 rtl:left-auto rtl:right-3 flex flex-wrap gap-1.5">
                                <span v-if="o.is_featured"
                                      class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white text-[10px] font-black shadow-md">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    مميّزة
                                </span>
                                <span v-if="o.source === 'gov_api'"
                                      class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/95 dark:bg-[#122440]/95 text-[#2D4B7E] dark:text-[#6BC8D2] text-[10px] font-black border border-white dark:border-[#3DAFB9]/40 backdrop-blur">
                                    🏛️ حكومية
                                </span>
                                <span :class="['inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black shadow-sm',
                                               riskClass(o.risk_level)]">
                                    مخاطر: {{ riskLabel(o.risk_level) }}
                                </span>
                            </div>

                            <!-- Sector chip -->
                            <div class="absolute bottom-3 right-3 rtl:right-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/95 dark:bg-[#122440]/95 backdrop-blur border border-white dark:border-[#3DAFB9]/40">
                                <span class="text-[10.5px] font-bold text-ink">{{ o.sector }}</span>
                                <span v-if="o.city" class="text-[10px] text-ink-muted">· {{ o.city }}</span>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="text-[15px] font-black text-ink group-hover:text-[#2D4B7E] dark:group-hover:text-[#6BC8D2] transition-colors line-clamp-2 leading-snug">{{ o.title }}</h3>
                            <p v-if="o.subtitle" class="text-[11.5px] text-[#3DAFB9] font-bold mt-1 line-clamp-1">{{ o.subtitle }}</p>
                            <p class="text-[12.5px] text-ink-body leading-relaxed mt-2.5 line-clamp-3 flex-1">{{ o.summary }}</p>

                            <!-- Financial highlights -->
                            <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-soft">
                                <div>
                                    <div class="text-[10px] text-ink-muted tracking-wider">قيمة الاستثمار</div>
                                    <div class="text-[13px] font-black text-ink mt-0.5">{{ formatMoney(o.investment_min) }}</div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-ink-muted tracking-wider">العائد المتوقّع</div>
                                    <div class="text-[13px] font-black text-green-600 dark:text-green-400 mt-0.5">{{ o.expected_roi }}%</div>
                                </div>
                            </div>

                            <!-- Meta strip -->
                            <div class="flex items-center gap-3 mt-3 text-[10.5px] text-ink-muted">
                                <span class="inline-flex items-center gap-1" v-if="o.duration_years">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 6v6l4 2"/><circle cx="12" cy="12" r="9"/></svg>
                                    {{ o.duration_years }} سنوات
                                </span>
                                <span v-if="o.duration_years" class="text-ink-muted/50">·</span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ o.views_count }}
                                </span>
                                <span class="text-ink-muted/50">·</span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    {{ o.applications_count }} طلب
                                </span>
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
