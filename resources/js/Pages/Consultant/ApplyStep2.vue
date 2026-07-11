<template>
    <WizardShell :current="2" :completed="application.completed_step">
        <form @submit.prevent="submit" class="space-y-7">
            <div>
                <h2 class="text-lg font-black text-ink">المستندات والملفات</h2>
                <p class="text-[12.5px] text-ink-body mt-1">ارفع صورك ووثائقك الاحترافية. يمكنك تعديلها لاحقاً.</p>
            </div>

            <!-- Avatar -->
            <FormField label="الصورة الشخصية" required hint="JPG / PNG · حتى 4 ميجا" :error="form.errors.avatar">
                <div class="flex items-center gap-4">
                    <div class="relative w-20 h-20 rounded-2xl overflow-hidden border-2 border-dashed border-soft bg-canvas flex items-center justify-center shrink-0">
                        <img v-if="avatarPreview" :src="avatarPreview" alt="" class="w-full h-full object-cover" />
                        <svg v-else class="w-6 h-6 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <label class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-elevated border border-soft hover:border-[#3DAFB9]/40 cursor-pointer transition-colors">
                            <svg class="w-3.5 h-3.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-[12.5px] font-bold">{{ avatarPreview ? 'استبدال الصورة' : 'اختيار صورة' }}</span>
                            <input type="file" accept="image/*" class="sr-only" @change="onAvatar" />
                        </label>
                        <p class="text-[10.5px] text-ink-muted mt-1.5">صورة واضحة بخلفية بيضاء أو محايدة تُفضَّل.</p>
                    </div>
                </div>
            </FormField>

            <!-- CV -->
            <FormField label="السيرة الذاتية (CV)" required hint="PDF · حتى 8 ميجا" :error="form.errors.cv">
                <FileDrop
                    accept=".pdf"
                    :current-name="existing.cv_name"
                    :file="form.cv"
                    :hint-text="'اسحب ملف PDF إلى هنا أو اضغط للاختيار'"
                    @change="f => form.cv = f"
                />
            </FormField>

            <!-- National ID file -->
            <FormField label="صورة الهوية / الإقامة" required hint="PDF أو JPG · حتى 4 ميجا" :error="form.errors.national_id_file">
                <FileDrop
                    accept=".pdf,.jpg,.jpeg,.png"
                    :current-name="existing.national_id_name"
                    :file="form.national_id_file"
                    :hint-text="'صورة الوجه الأمامي للهوية أو الإقامة'"
                    @change="f => form.national_id_file = f"
                />
            </FormField>

            <!-- Certificates -->
            <div>
                <div class="flex items-baseline justify-between mb-2">
                    <span class="text-[12.5px] font-bold text-ink">الشهادات والاعتمادات</span>
                    <span class="text-[10.5px] text-ink-muted">حتى 10 شهادات</span>
                </div>

                <div class="space-y-3">
                    <div v-for="(cert, i) in form.certificates" :key="i"
                         class="p-4 rounded-2xl border border-soft bg-canvas">
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-[11.5px] font-black text-[#3DAFB9]">شهادة {{ i + 1 }}</span>
                            <button type="button" @click="removeCert(i)"
                                    class="text-[11px] text-red-500 hover:text-red-600 font-bold">حذف</button>
                        </div>
                        <input v-model="cert.title" type="text"
                               class="fld mb-2"
                               placeholder="عنوان الشهادة — مثال: CFA Level II" />
                        <FileDrop
                            accept=".pdf,.jpg,.jpeg,.png"
                            :current-name="cert.name"
                            :file="cert.file"
                            compact
                            @change="f => cert.file = f"
                        />
                    </div>
                </div>

                <button type="button" @click="addCert"
                        v-if="form.certificates.length < 10"
                        class="mt-3 w-full h-11 rounded-2xl border-2 border-dashed border-[#3DAFB9]/30 hover:border-[#3DAFB9] hover:bg-[#3DAFB9]/5 text-[#3DAFB9] font-bold text-[12.5px] transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                    إضافة شهادة
                </button>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-soft">
                <Link :href="'/become-a-consultant/step-1'" class="inline-flex items-center gap-1.5 text-[13px] text-ink-body hover:text-ink font-bold">
                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    السابق
                </Link>
                <button type="submit" :disabled="form.processing"
                        class="group inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-[1.03] disabled:opacity-60 disabled:hover:scale-100 transition-transform">
                    <span v-if="form.processing" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    التالي — التخصص
                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>
        </form>
    </WizardShell>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import WizardShell from '@/Components/Apply/WizardShell.vue';
import FormField from '@/Components/Apply/FormField.vue';
import FileDrop from '@/Components/Apply/FileDrop.vue';

const props = defineProps({ application: Object, existing: Object });

// Certificates need `file` field per row for reactive binding
const initialCerts = (props.existing.certificates ?? []).map(c => ({
    title: c.title || '',
    name:  c.name || null,
    file:  null,
}));
if (initialCerts.length === 0) initialCerts.push({ title: '', name: null, file: null });

const form = useForm({
    avatar: null,
    cv: null,
    national_id_file: null,
    certificates: initialCerts,
});

const avatarPreview = ref(props.existing.avatar_url || null);

const onAvatar = (e) => {
    const f = e.target.files?.[0];
    if (!f) return;
    form.avatar = f;
    avatarPreview.value = URL.createObjectURL(f);
};

const addCert    = () => form.certificates.push({ title: '', name: null, file: null });
const removeCert = (i) => form.certificates.splice(i, 1);

const submit = () => form.post('/become-a-consultant/step-2', { forceFormData: true });
</script>

<style scoped>
.fld {
    display: block; width: 100%; height: 40px; padding: 0 14px;
    background: var(--bg-elevated); border: 1px solid var(--border-soft);
    border-radius: 10px; color: var(--ink-primary);
    font-size: 13px; font-family: inherit;
    transition: border-color 200ms ease, box-shadow 200ms ease;
}
.fld:focus { outline: none; border-color: #3DAFB9; box-shadow: 0 0 0 3px rgba(61,175,185,0.15); }
</style>
