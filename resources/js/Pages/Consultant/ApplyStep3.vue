<template>
    <WizardShell :current="3" :completed="application.completed_step">
        <form @submit.prevent="submit" class="space-y-7">
            <div>
                <h2 class="text-lg font-black text-ink">تخصصك وخدماتك</h2>
                <p class="text-[12.5px] text-ink-body mt-1">حدّد مجال خبرتك والخدمات التي ستُقدّمها من خلال المنصة.</p>
            </div>

            <!-- Primary specialization -->
            <FormField label="التخصص الرئيسي" required :error="form.errors.specialization_id">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    <label v-for="s in specializations" :key="s.id"
                           :class="['relative flex flex-col items-center gap-1.5 p-3 rounded-2xl border cursor-pointer transition-all',
                                    form.specialization_id === s.id
                                      ? 'border-[#3DAFB9] bg-[#3DAFB9]/8 shadow-sm'
                                      : 'border-soft hover:border-[#3DAFB9]/40']">
                        <input v-model.number="form.specialization_id" :value="s.id" type="radio" class="sr-only" />
                        <img :src="specIcon(s.icon, form.specialization_id === s.id)" class="w-6 h-6" alt="" />
                        <span class="text-[11.5px] font-bold text-center leading-tight" :class="form.specialization_id === s.id ? 'text-ink' : 'text-ink-body'">
                            {{ s.name_ar }}
                        </span>
                    </label>
                </div>
            </FormField>

            <!-- Secondary specializations -->
            <FormField label="تخصصات ثانوية (اختياري)" hint="حتى 3" :error="form.errors.secondary_specializations">
                <div class="flex flex-wrap gap-2">
                    <button v-for="s in specializations" :key="s.id"
                            type="button"
                            :disabled="s.id === form.specialization_id"
                            @click="toggleSecondary(s.id)"
                            :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-[11.5px] font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed',
                                     form.secondary_specializations.includes(s.id)
                                       ? 'bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white border-transparent'
                                       : 'bg-transparent border-soft text-ink-body hover:border-[#3DAFB9]/40']">
                        {{ s.name_ar }}
                    </button>
                </div>
            </FormField>

            <!-- Experience -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <FormField label="سنوات الخبرة" required :error="form.errors.years_experience">
                    <input v-model.number="form.years_experience" type="number" min="0" max="60" class="fld" />
                </FormField>
                <FormField label="لغات الاستشارة" required :error="form.errors.languages">
                    <div class="flex flex-wrap gap-2">
                        <label v-for="l in langs" :key="l.v"
                               :class="['inline-flex items-center gap-1.5 px-3 h-10 rounded-full border cursor-pointer text-[12.5px] font-bold transition-all',
                                        form.languages.includes(l.v) ? 'border-[#3DAFB9] bg-[#3DAFB9]/8 text-ink' : 'border-soft text-ink-body hover:border-[#3DAFB9]/40']">
                            <input :value="l.v" v-model="form.languages" type="checkbox" class="sr-only" />
                            {{ l.label }}
                        </label>
                    </div>
                </FormField>
            </div>

            <!-- Services -->
            <div>
                <div class="flex items-baseline justify-between mb-2">
                    <span class="text-[12.5px] font-bold text-ink">الخدمات التي تُقدّمها <span class="text-red-500">*</span></span>
                    <span class="text-[10.5px] text-ink-muted">حتى 8 خدمات</span>
                </div>
                <span v-if="form.errors.services" class="block text-[11.5px] text-red-500 font-medium mb-2">{{ form.errors.services }}</span>

                <div class="space-y-3">
                    <div v-for="(s, i) in form.services" :key="i"
                         class="p-4 rounded-2xl border border-soft bg-canvas">
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-[11.5px] font-black text-[#3DAFB9]">خدمة {{ i + 1 }}</span>
                            <button v-if="form.services.length > 1" type="button" @click="form.services.splice(i, 1)"
                                    class="text-[11px] text-red-500 hover:text-red-600 font-bold">حذف</button>
                        </div>
                        <input v-model="s.title" type="text" class="fld mb-2"
                               placeholder="عنوان الخدمة — مثل: مراجعة خطة عمل" />
                        <textarea v-model="s.description" rows="2" class="fld leading-relaxed mb-2"
                                  placeholder="وصف مختصر (اختياري)"></textarea>
                        <div class="flex items-center gap-2">
                            <span class="text-[11.5px] text-ink-body">المدة:</span>
                            <select v-model.number="s.duration_min" class="fld !h-9 !w-auto !py-0">
                                <option :value="30">30 دقيقة</option>
                                <option :value="45">45 دقيقة</option>
                                <option :value="60">60 دقيقة</option>
                                <option :value="90">90 دقيقة</option>
                                <option :value="120">120 دقيقة</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="button" @click="addService"
                        v-if="form.services.length < 8"
                        class="mt-3 w-full h-11 rounded-2xl border-2 border-dashed border-[#3DAFB9]/30 hover:border-[#3DAFB9] hover:bg-[#3DAFB9]/5 text-[#3DAFB9] font-bold text-[12.5px] transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                    إضافة خدمة
                </button>
            </div>

            <!-- Pricing + Duration -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <FormField label="سعر الجلسة (ر.س)" required hint="من 50 إلى 5000" :error="form.errors.hourly_rate">
                    <div class="relative">
                        <input v-model.number="form.hourly_rate" type="number" min="50" max="5000" step="10" class="fld pr-12 rtl:pr-12 rtl:pl-4" />
                        <span class="absolute top-1/2 -translate-y-1/2 left-4 rtl:left-4 rtl:right-auto text-[11px] font-black text-ink-muted">ر.س</span>
                    </div>
                </FormField>
                <FormField label="مدة الجلسة الافتراضية" required :error="form.errors.session_duration_min">
                    <select v-model.number="form.session_duration_min" class="fld">
                        <option :value="30">30 دقيقة</option>
                        <option :value="45">45 دقيقة</option>
                        <option :value="60">60 دقيقة</option>
                        <option :value="90">90 دقيقة</option>
                        <option :value="120">120 دقيقة</option>
                    </select>
                </FormField>
            </div>

            <!-- Pricing preview -->
            <div v-if="form.hourly_rate >= 50" class="p-4 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5 border border-[#3DAFB9]/20">
                <div class="text-[11px] font-black text-[#3DAFB9] tracking-wider mb-2">توزيع السعر</div>
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div>
                        <div class="text-lg font-black text-ink">{{ format(form.hourly_rate / 2) }}</div>
                        <div class="text-[10px] text-ink-muted mt-0.5">حصتك (قبل الزكاة)</div>
                    </div>
                    <div>
                        <div class="text-lg font-black text-[#F59E0B]">{{ format(zakat) }}</div>
                        <div class="text-[10px] text-ink-muted mt-0.5">زكاة 15%</div>
                    </div>
                    <div>
                        <div class="text-lg font-black text-ink">{{ format(form.hourly_rate / 2) }}</div>
                        <div class="text-[10px] text-ink-muted mt-0.5">حصة المنصة</div>
                    </div>
                </div>
                <p class="text-[11px] text-ink-body text-center mt-3 leading-relaxed">
                    صافي ما تستلمه لكل جلسة: <span class="font-black text-ink">{{ format(net) }} ر.س</span>
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-soft">
                <Link href="/become-a-consultant/step-2" class="inline-flex items-center gap-1.5 text-[13px] text-ink-body hover:text-ink font-bold">
                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                    السابق
                </Link>
                <button type="submit" :disabled="form.processing"
                        class="group inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-[1.03] disabled:opacity-60 disabled:hover:scale-100 transition-transform">
                    <span v-if="form.processing" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    إرسال الطلب للمراجعة
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
            </div>
        </form>
    </WizardShell>
</template>

<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import WizardShell from '@/Components/Apply/WizardShell.vue';
import FormField from '@/Components/Apply/FormField.vue';
import { useTheme } from '@/composables/useTheme';

const props = defineProps({ application: Object, existing: Object, specializations: Array });

const { isDark } = useTheme();

const initialServices = (props.existing.services?.length ? props.existing.services : [
    { title: '', description: '', duration_min: 60 },
]).map(s => ({
    title:        s.title ?? '',
    description:  s.description ?? '',
    duration_min: s.duration_min ?? 60,
}));

const form = useForm({
    specialization_id:         props.existing.specialization_id ?? null,
    secondary_specializations: props.existing.secondary_specializations ?? [],
    years_experience:          props.existing.years_experience ?? 0,
    services:                  initialServices,
    hourly_rate:               props.existing.hourly_rate ?? 300,
    session_duration_min:      props.existing.session_duration_min ?? 60,
    languages:                 props.existing.languages ?? ['ar'],
    availability:              props.existing.availability ?? {},
});

const langs = [
    { v: 'ar', label: 'العربية' },
    { v: 'en', label: 'English' },
    { v: 'fr', label: 'Français' },
];

const specIcon = (slug, active = false) => {
    const color = active ? '3DAFB9' : (isDark.value ? '6BC8D2' : '2D4B7E');
    return `https://api.iconify.design/solar:${slug || 'user-bold-duotone'}.svg?color=%23${color}&width=48`;
};

const toggleSecondary = (id) => {
    const idx = form.secondary_specializations.indexOf(id);
    if (idx >= 0) form.secondary_specializations.splice(idx, 1);
    else if (form.secondary_specializations.length < 3) form.secondary_specializations.push(id);
};

const addService = () => form.services.push({ title: '', description: '', duration_min: 60 });

const format = (v) => new Intl.NumberFormat('ar-SA', { maximumFractionDigits: 0 }).format(Math.max(0, v || 0));

// Pricing model: 50% consultant, 50% platform, 15% zakat on consultant share
const zakat = computed(() => (form.hourly_rate / 2) * 0.15);
const net   = computed(() => (form.hourly_rate / 2) - zakat.value);

const submit = () => form.post('/become-a-consultant/step-3');
</script>

<style scoped>
.fld {
    display: block; width: 100%; height: 42px; padding: 0 14px;
    background: var(--bg-elevated); border: 1px solid var(--border-soft);
    border-radius: 12px; color: var(--ink-primary);
    font-size: 13.5px; font-family: inherit;
    transition: border-color 200ms ease, box-shadow 200ms ease;
}
textarea.fld { height: auto; padding: 10px 14px; resize: vertical; }
.fld:focus { outline: none; border-color: #3DAFB9; box-shadow: 0 0 0 3px rgba(61,175,185,0.15); }
</style>
