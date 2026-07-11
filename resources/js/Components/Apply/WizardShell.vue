<template>
    <MainLayout>
        <!-- ============ HEADER / PROGRESS ============ -->
        <section class="relative pt-32 lg:pt-36 pb-8 overflow-hidden bg-paper">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[15%] w-[420px] h-[420px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>
            </div>

            <div class="relative max-w-3xl mx-auto px-6 lg:px-10">
                <!-- Eyebrow -->
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex items-end gap-[3px] h-4">
                        <span class="block w-[3px] h-2.5 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse"></span>
                        <span class="block w-[3px] h-4 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: .2s;"></span>
                        <span class="block w-[3px] h-3 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: .4s;"></span>
                    </div>
                    <span class="text-[11px] tracking-[0.25em] uppercase font-bold text-[#3DAFB9]">انضم إلى فريق رواد</span>
                </div>

                <h1 class="text-2xl lg:text-3xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-2">تسجيل مستشار جديد</h1>
                <p class="text-[13.5px] text-ink-body">
                    ثلاث خطوات بسيطة، ستستغرق حوالي 10 دقائق. يمكنك الحفظ والعودة لاحقاً في أي وقت.
                </p>

                <!-- Step nodes -->
                <div class="relative mt-9">
                    <!-- Connector line -->
                    <div class="absolute top-4 right-4 rtl:right-4 rtl:left-4 left-4 h-0.5 bg-[var(--border-soft)] rounded-full"></div>
                    <div class="absolute top-4 right-4 rtl:right-4 h-0.5 bg-gradient-to-l rtl:bg-gradient-to-r from-[#3DAFB9] to-[#2D4B7E] rounded-full transition-all duration-700"
                         :style="{ width: progressWidth }"></div>

                    <div class="relative grid grid-cols-3 gap-2">
                        <div v-for="(s, i) in steps" :key="i" class="flex flex-col items-center">
                            <span class="relative z-10 flex w-9 h-9 rounded-full items-center justify-center text-[13px] font-black border-2 transition-all duration-500"
                                  :class="nodeClass(i + 1)">
                                <svg v-if="isDone(i + 1)" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <template v-else>{{ i + 1 }}</template>
                            </span>
                            <span class="mt-2 text-[11.5px] font-bold transition-colors" :class="labelClass(i + 1)">
                                {{ s.label }}
                            </span>
                            <span class="hidden sm:block text-[10px] text-ink-muted mt-0.5">{{ s.hint }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ CARD BODY ============ -->
        <section class="relative pb-24">
            <div class="max-w-3xl mx-auto px-6 lg:px-10">
                <div class="rounded-[1.5rem] bg-elevated border border-soft shadow-card p-6 lg:p-9">
                    <slot />
                </div>

                <!-- Save note -->
                <p class="text-center text-[11.5px] text-ink-muted mt-5">
                    تُحفظ بياناتك تلقائياً بعد كل خطوة — يمكنك الخروج والعودة في أي وقت.
                </p>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    current: { type: Number, required: true },
    completed: { type: Number, default: 0 },
});

const steps = [
    { label: 'البيانات الشخصية', hint: 'من أنت' },
    { label: 'المستندات',        hint: 'ملفات + شهادات' },
    { label: 'التخصص والخدمات',  hint: 'ما تقدّمه' },
];

const isDone   = (n) => n <= props.completed;
const isActive = (n) => n === props.current;

const nodeClass = (n) => {
    if (isDone(n))   return 'bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] text-white border-transparent shadow-md';
    if (isActive(n)) return 'bg-elevated text-[#3DAFB9] border-[#3DAFB9] shadow-sm ring-4 ring-[#3DAFB9]/15';
    return 'bg-elevated text-ink-muted border-[var(--border-soft)]';
};
const labelClass = (n) => {
    if (isActive(n) || isDone(n)) return 'text-ink';
    return 'text-ink-muted';
};

const progressWidth = computed(() => {
    // 3 nodes → 2 gaps → 50% each
    if (props.completed <= 0) return '0%';
    if (props.completed >= 3) return 'calc(100% - 2rem)';
    return props.completed === 1 ? 'calc(50% - 1rem)' : 'calc(100% - 2rem)';
});
</script>
