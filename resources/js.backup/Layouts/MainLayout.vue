<template>
    <div class="min-h-screen bg-paper text-ink" style="overflow-x: clip;">
        <Navbar />
        <main>
            <slot />
        </main>
        <Footer />
        <!-- Persistent floating consultation beacon (all pages, always visible) -->
        <FloatingConsult />
    </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import FloatingConsult from '@/Components/FloatingConsult.vue';
import { useTheme } from '@/composables/useTheme';

useTheme();
</script>
