<template>
    <!-- ================================================================
         FLOATING CONSULTATION BEACON — simple, elegant, brand-tuned
         ================================================================ -->
    <a
        href="/contact"
        class="fab-beacon group fixed bottom-6 left-6 z-[70] flex items-center pointer-events-auto"
        aria-label="احجز استشارة مجانية"
    >
        <!-- === CORE BUTTON === -->
        <span class="relative block w-14 h-14 shrink-0">
            <!-- Soft pulsing halo (one, subtle) -->
            <span class="absolute inset-0 rounded-full bg-[#3DAFB9]/25 fab-halo"></span>

            <!-- Button body -->
            <span class="fab-core relative flex w-full h-full rounded-full bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] items-center justify-center shadow-lg shadow-[#3DAFB9]/30 border border-white/15">
                <!-- Chat bubble icon -->
                <svg class="w-6 h-6 text-white transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <!-- Notification dot -->
                <span class="absolute top-1 right-1 w-2.5 h-2.5 rounded-full bg-[#F59E0B] border-2 border-[#2D4B7E]"></span>
            </span>
        </span>

        <!-- === LABEL (expands on hover only — clean & simple) === -->
        <span class="fab-label overflow-hidden whitespace-nowrap">
            <span class="fab-pill flex items-center gap-2.5 mr-2.5 px-5 py-3 rounded-full bg-elevated border border-soft shadow-card">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                <span class="text-[13px] font-bold text-ink">احجز استشارتك المجانية</span>
                <svg class="fab-arrow w-3.5 h-3.5 text-[#3DAFB9] rtl:rotate-180 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </span>
        </span>
    </a>
</template>

<script setup>
// Persistent floating beacon — mounted from MainLayout.
</script>
