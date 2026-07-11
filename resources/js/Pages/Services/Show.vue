<template>
    <MainLayout>
        <!-- HERO -->
        <section class="relative overflow-hidden bg-paper">
            <div class="relative h-[42vh] lg:h-[48vh] min-h-[360px] overflow-hidden">
                <img :src="service.hero_image" :alt="service.title" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-[#0A1729] via-[#0A1729]/60 to-[#0A1729]/30"></div>
            </div>
            <div class="absolute inset-0 flex items-end pb-8 lg:pb-14">
                <div class="max-w-[1300px] mx-auto px-6 lg:px-10 w-full">
                    <Link href="/services" class="inline-flex items-center gap-2 text-[12px] text-white/70 hover:text-white font-bold mb-5">
                        <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                        كل الخدمات
                    </Link>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-14 h-14 rounded-2xl bg-white/95 backdrop-blur flex items-center justify-center shadow-lg">
                            <img :src="iconUrl(service.icon)" class="w-7 h-7" alt="" />
                        </div>
                        <span v-if="service.featured" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white text-[10.5px] font-black shadow-md">
                            خدمة رئيسية
                        </span>
                    </div>
                    <h1 class="text-3xl lg:text-[2.6rem] font-black text-white leading-[1.15] mb-2 max-w-3xl">{{ service.title }}</h1>
                    <p class="text-[15px] lg:text-lg text-white/85 max-w-2xl">{{ service.tagline }}</p>
                </div>
            </div>
        </section>

        <!-- STATS STRIP -->
        <section class="relative -mt-8 lg:-mt-10 z-10">
            <div class="max-w-[1300px] mx-auto px-6 lg:px-10">
                <div class="grid grid-cols-3 gap-3 rounded-[1.5rem] bg-elevated border border-soft p-5 lg:p-6 shadow-card-hover">
                    <div v-for="(st, i) in service.stats" :key="i" class="text-center border-l last:border-l-0 rtl:border-l-0 rtl:border-r rtl:last:border-r-0 border-soft">
                        <div class="text-2xl lg:text-3xl font-black text-gradient-brand">{{ st.value }}</div>
                        <div class="text-[10.5px] text-ink-muted tracking-widest mt-1">{{ st.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- BODY -->
        <section class="relative py-14">
            <div class="max-w-[1300px] mx-auto px-6 lg:px-10">
                <div class="grid grid-cols-12 gap-8">
                    <div class="col-span-12 lg:col-span-8 space-y-8">
                        <!-- Summary -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8 shadow-card">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">وصف الخدمة</h2>
                            </div>
                            <p class="text-[14.5px] text-ink-body leading-[1.95]">{{ service.summary }}</p>
                        </div>

                        <!-- Includes -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8 shadow-card">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">تشمل الخدمة</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div v-for="(item, i) in service.includes" :key="i"
                                     class="flex items-start gap-3 p-3 rounded-xl bg-canvas border border-soft">
                                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/10 border border-[#3DAFB9]/20 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-[13.5px] text-ink-body leading-relaxed">{{ item }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Deliverables -->
                        <div class="rounded-[1.5rem] bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50] p-7 lg:p-8 shadow-card-hover relative overflow-hidden">
                            <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full aurora-drift"
                                 style="background: radial-gradient(circle, rgba(61,175,185,0.20), transparent 70%);"></div>
                            <div class="relative">
                                <div class="text-[10.5px] text-[#6BC8D2] tracking-widest font-black uppercase mb-2">المخرجات</div>
                                <h2 class="text-2xl font-black text-white mb-5">ما ستحصل عليه</h2>
                                <ul class="space-y-3">
                                    <li v-for="(d, i) in service.deliverables" :key="i" class="flex items-start gap-3 text-white/85 text-[14px]">
                                        <svg class="w-4 h-4 text-[#6BC8D2] shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M9 12l2 2 4-4M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                                        <span>{{ d }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- CTA sidebar -->
                    <aside class="col-span-12 lg:col-span-4">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card sticky top-28">
                            <div class="mb-5">
                                <div class="text-[10px] text-[#3DAFB9] tracking-widest font-black uppercase mb-1">جاهز للبدء؟</div>
                                <h3 class="text-lg font-black text-ink">اطلب هذه الخدمة</h3>
                                <p class="text-[12.5px] text-ink-body mt-1 leading-relaxed">
                                    اترك بياناتك وسيتواصل معك مستشار متخصّص خلال 24 ساعة.
                                </p>
                            </div>
                            <button @click="showModal = true"
                                    class="w-full py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.02] transition-transform">
                                <span class="inline-flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                                    طلب هذه الخدمة
                                </span>
                            </button>
                            <a href="/contact" class="mt-3 w-full py-3 rounded-full bg-elevated border border-soft text-ink text-[12.5px] font-bold text-center block hover:border-[#3DAFB9]/40 transition-colors">
                                أو تحدّث مع فريق المبيعات
                            </a>
                        </div>
                    </aside>
                </div>

                <!-- Related services -->
                <div v-if="related?.length" class="mt-16">
                    <h2 class="text-2xl font-black text-ink mb-5">خدمات أخرى قد تهمّك</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Link v-for="r in related" :key="r.slug" :href="`/services/${r.slug}`"
                              class="group flex items-start gap-3 p-4 rounded-2xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card transition-all">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/10 border border-[#3DAFB9]/20 flex items-center justify-center shrink-0">
                                <img :src="iconUrl(r.icon)" class="w-5 h-5" alt="" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-[13.5px] font-black text-ink group-hover:text-[#3DAFB9] transition-colors line-clamp-1">{{ r.title }}</div>
                                <div class="text-[11.5px] text-ink-body line-clamp-2 mt-0.5">{{ r.tagline }}</div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════ REQUEST MODAL (WIDE / PREMIUM) ═══════════ -->
        <PremiumModal v-model="showModal"
                      eyebrow="طلب خدمة"
                      :title="submitted ? 'تم استلام طلبك بنجاح' : 'تسجيل طلب خدمة'"
                      :subtitle="submitted ? null : service.title"
                      max-width="820px">
            <div v-if="!submitted">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Row 1: company & size -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <PremiumField v-model="form.company_name" label="اسم الشركة" required
                                      icon="buildings-2" placeholder="اسم شركتك أو مؤسستك"
                                      :error="form.errors.company_name" />
                        <PremiumField v-model="form.company_size" label="حجم الشركة" type="select"
                                      icon="users-group-rounded" :error="form.errors.company_size">
                            <option :value="null">اختر عدد الموظفين...</option>
                            <option value="1-10">1 – 10 موظفين</option>
                            <option value="11-50">11 – 50 موظفاً</option>
                            <option value="51-200">51 – 200 موظفاً</option>
                            <option value="201-1000">201 – 1,000 موظف</option>
                            <option value=">1000">أكثر من 1,000</option>
                        </PremiumField>
                    </div>

                    <!-- Row 2: contact name + phone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <PremiumField v-model="form.contact_name" label="اسم المسؤول" required
                                      icon="user" placeholder="الاسم الكامل"
                                      :error="form.errors.contact_name" />
                        <PremiumField v-model="form.contact_phone" label="رقم الجوال" required type="tel"
                                      icon="phone" dir="ltr" placeholder="+9665XXXXXXXX"
                                      :error="form.errors.contact_phone" />
                    </div>

                    <!-- Row 3: email -->
                    <PremiumField v-model="form.contact_email" label="البريد الإلكتروني" required type="email"
                                  icon="letter" dir="ltr" placeholder="you@company.com"
                                  :error="form.errors.contact_email" />

                    <!-- Row 4: timeline + budget -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <PremiumField v-model="form.timeline" label="الإطار الزمني" type="select"
                                      icon="clock-circle" :error="form.errors.timeline">
                            <option :value="null">متى تحتاج البدء؟</option>
                            <option value="urgent">عاجل (خلال أسبوعين)</option>
                            <option value="1m">خلال شهر</option>
                            <option value="3m">خلال 3 أشهر</option>
                            <option value="6m+">6 أشهر أو أكثر</option>
                        </PremiumField>
                        <PremiumField v-model="form.budget" label="الميزانية التقديرية" hint="اختياري" type="number"
                                      icon="wallet-money" prefix="ر.س" placeholder="50,000"
                                      :error="form.errors.budget" />
                    </div>

                    <!-- Row 5: brief -->
                    <PremiumField v-model="form.project_brief" label="اشرح مشروعك" hint="اختياري" type="textarea"
                                  icon="document-text" placeholder="أخبرنا باحتياجاتك وأهدافك ونطاق المشروع..."
                                  :maxlength="2000" show-counter :rows="4"
                                  :error="form.errors.project_brief" />

                    <!-- Privacy notice -->
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5 border border-[#3DAFB9]/20">
                        <img :src="iconLocked" alt="" class="w-6 h-6 shrink-0 mt-0.5" />
                        <div class="text-[12px] text-ink-body leading-relaxed">
                            <span class="font-bold text-ink">بياناتك مضمونة السرّية.</span>
                            نستخدمها فقط للتواصل معك بخصوص طلبك، ولا نشاركها مع أي طرف خارجي.
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-soft">
                        <button type="button" @click="showModal = false"
                                class="px-5 py-2.5 rounded-full text-[13px] text-ink-body hover:text-ink font-bold transition-colors">
                            إلغاء
                        </button>
                        <button type="submit" :disabled="form.processing"
                                class="premium-submit inline-flex items-center gap-2 px-8 py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13.5px] font-black shadow-lg shadow-[#3DAFB9]/30 disabled:opacity-60 transition-all">
                            <span v-if="form.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            إرسال الطلب
                        </button>
                    </div>
                </form>
            </div>

            <!-- SUCCESS STATE -->
            <div v-else class="text-center py-6">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <span class="absolute inset-0 rounded-full bg-[#3DAFB9]/20 animate-ping"></span>
                    <span class="absolute inset-2 rounded-full bg-[#3DAFB9]/25"></span>
                    <div class="relative w-full h-full rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center shadow-xl shadow-[#3DAFB9]/40">
                        <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
                <p class="text-[14px] text-ink-body leading-[1.9] max-w-md mx-auto mb-8">
                    شكراً لك! سيتواصل معك فريق رواد خلال <span class="font-black text-ink">24 ساعة عمل</span>
                    لبدء العمل على طلبك ومناقشة تفاصيل المشروع.
                </p>
                <button @click="showModal = false; submitted = false; form.reset();"
                        class="inline-flex px-7 py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13.5px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-105 transition-transform">
                    ممتاز
                </button>
            </div>
        </PremiumModal>
    </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import PremiumModal from '@/Components/UI/PremiumModal.vue';
import PremiumField from '@/Components/UI/PremiumField.vue';
import { useTheme } from '@/composables/useTheme';

const props = defineProps({ service: Object, related: Array });
const { isDark } = useTheme();

// Brand-colored Solar icon for privacy notice
const iconLocked = computed(() => {
    const color = isDark.value ? '6BC8D2' : '3DAFB9';
    return `https://api.iconify.design/solar:shield-keyhole-bold-duotone.svg?color=%23${color}&width=48`;
});

const showModal = ref(false);
const submitted = ref(false);

const form = useForm({
    service_slug:  props.service.slug,
    service_title: props.service.title,
    company_name:  '',
    contact_name:  '',
    contact_email: '',
    contact_phone: '',
    company_size:  null,
    budget:        null,
    timeline:      null,
    project_brief: '',
});

const submit = () => form.post('/services/request', {
    preserveScroll: true,
    onSuccess: () => { submitted.value = true; },
});

const iconUrl = (slug) => {
    const color = isDark.value ? '6BC8D2' : '3DAFB9';
    return `https://api.iconify.design/solar:${slug}.svg?color=%23${color}&width=48`;
};
</script>

<style scoped>
.premium-submit:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 12px 32px -8px rgba(61, 175, 185, 0.45);
}
</style>
