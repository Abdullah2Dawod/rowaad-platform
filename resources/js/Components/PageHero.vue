<template>
    <section class="relative pt-28 sm:pt-32 lg:pt-44 pb-10 sm:pb-16 lg:pb-24 overflow-hidden bg-paper">
        <!-- Layered background -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute inset-0" style="background:
                radial-gradient(ellipse 60% 50% at 85% 20%, rgba(61,175,185,0.10), transparent 70%),
                radial-gradient(ellipse 50% 50% at 10% 80%, rgba(45,75,126,0.07), transparent 70%);"></div>
            <div class="absolute inset-0 grid-pattern opacity-60"></div>
            <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-[var(--bg-paper)] to-transparent"></div>
        </div>

        <div class="relative max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-10 text-center">
            <!-- Breadcrumb-style brand line -->
            <div class="inline-flex items-center gap-3 mb-4 sm:mb-6">
                <div class="flex items-end gap-[3px] h-4">
                    <span class="block w-[3px] h-2.5 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse"></span>
                    <span class="block w-[3px] h-4 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: 0.2s;"></span>
                    <span class="block w-[3px] h-3 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: 0.4s;"></span>
                </div>
                <span class="text-[11px] tracking-[0.2em] uppercase font-bold text-[#3DAFB9]">{{ eyebrow }}</span>
            </div>

            <h1 class="text-[1.9rem] sm:text-4xl md:text-5xl lg:text-6xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.15] tracking-tight mb-4 sm:mb-5">
                {{ title }}
            </h1>

            <p v-if="subtitle" class="max-w-2xl mx-auto text-[14px] sm:text-base lg:text-lg text-ink-body leading-relaxed">
                {{ subtitle }}
            </p>

            <slot name="actions"></slot>
        </div>
    </section>
</template>

<script setup>
defineProps({
    eyebrow: { type: String, default: '' },
    title:   { type: String, required: true },
    subtitle:{ type: String, default: '' },
});
</script>
