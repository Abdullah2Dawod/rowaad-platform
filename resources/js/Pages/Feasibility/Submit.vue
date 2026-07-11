<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-24 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[15%] w-[420px] h-[420px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>
            </div>
            <div class="relative max-w-3xl mx-auto px-6 lg:px-10">
                <div class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold mb-3">اطرح دراستك</div>
                <h1 class="text-3xl lg:text-[2.4rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-tight mb-3">شارك دراستك مع رواد الأعمال</h1>
                <p class="text-[13.5px] text-ink-body mb-8">ارفع دراسة جدوى احترافية وسنراجعها خلال 24 ساعة. تحصل على نسبة من كل عملية بيع.</p>

                <form @submit.prevent="submit" class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-9 space-y-6 shadow-card">
                    <FormField label="عنوان الدراسة" required :error="form.errors.title">
                        <input v-model="form.title" type="text" class="fld" placeholder="مثال: دراسة جدوى مطعم متخصص في الرياض" />
                    </FormField>

                    <FormField label="ملخّص قصير" required :hint="`${(form.excerpt||'').length}/400`" :error="form.errors.excerpt">
                        <textarea v-model="form.excerpt" rows="3" maxlength="400" class="fld leading-relaxed"
                                  placeholder="نبذة موجزة تظهر في قائمة الدراسات (40-400 حرف)"></textarea>
                    </FormField>

                    <FormField label="الوصف الكامل" required :hint="`${(form.description||'').length}/8000`" :error="form.errors.description">
                        <textarea v-model="form.description" rows="8" maxlength="8000" class="fld leading-relaxed"
                                  placeholder="اشرح تفاصيل الدراسة: النطاق، المخرجات، الأقسام..."></textarea>
                    </FormField>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <FormField label="القطاع" required :error="form.errors.sector">
                            <input v-model="form.sector" type="text" class="fld" placeholder="مطاعم، عقارات، تجزئة..." />
                        </FormField>
                        <FormField label="التخصص" required :error="form.errors.specialization_id">
                            <select v-model.number="form.specialization_id" class="fld">
                                <option :value="null">— اختر —</option>
                                <option v-for="s in specializations" :key="s.id" :value="s.id">{{ s.name_ar }}</option>
                            </select>
                        </FormField>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <FormField label="عدد الصفحات (اختياري)" :error="form.errors.pages_count">
                            <input v-model.number="form.pages_count" type="number" min="1" max="5000" class="fld" />
                        </FormField>
                        <FormField label="السعر (ر.س)" required hint="0 = مجاناً" :error="form.errors.price">
                            <input v-model.number="form.price" type="number" min="0" max="50000" step="10" class="fld" />
                        </FormField>
                    </div>

                    <FormField label="صورة الغلاف (اختياري)" hint="JPG/PNG · حتى 4 ميجا" :error="form.errors.cover_image">
                        <FileDrop accept=".jpg,.jpeg,.png" :file="form.cover_image" @change="f => form.cover_image = f" />
                    </FormField>

                    <FormField label="ملف الدراسة (PDF)" required hint="حتى 30 ميجا" :error="form.errors.file">
                        <FileDrop accept=".pdf" :file="form.file" @change="f => form.file = f" />
                    </FormField>

                    <div class="p-4 rounded-2xl bg-blue-500/8 border border-blue-500/20 text-[12px] text-ink-body leading-relaxed">
                        <span class="font-bold">ملاحظة:</span> ستُراجَع الدراسة من فريقنا خلال 24 ساعة. عند القبول ستظهر في السوق ويصلك إشعار.
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-soft">
                        <Link href="/feasibility-studies" class="text-[13px] text-ink-body hover:text-ink font-bold">إلغاء</Link>
                        <button type="submit" :disabled="form.processing"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-[1.03] disabled:opacity-60 transition-transform">
                            <span v-if="form.processing" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            إرسال للمراجعة
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import FormField from '@/Components/Apply/FormField.vue';
import FileDrop from '@/Components/Apply/FileDrop.vue';

defineProps({ specializations: Array });

const form = useForm({
    title: '',
    excerpt: '',
    description: '',
    sector: '',
    specialization_id: null,
    pages_count: null,
    price: 0,
    cover_image: null,
    file: null,
});

const submit = () => form.post('/feasibility/submit', { forceFormData: true });
</script>

<style scoped>
.fld { display: block; width: 100%; height: 42px; padding: 0 14px; background: var(--bg-canvas); border: 1px solid var(--border-soft); border-radius: 12px; color: var(--ink-primary); font-size: 13.5px; font-family: inherit; transition: border-color 200ms ease, box-shadow 200ms ease; }
textarea.fld { height: auto; padding: 10px 14px; resize: vertical; }
.fld:focus { outline: none; border-color: #3DAFB9; box-shadow: 0 0 0 3px rgba(61,175,185,0.15); }
</style>
