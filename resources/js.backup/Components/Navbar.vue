<template>
    <!-- =============================================================
         NAVBAR · Single floating pill (morphs on scroll)
         ============================================================= -->
    <nav
        class="fixed left-0 right-0 z-50 pointer-events-none"
        :style="{
            top: scrolled ? '10px' : '18px',
            transition: 'top 700ms cubic-bezier(0.16, 1, 0.3, 1)',
        }"
    >
        <div
            ref="navShell"
            class="mx-auto flex items-center justify-between gap-2 lg:gap-4 pointer-events-auto relative"
            :style="navShellStyle"
        >
            <!-- ============ LOGO ============ -->
            <a href="/" class="flex items-center shrink-0 group">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-tr from-[#3DAFB9]/20 to-[#2D4B7E]/20 blur-xl opacity-60 group-hover:opacity-100 transition-opacity duration-500 rounded-full"></div>
                    <img
                        :src="isDark ? '/images/rowaad-logo-symbol-dark.png' : '/images/rowaad-logo-symbol.png'"
                        alt="رواد بلا حدود"
                        :style="{
                            height: scrolled ? '38px' : '42px',
                            transition: 'height 700ms cubic-bezier(0.16, 1, 0.3, 1)',
                        }"
                        class="relative object-contain w-auto logo-glow group-hover:scale-105 transition-transform duration-500"
                    />
                </div>
            </a>

            <!-- ============ NAV LINKS with sliding indicator ============ -->
            <div
                ref="navLinksContainer"
                class="hidden lg:flex items-center relative h-10 mx-1"
                @mouseleave="hideIndicator"
            >
                <div
                    class="absolute pointer-events-none rounded-full bg-gradient-to-br from-[#3DAFB9]/14 via-[#3DAFB9]/8 to-[#2D4B7E]/12 border border-[#3DAFB9]/20"
                    :style="indicatorStyle"
                ></div>

                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    @mouseenter="showIndicator($event)"
                    class="relative z-10 px-2.5 xl:px-3.5 h-10 flex items-center text-[12.5px] xl:text-[13px] text-ink hover:text-[#2D4B7E] dark:hover:text-[#6BC8D2] transition-colors duration-300 font-semibold tracking-tight group/link whitespace-nowrap"
                >
                    <span class="relative inline-block">
                        {{ link.label }}
                        <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] scale-0 group-hover/link:scale-100 transition-transform duration-300"></span>
                    </span>
                </a>
            </div>

            <!-- ============ RIGHT CLUSTER ============ -->
            <div class="flex items-center gap-1.5 shrink-0">
                <!-- Theme toggle -->
                <button
                    @click="toggleTheme"
                    class="relative flex items-center justify-center w-9 h-9 rounded-full text-ink-body hover:text-ink-strong transition-colors overflow-hidden group/theme"
                    :aria-label="isDark ? 'الوضع النهاري' : 'الوضع المسائي'"
                >
                    <span class="absolute inset-0 bg-gradient-to-br from-[#3DAFB9]/0 to-[#2D4B7E]/0 group-hover/theme:from-[#3DAFB9]/15 group-hover/theme:to-[#2D4B7E]/15 transition-all rounded-full"></span>
                    <span class="absolute inset-0 flex items-center justify-center" :style="{ opacity: isDark ? 0 : 1, transform: `scale(${isDark ? 0.4 : 1}) rotate(${isDark ? '-90deg' : '0deg'})`, transition: 'opacity 400ms ease-out, transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1)' }">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="4"/>
                            <path stroke-linecap="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                        </svg>
                    </span>
                    <span class="absolute inset-0 flex items-center justify-center" :style="{ opacity: isDark ? 1 : 0, transform: `scale(${isDark ? 1 : 0.4}) rotate(${isDark ? '0deg' : '90deg'})`, transition: 'opacity 400ms ease-out, transform 500ms cubic-bezier(0.34, 1.56, 0.64, 1)' }">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                        </svg>
                    </span>
                </button>

                <!-- Language -->
                <button
                    @click="switchLocale(locale === 'ar' ? 'en' : 'ar')"
                    class="hidden md:flex items-center justify-center w-9 h-9 rounded-full text-ink-body hover:text-ink-strong hover:bg-[#3DAFB9]/10 transition-all"
                    :title="locale === 'ar' ? 'English' : 'العربية'"
                >
                    <span class="text-[10.5px] font-bold tracking-wider">{{ locale === 'ar' ? 'EN' : 'ع' }}</span>
                </button>

                <!-- Divider -->
                <div class="hidden md:block w-px h-5 bg-[var(--border-soft)] mx-0.5"></div>

                <!-- CTA -->
                <a
                    href="/contact"
                    class="group relative inline-flex items-center gap-1.5 px-3.5 lg:px-4 py-2 rounded-full text-[#2D4B7E] dark:text-[#6BC8D2] hover:text-white dark:hover:text-white transition-all duration-500 overflow-hidden shrink-0"
                    style="border: 1px solid rgba(61, 175, 185, 0.40);"
                >
                    <span class="absolute inset-0 bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] translate-x-full rtl:-translate-x-full group-hover:translate-x-0 transition-transform duration-500 ease-out"></span>
                    <span class="relative z-10 text-[11.5px] lg:text-[12px] font-bold whitespace-nowrap">{{ t('nav.book_consultation') }}</span>
                    <svg class="relative z-10 w-3.5 h-3.5 rtl:rotate-180 group-hover:translate-x-0.5 rtl:group-hover:-translate-x-0.5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                <!-- Mobile toggle -->
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-full text-ink hover:bg-[#3DAFB9]/10 transition-colors">
                    <div class="w-5 h-4 flex flex-col justify-between">
                        <span :class="['h-0.5 bg-current rounded-full transition-all duration-300', mobileOpen ? 'rotate-45 translate-y-1.5 w-5' : 'w-5']"></span>
                        <span :class="['h-0.5 bg-current rounded-full transition-all duration-300', mobileOpen ? 'opacity-0' : 'w-3.5 ml-auto']"></span>
                        <span :class="['h-0.5 bg-current rounded-full transition-all duration-300', mobileOpen ? '-rotate-45 -translate-y-1.5 w-5' : 'w-5']"></span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <transition
            enter-active-class="transition duration-400 ease-out"
            enter-from-class="opacity-0 -translate-y-4 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-250 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 -translate-y-4 scale-95"
        >
            <div
                v-if="mobileOpen"
                class="lg:hidden pointer-events-auto mx-4 mt-3 rounded-3xl overflow-hidden"
                :style="mobileMenuStyle"
            >
                <div class="p-4 space-y-1">
                    <a
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="group relative flex items-center justify-between px-4 py-3 text-ink hover:text-[#2D4B7E] dark:hover:text-[#6BC8D2] rounded-2xl transition-all text-base font-semibold overflow-hidden"
                        @click="mobileOpen = false"
                    >
                        <span class="absolute inset-0 bg-gradient-to-br from-[#3DAFB9]/0 to-[#2D4B7E]/0 group-hover:from-[#3DAFB9]/10 group-hover:to-[#2D4B7E]/10 transition-all"></span>
                        <span class="relative">{{ link.label }}</span>
                        <svg class="relative w-3.5 h-3.5 text-[#3DAFB9] rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </transition>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useTheme } from '@/composables/useTheme';
import { useI18n } from '@/composables/useI18n';

const { isDark, toggleTheme } = useTheme();
const { t, locale, switchLocale } = useI18n();

const scrolled = ref(false);
const mobileOpen = ref(false);
const navShell = ref(null);
const navLinksContainer = ref(null);

const indicatorStyle = ref({
    opacity: 0, width: '0px', height: '36px', top: '50%', left: '0px',
    transform: 'translateY(-50%) translateX(0)', transition: 'opacity 250ms ease-out',
});

// Single-pill container styling — starts wider so all 7 links + logo + actions fit
const navShellStyle = computed(() => ({
    // WIDER initial width to fit all items comfortably (fixes overflow of the old design)
    maxWidth: scrolled.value ? '1320px' : '1080px',
    padding: '6px 8px 6px 14px',
    borderRadius: '999px',
    background: isDark.value
        ? (scrolled.value ? 'rgba(18, 36, 64, 0.78)' : 'rgba(18, 36, 64, 0.62)')
        : (scrolled.value ? 'rgba(255, 255, 255, 0.78)' : 'rgba(255, 255, 255, 0.62)'),
    backdropFilter: 'blur(28px) saturate(180%)',
    WebkitBackdropFilter: 'blur(28px) saturate(180%)',
    border: isDark.value ? '1px solid rgba(107, 200, 210, 0.18)' : '1px solid rgba(255, 255, 255, 0.85)',
    boxShadow: isDark.value
        ? (scrolled.value
            ? '0 1px 0 rgba(107,200,210,0.10) inset, 0 12px 32px -8px rgba(0,0,0,0.50), 0 24px 60px -16px rgba(0,0,0,0.60)'
            : '0 1px 0 rgba(107,200,210,0.12) inset, 0 16px 40px -10px rgba(0,0,0,0.55), 0 32px 80px -16px rgba(61,175,185,0.18)')
        : (scrolled.value
            ? '0 1px 0 rgba(255,255,255,0.95) inset, 0 8px 24px -8px rgba(45,75,126,0.10), 0 24px 60px -16px rgba(45,75,126,0.15)'
            : '0 1px 0 rgba(255,255,255,1) inset, 0 12px 32px -8px rgba(45,75,126,0.14), 0 32px 80px -16px rgba(61,175,185,0.18)'),
    transition: 'max-width 700ms cubic-bezier(0.16, 1, 0.3, 1), background 500ms ease-out, box-shadow 600ms ease-out',
}));

const mobileMenuStyle = computed(() => ({
    background: isDark.value ? 'rgba(18, 36, 64, 0.92)' : 'rgba(255, 255, 255, 0.92)',
    backdropFilter: 'blur(28px) saturate(180%)',
    WebkitBackdropFilter: 'blur(28px) saturate(180%)',
    border: isDark.value ? '1px solid rgba(107, 200, 210, 0.18)' : '1px solid rgba(255, 255, 255, 0.85)',
    boxShadow: '0 20px 60px -16px rgba(45, 75, 126, 0.25)',
}));

const navLinks = computed(() => [
    { label: t('nav.home'),        href: '/' },
    { label: t('nav.about'),       href: '/about' },
    { label: t('nav.services'),    href: '/services' },
    { label: t('nav.sectors'),     href: '/sectors' },
    { label: t('nav.consultants'), href: '/consultants' },
    { label: t('nav.blog'),        href: '/blog' },
    { label: t('nav.contact'),     href: '/contact' },
]);

const showIndicator = (event) => {
    const el = event.currentTarget;
    const container = navLinksContainer.value;
    if (!el || !container) return;
    const elRect = el.getBoundingClientRect();
    const containerRect = container.getBoundingClientRect();
    indicatorStyle.value = {
        opacity: 1, width: elRect.width + 'px', height: '36px', top: '50%', left: '0px',
        transform: `translateY(-50%) translateX(${elRect.left - containerRect.left}px)`,
        transition: 'transform 500ms cubic-bezier(0.16, 1, 0.3, 1), width 500ms cubic-bezier(0.16, 1, 0.3, 1), opacity 250ms ease-out',
    };
};
const hideIndicator = () => {
    indicatorStyle.value = { ...indicatorStyle.value, opacity: 0, transition: 'opacity 300ms ease-out' };
};

const handleScroll = () => { scrolled.value = window.scrollY > 60; };
onMounted(() => { window.addEventListener('scroll', handleScroll, { passive: true }); });
onUnmounted(() => { window.removeEventListener('scroll', handleScroll); });
</script>
