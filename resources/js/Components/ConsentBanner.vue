<template>
    <transition name="slide-up">
        <div v-if="visible" role="dialog" aria-labelledby="consent-title" aria-live="polite"
             class="fixed bottom-3 left-3 right-3 sm:left-6 sm:right-auto sm:max-w-md z-50">
            <div class="rounded-2xl bg-elevated border border-[#3DAFB9]/30 shadow-2xl p-5 backdrop-blur-md">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-[#3DAFB9]/12 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 id="consent-title" class="text-[13.5px] font-black text-ink mb-1">خصوصيتك تهمّنا</h3>
                        <p class="text-[12px] text-ink-body leading-[1.75]">
                            نستخدم ملفات تعريف الارتباط لتحسين تجربتك وقياس أداء الموقع، وفق
                            <a href="/privacy" class="text-[#3DAFB9] hover:underline font-bold">نظام حماية البيانات الشخصية السعودي (PDPL)</a>.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 justify-end">
                    <button @click="decline"
                            class="px-4 py-2 rounded-full text-[12px] font-bold text-ink-body hover:bg-canvas transition-colors">
                        الأساسية فقط
                    </button>
                    <button @click="accept"
                            class="px-5 py-2 rounded-full bg-gradient-to-l from-[#2D4B7E] to-[#3DAFB9] text-white text-[12px] font-black shadow-md hover:scale-105 transition-transform">
                        قبول الكل
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const STORAGE_KEY = 'rowaad_consent_v1';
const visible = ref(false);

onMounted(() => {
    try {
        if (! localStorage.getItem(STORAGE_KEY)) {
            // Delay slightly so first-paint isn't disrupted
            setTimeout(() => (visible.value = true), 600);
        }
    } catch { /* localStorage blocked → don't nag; default to essential-only */ }
});

function persist(choice) {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify({ choice, at: new Date().toISOString() })); } catch {}
    visible.value = false;
}
function accept()  { persist('all'); }
function decline() { persist('essential'); }
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active { transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease; }
.slide-up-enter-from { transform: translateY(120%); opacity: 0; }
.slide-up-leave-to   { transform: translateY(120%); opacity: 0; }
</style>
