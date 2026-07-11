<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-24 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 right-[15%] w-[500px] h-[500px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
                <div class="absolute bottom-0 left-[10%] w-[400px] h-[400px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.10), transparent 70%); animation-delay: 3s;"></div>
            </div>

            <div class="relative max-w-2xl mx-auto px-6 lg:px-10 text-center">
                <div class="relative w-28 h-28 mx-auto mb-8">
                    <span class="absolute inset-0 rounded-full bg-[#3DAFB9]/15 animate-ping"></span>
                    <span class="absolute inset-2 rounded-full bg-[#3DAFB9]/20"></span>
                    <div class="relative w-full h-full rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center shadow-xl shadow-[#3DAFB9]/40">
                        <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>

                <div class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold mb-3">تم بنجاح</div>
                <h1 class="text-3xl lg:text-4xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-3">تم تأكيد حجزك</h1>
                <p class="text-[15px] text-ink-body leading-[1.9] max-w-lg mx-auto mb-2">
                    شكراً لك! تم استلام الدفع بنجاح، وسنُرسل لك تفاصيل الجلسة عبر البريد الإلكتروني.
                </p>
                <p class="text-[12px] text-ink-muted mb-8">مرجع الحجز: <span class="font-mono font-black text-ink" dir="ltr">{{ booking.reference }}</span></p>

                <!-- Details card -->
                <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 mb-8 text-right rtl:text-right shadow-card">
                    <div class="flex items-center gap-3 pb-5 mb-5 border-b border-soft">
                        <img :src="booking.consultant.avatar" :alt="booking.consultant.name" class="w-14 h-14 rounded-full object-cover shrink-0" />
                        <div class="min-w-0 flex-1">
                            <div class="text-[14px] font-black text-ink truncate">{{ booking.consultant.name }}</div>
                            <div class="text-[11.5px] text-ink-body truncate">{{ booking.consultant.title }}</div>
                        </div>
                    </div>
                    <dl class="grid grid-cols-2 gap-y-3 text-[13px]">
                        <dt class="text-ink-muted">التاريخ</dt><dd class="font-bold text-ink text-left">{{ booking.preferred_date }}</dd>
                        <dt class="text-ink-muted">الوقت</dt><dd class="font-bold text-ink text-left">{{ booking.preferred_time }}</dd>
                        <dt class="text-ink-muted">المدة</dt><dd class="font-bold text-ink text-left">{{ booking.duration_min }} دقيقة</dd>
                        <dt class="text-ink-muted">المبلغ</dt><dd class="font-bold text-gradient-brand text-left">{{ formatPrice(booking.amount) }} ر.س</dd>
                    </dl>
                </div>

                <!-- Meeting link card (only when confirmed) -->
                <div v-if="booking.status === 'confirmed' && booking.meeting_url"
                     class="relative overflow-hidden rounded-[1.5rem] p-6 lg:p-7 mb-8 text-right rtl:text-right shadow-card-hover bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50]">
                    <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full aurora-drift"
                         style="background: radial-gradient(circle, rgba(61,175,185,0.30), transparent 70%);"></div>
                    <div class="relative flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#3DAFB9] flex items-center justify-center shrink-0 shadow-lg shadow-[#3DAFB9]/40">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[10.5px] text-[#6BC8D2] tracking-[0.25em] uppercase font-bold mb-1">جلستك مؤكّدة</div>
                            <h3 class="text-lg font-black text-white mb-2">رابط الاجتماع جاهز</h3>
                            <p class="text-[12.5px] text-white/60 leading-relaxed mb-4">
                                افتح الرابط أدناه في الموعد المحدّد. ننصحك بالاتصال قبل الموعد بـ 5 دقائق لاختبار الصوت.
                            </p>
                            <a :href="booking.meeting_url" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-[#0A1729] text-[13px] font-black shadow-lg hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                الانضمام إلى الجلسة
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Next steps -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-8 text-right rtl:text-right">
                    <div v-for="(step, i) in nextSteps" :key="i"
                         :class="['p-5 rounded-2xl border transition-all',
                                  isStepDone(i) ? 'bg-[#3DAFB9]/8 border-[#3DAFB9]/30' : 'bg-elevated border-soft']">
                        <div class="flex items-center gap-2 mb-2">
                            <span :class="['w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-black',
                                           isStepDone(i) ? 'bg-[#3DAFB9] text-white' : 'bg-[#3DAFB9]/15 text-[#3DAFB9]']">
                                <svg v-if="isStepDone(i)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                                <template v-else>{{ i + 1 }}</template>
                            </span>
                            <span class="text-[13px] font-black text-ink">{{ step.title }}</span>
                        </div>
                        <p class="text-[12px] text-ink-body leading-relaxed">{{ step.desc }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-3 flex-wrap">
                    <Link href="/bookings" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-105 transition-transform">
                        حجوزاتي
                    </Link>
                    <Link href="/" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-elevated border border-soft text-ink text-[13px] font-bold hover:border-[#3DAFB9]/40 transition-colors">
                        العودة للرئيسية
                    </Link>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({ booking: Object });

const nextSteps = [
    { title: 'الدفع',        desc: 'تم استلام الدفع بنجاح.' },
    { title: 'تأكيد المستشار', desc: 'يؤكّد المستشار الحجز ويُرسل رابط الجلسة.' },
    { title: 'الاستشارة',    desc: 'التقِ بمستشارك في الموعد المحدّد وابدأ رحلتك.' },
];

// Step 0 (paid) done as soon as we hit this page.
// Step 1 (confirmed) done when meeting_url is set.
// Step 2 (session) done when status == completed.
const isStepDone = (i) => {
    if (i === 0) return true;
    if (i === 1) return ['confirmed', 'completed'].includes(props.booking.status);
    if (i === 2) return props.booking.status === 'completed';
    return false;
};

const formatPrice = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));
</script>
