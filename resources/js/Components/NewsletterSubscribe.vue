<template>
    <div class="newsletter-widget">
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
        >
            <div v-if="flash" class="mb-3 p-3 rounded-2xl bg-green-500/10 border border-green-500/25 text-[12.5px] font-bold text-green-700 dark:text-green-400">
                ✅ {{ flash }}
            </div>
        </transition>

        <div class="relative overflow-hidden rounded-[1.5rem] p-6 lg:p-8 bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50] shadow-card-hover">
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full aurora-drift"
                 style="background: radial-gradient(circle, rgba(61,175,185,0.25), transparent 70%);"></div>
            <div class="absolute -bottom-20 -left-20 w-56 h-56 rounded-full aurora-drift"
                 style="background: radial-gradient(circle, rgba(45,75,126,0.30), transparent 70%); animation-delay: 3s;"></div>

            <div class="relative">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#6BC8D2] animate-pulse"></span>
                    <span class="text-[10.5px] font-black text-[#6BC8D2] tracking-widest">النشرة الأسبوعية</span>
                </div>
                <h3 class="text-xl lg:text-2xl font-black text-white leading-tight mb-2">
                    كن أول من يعرف
                </h3>
                <p class="text-[13px] text-white/70 leading-relaxed mb-5 max-w-md">
                    تحليلات اقتصادية، فرص استثمارية، ودراسات جدوى جديدة — مباشرة إلى بريدك كل أسبوع.
                </p>

                <form @submit.prevent="submit" class="space-y-3">
                    <div class="flex flex-col sm:flex-row gap-2.5">
                        <div class="flex-1 relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 rtl:right-4 rtl:left-auto w-4 h-4 text-white/50 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input v-model="form.email" type="email" required dir="ltr" placeholder="you@example.com"
                                   class="w-full h-11 pr-11 rtl:pr-11 rtl:pl-4 pl-4 rounded-full bg-white/10 border border-white/20 text-white text-[13px] font-medium placeholder:text-white/40 focus:outline-none focus:border-[#3DAFB9] focus:bg-white/15 transition-all" />
                        </div>
                        <button type="submit" :disabled="form.processing"
                                class="inline-flex items-center justify-center gap-2 px-6 h-11 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#6BC8D2] to-[#3DAFB9] text-[#0A1729] text-[13px] font-black shadow-lg shadow-[#3DAFB9]/25 hover:scale-[1.03] disabled:opacity-60 transition-transform whitespace-nowrap">
                            <span v-if="form.processing" class="w-3.5 h-3.5 border-2 border-[#0A1729] border-t-transparent rounded-full animate-spin"></span>
                            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            اشترك مجاناً
                        </button>
                    </div>
                    <div v-if="form.errors.email" class="text-[11.5px] text-red-300 font-medium">{{ form.errors.email }}</div>
                    <p class="text-[10.5px] text-white/40 leading-relaxed">
                        بالاشتراك أنت توافق على تلقي رسائلنا. يمكنك إلغاء الاشتراك في أي وقت بضغطة واحدة.
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({ source: { type: String, default: 'footer' } });

const page = usePage();
const flash = ref(null);

const form = useForm({
    email:  '',
    source: props.source,
    preferences: ['weekly'],
});

const submit = () => form.post('/newsletter/subscribe', {
    preserveScroll: true,
    onSuccess: () => {
        flash.value = page.props.flash?.success ?? 'شكراً! أرسلنا رابط تأكيد إلى بريدك.';
        form.reset('email');
        setTimeout(() => (flash.value = null), 6000);
    },
});
</script>
