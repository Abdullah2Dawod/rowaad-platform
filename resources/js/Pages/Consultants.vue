<template>
    <MainLayout>
        <PageHero
            :eyebrow="t('nav.consultants')"
            title="نخبة من المستشارين المعتمدين"
            subtitle="اختر المستشار المناسب لتخصصك من فريق موثوق ومعتمد رسمياً في المنصة."
        />

        <!-- ============ FILTERS BAR ============ -->
        <section class="relative pt-6 pb-4">
            <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="rounded-2xl bg-elevated border border-soft p-4 lg:p-5 shadow-card">
                    <div class="flex flex-col lg:flex-row gap-3 lg:gap-4 lg:items-center">
                        <!-- Search -->
                        <div class="relative flex-1">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 rtl:right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input
                                v-model="q"
                                @input="debouncedApply"
                                type="text"
                                placeholder="ابحث بالاسم أو التخصص..."
                                class="w-full pr-11 rtl:pr-11 rtl:pl-4 pl-4 py-2.5 rounded-full bg-canvas border border-soft text-[14px] text-ink placeholder:text-ink-muted focus:outline-none focus:border-[#3DAFB9] transition-colors"
                            />
                        </div>
                        <!-- Sort -->
                        <select
                            v-model="sort"
                            @change="apply()"
                            class="rounded-full bg-canvas border border-soft px-4 py-2.5 text-[13px] font-bold text-ink focus:outline-none focus:border-[#3DAFB9]"
                        >
                            <option value="featured">المميزون أولاً</option>
                            <option value="rating">الأعلى تقييماً</option>
                            <option value="newest">الأحدث</option>
                            <option value="price">السعر: من الأقل</option>
                        </select>
                    </div>

                    <!-- Specialization chips -->
                    <div class="mt-4 flex items-center gap-2 overflow-x-auto pb-1 -mb-1" style="scrollbar-width: thin;">
                        <button
                            @click="setSpec(null)"
                            :class="['shrink-0 px-4 py-2 rounded-full text-[12px] font-bold border transition-all whitespace-nowrap',
                                     !specialization ? 'bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white border-transparent shadow-sm'
                                                     : 'bg-transparent border-soft text-ink-body hover:border-[#3DAFB9]/40 hover:text-ink']">
                            كل التخصصات
                        </button>
                        <button
                            v-for="s in specializations" :key="s.slug"
                            @click="setSpec(s.slug)"
                            :class="['shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[12px] font-bold border transition-all whitespace-nowrap',
                                     specialization === s.slug ? 'bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white border-transparent shadow-sm'
                                                              : 'bg-transparent border-soft text-ink-body hover:border-[#3DAFB9]/40 hover:text-ink']">
                            <img :src="specIcon(s.icon, specialization === s.slug)" class="w-4 h-4" alt="" />
                            {{ s.name_ar }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ CONSULTANTS GRID ============ -->
        <section class="relative pt-4 pb-12 sm:pb-16 lg:pb-24">
            <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <!-- Result count -->
                <div class="flex items-baseline justify-between mb-6">
                    <div class="text-[13px] text-ink-muted">
                        <span class="font-black text-ink text-lg">{{ consultants.total }}</span>
                        مستشار متاح
                    </div>
                </div>

                <!-- Empty state -->
                <div v-if="!consultants.data.length" class="text-center py-20">
                    <div class="w-16 h-16 rounded-full bg-[#3DAFB9]/10 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                    </div>
                    <p class="text-ink-body">لا يوجد مستشارون مطابقون. جرّب فلتراً آخر.</p>
                </div>

                <!-- Grid -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <Link
                        v-for="c in consultants.data" :key="c.id"
                        :href="`/consultants/${c.id}`"
                        class="group relative flex flex-col rounded-3xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover overflow-hidden transition-all duration-500 hover:-translate-y-1"
                    >
                        <!-- Featured ribbon -->
                        <div v-if="c.is_featured"
                             class="absolute top-4 left-4 rtl:left-auto rtl:right-4 z-10 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white text-[10px] font-black shadow-md">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            مميّز
                        </div>

                        <!-- Avatar area -->
                        <div class="relative aspect-[4/3.2] overflow-hidden">
                            <img :src="c.avatar" :alt="c.name" loading="lazy"
                                 class="w-full h-full object-cover saturate-[0.75] group-hover:saturate-100 group-hover:scale-[1.04] transition-all duration-[900ms]" />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A1729]/85 via-[#0A1729]/15 to-transparent"></div>

                            <!-- Specialization tag over image -->
                            <div v-if="c.specialization" class="absolute bottom-4 left-4 rtl:left-auto rtl:right-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/95 dark:bg-[#122440]/90 backdrop-blur border border-white/60">
                                <img :src="specIcon(c.specialization.icon, false)" class="w-3.5 h-3.5" alt="" />
                                <span class="text-[11px] font-bold text-ink">{{ c.specialization.name_ar }}</span>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="text-[17px] font-black text-ink group-hover:text-[#2D4B7E] dark:group-hover:text-[#6BC8D2] transition-colors">{{ c.name }}</h3>
                            <div class="text-[12px] text-[#3DAFB9] font-bold mt-0.5">{{ c.title }}</div>

                            <p class="text-[13px] text-ink-body leading-relaxed mt-3 line-clamp-3 flex-1">{{ c.bio }}</p>

                            <!-- Meta row -->
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-soft">
                                <!-- Rating -->
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#F59E0B]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <span class="text-[13px] font-black text-ink">{{ Number(c.rating_avg).toFixed(1) }}</span>
                                    <span class="text-[10px] text-ink-muted">({{ c.rating_count }})</span>
                                </div>
                                <!-- Price -->
                                <div class="text-left rtl:text-left">
                                    <span class="text-[15px] font-black text-ink">{{ formatPrice(c.hourly_rate) }}</span>
                                    <span class="text-[10px] text-ink-muted mr-1">ر.س / جلسة</span>
                                </div>
                            </div>

                            <!-- Small stats strip -->
                            <div class="flex items-center gap-3 mt-3 text-[10.5px] text-ink-muted">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 6v6l4 2"/><circle cx="12" cy="12" r="9"/></svg>
                                    {{ c.years_experience }}+ سنوات
                                </span>
                                <span class="text-ink-muted/50">·</span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ c.sessions_completed }} جلسة
                                </span>
                                <span class="text-ink-muted/50">·</span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ c.city }}
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="consultants.last_page > 1" class="mt-10 flex items-center justify-center gap-1.5">
                    <Link
                        v-for="link in consultants.links" :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'min-w-9 h-9 inline-flex items-center justify-center rounded-full text-[12px] font-bold transition-all',
                            link.active ? 'bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] text-white shadow-md'
                                       : link.url ? 'bg-elevated border border-soft text-ink hover:border-[#3DAFB9]/40'
                                                  : 'bg-transparent text-ink-muted/40 cursor-not-allowed'
                        ]"
                        v-html="link.label"
                        preserve-scroll
                    />
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import PageHero from '@/Components/PageHero.vue';
import { useI18n } from '@/composables/useI18n';
import { useTheme } from '@/composables/useTheme';

const props = defineProps({
    consultants:     Object,
    specializations: Array,
    filters:         Object,
});

const { t } = useI18n();
const { isDark } = useTheme();

const q              = ref(props.filters?.q ?? '');
const sort           = ref(props.filters?.sort ?? 'featured');
const specialization = ref(props.filters?.specialization ?? null);

const specIcon = (slug, active = false) => {
    const color = active ? 'FFFFFF' : (isDark.value ? '6BC8D2' : '2D4B7E');
    return `https://api.iconify.design/solar:${slug || 'user-bold-duotone'}.svg?color=%23${color}&width=48`;
};

const formatPrice = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));

const apply = () => {
    router.get('/consultants', {
        q: q.value || undefined,
        sort: sort.value !== 'featured' ? sort.value : undefined,
        specialization: specialization.value || undefined,
    }, { preserveState: true, preserveScroll: true, replace: true });
};

let searchTimer = null;
const debouncedApply = () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(apply, 400);
};

const setSpec = (slug) => {
    specialization.value = slug;
    apply();
};
</script>
