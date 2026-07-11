<template>
    <!-- Visual connector between sections — makes the site feel like ONE body -->
    <div class="relative h-20 lg:h-24 flex items-center justify-center pointer-events-none" :class="bgClass">
        <!-- Vertical flowing line -->
        <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-px overflow-hidden">
            <div class="w-full h-full bg-gradient-to-b from-transparent via-[#3DAFB9]/40 to-transparent"></div>
            <!-- Traveling dot down the line -->
            <span class="absolute left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#3DAFB9] shadow-[0_0_8px_rgba(61,175,185,0.8)]"
                  style="animation: bridgeDot 2.8s ease-in-out infinite;"></span>
        </div>
        <!-- Center diamond node -->
        <div class="relative w-2.5 h-2.5 rotate-45 bg-elevated border border-[#3DAFB9]/50 shadow-sm"></div>
        <!-- Side fading lines -->
        <div class="absolute left-1/2 ml-4 rtl:ml-0 rtl:mr-4 w-16 h-px bg-gradient-to-r from-[#3DAFB9]/30 to-transparent"></div>
        <div class="absolute right-1/2 mr-4 rtl:mr-0 rtl:ml-4 w-16 h-px bg-gradient-to-l from-[#3DAFB9]/30 to-transparent"></div>
    </div>
</template>

<script setup>
defineProps({
    // Pass 'canvas' when the bridge sits on the canvas background for seamless blend
    bgClass: { type: String, default: '' },
});
</script>

<style>
@keyframes bridgeDot {
    0%   { top: 0%;   opacity: 0; }
    15%  { opacity: 1; }
    85%  { opacity: 1; }
    100% { top: 100%; opacity: 0; }
}
</style>
