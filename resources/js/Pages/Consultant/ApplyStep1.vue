<template>
    <WizardShell :current="1" :completed="application.completed_step">
        <form @submit.prevent="submit" class="space-y-6">
            <!-- Section header -->
            <div>
                <h2 class="text-lg font-black text-ink">من أنت؟</h2>
                <p class="text-[12.5px] text-ink-body mt-1">أدخل بياناتك الشخصية والمهنية الأساسية.</p>
            </div>

            <!-- Names -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <FormField label="الاسم الرباعي (عربي)" required :error="form.errors.full_name_ar">
                    <input v-model="form.full_name_ar" type="text" class="fld" placeholder="مثال: خالد بن عبدالله الشمري" />
                </FormField>
                <FormField label="الاسم بالإنجليزية" required :error="form.errors.full_name_en">
                    <input v-model="form.full_name_en" type="text" dir="ltr" class="fld" placeholder="e.g. Khalid Al-Shammari" />
                </FormField>
            </div>

            <!-- Email (creates account when submitted) -->
            <FormField label="البريد الإلكتروني" required
                       hint="سنُرسل لك كلمة المرور عند الاعتماد" :error="form.errors.email">
                <input v-model="form.email" type="email" dir="ltr" class="fld" placeholder="you@example.com" />
            </FormField>

            <!-- ID + Nationality -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <FormField label="رقم الهوية / الإقامة" required hint="10 أرقام" :error="form.errors.national_id">
                    <input v-model="form.national_id" type="text" dir="ltr" inputmode="numeric" maxlength="15" class="fld" placeholder="1XXXXXXXXX" />
                </FormField>
                <FormField label="الجنسية" required :error="form.errors.nationality">
                    <input v-model="form.nationality" type="text" class="fld" placeholder="سعودي" />
                </FormField>
            </div>

            <!-- Birth + Gender -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <FormField label="تاريخ الميلاد" required :error="form.errors.birth_date">
                    <input v-model="form.birth_date" type="date" class="fld" :max="maxBirthDate" />
                </FormField>
                <FormField label="الجنس" required :error="form.errors.gender">
                    <div class="grid grid-cols-2 gap-2">
                        <label v-for="g in genders" :key="g.v"
                               :class="['flex items-center justify-center gap-2 h-10 rounded-xl border cursor-pointer text-[13px] font-bold transition-all',
                                        form.gender === g.v ? 'border-[#3DAFB9] bg-[#3DAFB9]/8 text-ink' : 'border-soft text-ink-body hover:border-[#3DAFB9]/40']">
                            <input v-model="form.gender" :value="g.v" type="radio" class="sr-only" />
                            {{ g.label }}
                        </label>
                    </div>
                </FormField>
            </div>

            <!-- Location -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <FormField label="المدينة" required :error="form.errors.city">
                    <input v-model="form.city" type="text" class="fld" placeholder="الرياض" />
                </FormField>
                <FormField label="الدولة" required :error="form.errors.country">
                    <select v-model="form.country" class="fld">
                        <option value="SA">المملكة العربية السعودية</option>
                        <option value="AE">الإمارات</option>
                        <option value="KW">الكويت</option>
                        <option value="BH">البحرين</option>
                        <option value="QA">قطر</option>
                        <option value="OM">عُمان</option>
                        <option value="EG">مصر</option>
                        <option value="JO">الأردن</option>
                        <option value="OTHER">أخرى</option>
                    </select>
                </FormField>
                <FormField label="رقم الجوال" required :error="form.errors.phone">
                    <input v-model="form.phone" type="tel" dir="ltr" class="fld" placeholder="+9665XXXXXXXX" />
                </FormField>
            </div>

            <!-- Professional -->
            <FormField label="المسمّى المهني" required hint="مثل: مستشار اقتصادي أول" :error="form.errors.professional_title">
                <input v-model="form.professional_title" type="text" class="fld" placeholder="مستشار اقتصادي" />
            </FormField>

            <!-- Bio Ar -->
            <FormField label="نبذة عنك (عربي)" required
                       :hint="`${(form.bio_ar||'').length} / 1500`" :error="form.errors.bio_ar">
                <textarea v-model="form.bio_ar" rows="5" maxlength="1500"
                          class="fld leading-relaxed"
                          placeholder="اكتب فقرة موجزة عن خبراتك ومجالات تخصصك وما يميّزك كمستشار. (٨٠ حرفاً على الأقل)"></textarea>
            </FormField>

            <!-- Bio En (optional) -->
            <FormField label="نبذة بالإنجليزية (اختياري)" :hint="`${(form.bio_en||'').length} / 1500`" :error="form.errors.bio_en">
                <textarea v-model="form.bio_en" rows="4" maxlength="1500" dir="ltr"
                          class="fld leading-relaxed"
                          placeholder="A short bio in English (optional)."></textarea>
            </FormField>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-soft">
                <Link href="/" class="text-[13px] text-ink-body hover:text-ink font-bold">إلغاء</Link>
                <button type="submit" :disabled="form.processing"
                        class="group inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-[1.03] disabled:opacity-60 disabled:hover:scale-100 transition-transform">
                    <span v-if="form.processing" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    التالي — المستندات
                    <svg class="w-3.5 h-3.5 rtl:rotate-180 group-hover:translate-x-0.5 rtl:group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
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

const props = defineProps({ application: Object, existing: Object });

const form = useForm({
    full_name_ar:       props.existing.full_name_ar ?? '',
    full_name_en:       props.existing.full_name_en ?? '',
    email:              props.existing.email        ?? '',
    national_id:        props.existing.national_id  ?? '',
    nationality:        props.existing.nationality  ?? 'سعودي',
    birth_date:         props.existing.birth_date   ?? '',
    gender:             props.existing.gender       ?? '',
    city:               props.existing.city         ?? '',
    country:            props.existing.country      ?? 'SA',
    phone:              props.existing.phone        ?? '',
    professional_title: props.existing.professional_title ?? '',
    bio_ar:             props.existing.bio_ar       ?? '',
    bio_en:             props.existing.bio_en       ?? '',
});

const genders = [{ v: 'male', label: 'ذكر' }, { v: 'female', label: 'أنثى' }];

// Max birth date = 18 years ago (validation matches server)
const maxBirthDate = computed(() => {
    const d = new Date(); d.setFullYear(d.getFullYear() - 18);
    return d.toISOString().slice(0, 10);
});

const submit = () => form.post('/become-a-consultant/step-1');
</script>

<style scoped>
.fld {
    display: block;
    width: 100%;
    height: 42px;
    padding: 0 14px;
    background: var(--bg-canvas);
    border: 1px solid var(--border-soft);
    border-radius: 12px;
    color: var(--ink-primary);
    font-size: 13.5px;
    font-family: inherit;
    transition: border-color 200ms ease, box-shadow 200ms ease;
}
textarea.fld {
    height: auto;
    padding: 10px 14px;
    resize: vertical;
}
.fld:focus {
    outline: none;
    border-color: #3DAFB9;
    box-shadow: 0 0 0 3px rgba(61, 175, 185, 0.15);
}
.fld::placeholder { color: var(--ink-muted); }
</style>
