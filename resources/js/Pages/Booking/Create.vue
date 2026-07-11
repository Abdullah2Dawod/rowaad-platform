<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-24 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[10%] w-[500px] h-[500px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>
            </div>

            <div class="relative max-w-5xl mx-auto px-6 lg:px-10">
                <Link :href="`/consultants/${consultant.id}`" class="inline-flex items-center gap-2 text-[12px] text-ink-body hover:text-[#3DAFB9] font-bold mb-6 transition-colors">
                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                    العودة لصفحة المستشار
                </Link>

                <div class="mb-2 text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">حجز استشارة</div>
                <h1 class="text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-10">اختر مدة الجلسة وموعدك</h1>

                <div class="grid grid-cols-12 gap-6">
                    <!-- ═══════════ FORM ═══════════ -->
                    <form @submit.prevent="submit" class="col-span-12 lg:col-span-8 space-y-6">

                        <!-- ─── DURATION TIERS (interactive cards) ─── -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 lg:p-7 shadow-card">
                            <div class="flex items-baseline justify-between mb-5">
                                <div>
                                    <h2 class="text-lg font-black text-ink">مدة الجلسة</h2>
                                    <p class="text-[12px] text-ink-body mt-0.5">كلما زادت المدة، حصلت على قيمة أفضل مقابل الوقت.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-5 gap-2.5">
                                <button v-for="tier in pricing_tiers" :key="tier.duration_min"
                                        type="button"
                                        @click="selectTier(tier)"
                                        :class="['tier-card relative overflow-hidden p-3.5 rounded-2xl border-2 transition-all text-start',
                                                 form.duration_min === tier.duration_min
                                                    ? 'border-[#3DAFB9] bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/8 shadow-md shadow-[#3DAFB9]/15'
                                                    : 'border-soft bg-canvas hover:border-[#3DAFB9]/40']">
                                    <!-- Best value ribbon on 90/120 min -->
                                    <span v-if="tier.duration_min >= 90"
                                          class="absolute -top-0.5 -left-0.5 rtl:-left-auto rtl:-right-0.5 text-[8.5px] font-black text-white bg-gradient-to-br from-[#F59E0B] to-[#D97706] px-2 py-0.5 rounded-br-lg rtl:rounded-br-none rtl:rounded-bl-lg">
                                        ⭐ الأفضل قيمة
                                    </span>

                                    <div class="text-[11px] text-ink-muted tracking-wider">{{ tier.label }}</div>
                                    <div class="mt-1.5 flex items-baseline gap-1">
                                        <span :class="['text-lg font-black', form.duration_min === tier.duration_min ? 'text-gradient-brand' : 'text-ink']">
                                            {{ formatPrice(tier.amount) }}
                                        </span>
                                        <span class="text-[9.5px] text-ink-muted">ر.س</span>
                                    </div>
                                    <!-- Savings hint -->
                                    <div v-if="savingsPercent(tier) > 0"
                                         class="mt-1 text-[9.5px] font-bold text-green-600 dark:text-green-400">
                                        وفّر {{ savingsPercent(tier) }}% مقابل الدقيقة
                                    </div>
                                </button>
                            </div>
                            <span v-if="form.errors.duration_min" class="mt-2 block text-[11.5px] text-red-500 font-medium">{{ form.errors.duration_min }}</span>
                        </div>

                        <!-- ─── DATE + TIME ─── -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 lg:p-7 shadow-card">
                            <h2 class="text-lg font-black text-ink mb-1">موعد الجلسة</h2>
                            <p class="text-[12px] text-ink-body mb-5">اختر التاريخ والوقت المناسبَين وسيؤكّدهما المستشار.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <PremiumField v-model="form.preferred_date" label="التاريخ المُقترح" required type="date"
                                              icon="calendar-mark" :min="today"
                                              :error="form.errors.preferred_date" />
                                <PremiumField v-model="form.preferred_time" label="الوقت المُقترح" required type="time"
                                              icon="clock-circle"
                                              :error="form.errors.preferred_time" />
                            </div>
                        </div>

                        <!-- ─── SERVICE + NOTES ─── -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 lg:p-7 shadow-card space-y-5">
                            <h2 class="text-lg font-black text-ink">تفاصيل إضافية</h2>

                            <PremiumField v-model="form.service_title" label="نوع الخدمة" hint="اختياري" type="select"
                                          icon="widget-4" :error="form.errors.service_title">
                                <option :value="null">— اختر خدمة معيّنة —</option>
                                <option v-for="s in consultant.services" :key="s.title" :value="s.title">{{ s.title }}</option>
                            </PremiumField>

                            <PremiumField v-model="form.notes" label="ملاحظات للمستشار" hint="اختياري" type="textarea"
                                          icon="document-text" placeholder="اذكر باختصار موضوع الاستشارة والنقاط التي ترغب في مناقشتها."
                                          :maxlength="1000" show-counter :rows="4"
                                          :error="form.errors.notes" />
                        </div>

                        <!-- ─── ACTIONS ─── -->
                        <div class="flex items-center justify-between pt-2">
                            <Link :href="`/consultants/${consultant.id}`" class="text-[13px] text-ink-body hover:text-ink font-bold">
                                إلغاء
                            </Link>
                            <button type="submit" :disabled="form.processing"
                                    class="group inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[14px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.03] disabled:opacity-60 disabled:hover:scale-100 transition-transform">
                                <span v-if="form.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                متابعة إلى الدفع · {{ formatPrice(currentAmount) }} ر.س
                            </button>
                        </div>
                    </form>

                    <!-- ═══════════ SUMMARY ═══════════ -->
                    <aside class="col-span-12 lg:col-span-4">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card sticky top-28">
                            <!-- Consultant -->
                            <div class="flex items-center gap-3 mb-5 pb-5 border-b border-soft">
                                <img :src="consultant.avatar" :alt="consultant.name" class="w-14 h-14 rounded-full object-cover shrink-0" />
                                <div class="min-w-0">
                                    <div class="text-[14px] font-black text-ink truncate">{{ consultant.name }}</div>
                                    <div class="text-[11.5px] text-ink-body truncate">{{ consultant.title }}</div>
                                    <div class="text-[10.5px] text-ink-muted mt-0.5">
                                        {{ formatPrice(consultant.hourly_rate) }} ر.س / ساعة
                                    </div>
                                </div>
                            </div>

                            <!-- Summary rows -->
                            <dl class="space-y-3 text-[13px]">
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">التاريخ</dt>
                                    <dd class="font-bold text-ink">{{ form.preferred_date || '—' }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">الوقت</dt>
                                    <dd class="font-bold text-ink">{{ form.preferred_time || '—' }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">المدة</dt>
                                    <dd class="font-bold text-ink">{{ currentDurationLabel }}</dd>
                                </div>
                                <div v-if="form.service_title" class="flex items-center justify-between">
                                    <dt class="text-ink-muted">الخدمة</dt>
                                    <dd class="font-bold text-ink text-left rtl:text-left line-clamp-1">{{ form.service_title }}</dd>
                                </div>
                            </dl>

                            <!-- Total -->
                            <div class="mt-6 pt-5 border-t border-soft">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[13px] text-ink-body">إجمالي الجلسة</span>
                                    <div class="text-left rtl:text-left">
                                        <span class="text-2xl font-black text-gradient-brand">{{ formatPrice(currentAmount) }}</span>
                                        <span class="text-[11px] text-ink-muted mr-1">ر.س</span>
                                    </div>
                                </div>
                                <p class="text-[10.5px] text-ink-muted">شامل جميع الرسوم — لا مفاجآت.</p>
                            </div>

                            <!-- Trust footer -->
                            <div class="mt-5 p-3 rounded-xl bg-gradient-to-br from-[#3DAFB9]/6 to-[#2D4B7E]/4 border border-[#3DAFB9]/15">
                                <p class="text-[10.5px] text-ink-body text-center leading-relaxed">
                                    🛡️ الدفع بعد الضغط على "متابعة إلى الدفع"<br>
                                    يُستردّ المبلغ كاملاً إذا اعتذر المستشار.
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import PremiumField from '@/Components/UI/PremiumField.vue';

const props = defineProps({
    consultant:    { type: Object, required: true },
    pricing_tiers: { type: Array,  default: () => [] },
});

// Default to consultant's preferred duration (or 60)
const defaultDuration = props.consultant.session_duration_min || 60;

const form = useForm({
    preferred_date: '',
    preferred_time: '10:00',
    duration_min:   defaultDuration,
    service_title:  null,
    notes:          '',
});

const today = computed(() => new Date().toISOString().slice(0, 10));

// The currently-selected tier — always in sync with form.duration_min
const currentTier = computed(() =>
    props.pricing_tiers.find(t => t.duration_min === form.duration_min) ?? props.pricing_tiers[2]
);
const currentAmount        = computed(() => currentTier.value?.amount ?? 0);
const currentDurationLabel = computed(() => currentTier.value?.label ?? '—');

const selectTier = (tier) => { form.duration_min = tier.duration_min; };

const formatPrice = (v) => new Intl.NumberFormat('ar-SA', { maximumFractionDigits: 0 }).format(Math.round(v));

/**
 * "You save X%" — compares the effective per-minute rate at this tier vs the base 60-min tier.
 * Only shows a positive value when the tier is CHEAPER per minute than base.
 */
const baseTier = computed(() => props.pricing_tiers.find(t => t.duration_min === 60));
const savingsPercent = (tier) => {
    if (!baseTier.value || tier.duration_min === 60) return 0;
    const basePerMin = baseTier.value.amount / 60;
    const tierPerMin = tier.amount / tier.duration_min;
    const pct = Math.round((1 - tierPerMin / basePerMin) * 100);
    return pct > 0 ? pct : 0;
};

const submit = () => form.post(`/bookings/store/${props.consultant.id}`);
</script>

<style scoped>
.tier-card {
    position: relative;
}
.tier-card:hover {
    transform: translateY(-2px);
}
</style>
