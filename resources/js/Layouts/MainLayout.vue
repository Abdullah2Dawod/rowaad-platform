<template>
    <div class="min-h-screen bg-paper text-ink" style="overflow-x: clip;">
        <Navbar />
        <main>
            <slot />
        </main>
        <Footer />
        <!-- Live chat with support / consultant (all pages, always visible) -->
        <LiveChat />
    </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import LiveChat from '@/Components/LiveChat.vue';
import { useTheme } from '@/composables/useTheme';

useTheme();
</script>
