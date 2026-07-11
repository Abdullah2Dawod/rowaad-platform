<template>
    <!-- =============================================================
         NAVBAR · Single floating pill (morphs on scroll)
         ============================================================= -->
    <nav
        class="fixed left-0 right-0 z-50 pointer-events-none px-3 sm:px-4 lg:px-0"
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
            <!-- ============ LOGO (uses admin settings when configured) ============ -->
            <a href="/" class="flex items-center shrink-0 group">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-tr from-[#3DAFB9]/20 to-[#2D4B7E]/20 blur-xl opacity-60 group-hover:opacity-100 transition-opacity duration-500 rounded-full"></div>
                    <img
                        :src="brandLogo"
                        :alt="siteName"
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

                <!-- ============ AUTH-AWARE CLUSTER ============ -->
                <template v-if="!authUser">
                    <a href="/become-a-consultant"
                       class="hidden 2xl:inline-flex items-center gap-1 px-2.5 h-8 rounded-full text-[11px] font-bold text-ink-body hover:text-[#3DAFB9] transition-colors shrink-0">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                        انضم كمستشار
                    </a>
                    <a
                        href="/login"
                        class="group relative inline-flex items-center gap-1.5 px-3 lg:px-3.5 h-8 rounded-full text-[#2D4B7E] dark:text-[#6BC8D2] hover:text-white dark:hover:text-white transition-all duration-500 overflow-hidden shrink-0"
                        style="border: 1px solid rgba(61, 175, 185, 0.40);"
                    >
                        <span class="absolute inset-0 bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] translate-x-full rtl:-translate-x-full group-hover:translate-x-0 transition-transform duration-500 ease-out"></span>
                        <span class="relative z-10 text-[11px] lg:text-[11.5px] font-bold whitespace-nowrap">تسجيل الدخول</span>
                        <svg class="relative z-10 w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>
                    </a>
                </template>

                <!-- Logged in — profile dropdown -->
                <div v-else class="relative">
                    <button @click="userMenuOpen = !userMenuOpen"
                            class="group inline-flex items-center gap-2 pr-3 pl-1.5 rtl:pr-1.5 rtl:pl-3 py-1 rounded-full border border-[#3DAFB9]/30 hover:border-[#3DAFB9] transition-colors">
                        <span class="w-7 h-7 rounded-full overflow-hidden bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] text-white text-[11px] font-black flex items-center justify-center shrink-0">
                            <img v-if="authUser.avatar_url" :src="authUser.avatar_url" :alt="authUser.name" class="w-full h-full object-cover" />
                            <template v-else>{{ (authUser.name || '?').charAt(0) }}</template>
                        </span>
                        <span class="hidden sm:inline text-[11.5px] font-bold text-ink truncate max-w-[100px]">{{ authUser.name }}</span>
                        <svg class="w-3 h-3 text-ink-body transition-transform" :class="userMenuOpen && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 -translate-y-2"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                    >
                        <div v-if="userMenuOpen" @click.outside="userMenuOpen = false"
                             class="absolute top-full mt-2 end-0 min-w-[240px] rounded-2xl bg-elevated border border-soft shadow-card-hover p-2 z-50">
                            <div class="flex items-center gap-3 px-3 py-2.5 border-b border-soft mb-1">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] text-white text-sm font-black flex items-center justify-center shrink-0">
                                    <img v-if="authUser.avatar_url" :src="authUser.avatar_url" :alt="authUser.name" class="w-full h-full object-cover" />
                                    <template v-else>{{ (authUser.name || '?').charAt(0) }}</template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[12.5px] font-black text-ink truncate">{{ authUser.name }}</div>
                                    <div class="text-[10.5px] text-ink-muted truncate">{{ authUser.email }}</div>
                                </div>
                            </div>
                            <a v-for="item in userMenuItems" :key="item.href" :href="item.href"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-[12.5px] text-ink-body hover:bg-[#3DAFB9]/10 hover:text-ink transition-colors">
                                <span v-html="item.icon" class="w-4 h-4 inline-flex text-[#3DAFB9]"></span>
                                {{ item.label }}
                            </a>
                            <form @submit.prevent="logout" class="mt-1 pt-1 border-t border-soft">
                                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-[12.5px] text-red-500 hover:bg-red-500/10 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </transition>
                </div>

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

        <!-- ============ MOBILE MENU · app-style drawer ============ -->
        <!-- Backdrop for tap-to-close -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="mobileOpen"
                class="lg:hidden fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm pointer-events-auto"
                @click="mobileOpen = false"
            ></div>
        </transition>

        <transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 -translate-y-6 scale-[0.98]"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 -translate-y-4 scale-[0.98]"
        >
            <div
                v-if="mobileOpen"
                class="lg:hidden pointer-events-auto mx-3 sm:mx-4 mt-3 rounded-[28px] overflow-hidden relative z-50"
                :style="mobileMenuStyle"
            >
                <!-- Top gradient strip -->
                <div class="h-1 bg-gradient-to-r from-[#2D4B7E] via-[#3DAFB9] to-[#6BC8D2]"></div>

                <!-- User card (logged in) OR CTA (guest) -->
                <div v-if="authUser" class="p-4 border-b border-[var(--border-soft)]">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] text-white text-base font-black flex items-center justify-center shrink-0 ring-2 ring-white dark:ring-[#1A2F50]"
                                 style="box-shadow: 0 6px 18px -4px rgba(45,75,126,.35);">
                                <img v-if="authUser.avatar_url" :src="authUser.avatar_url" :alt="authUser.name" class="w-full h-full object-cover" />
                                <template v-else>{{ (authUser.name || '?').charAt(0) }}</template>
                            </div>
                            <span class="absolute -bottom-0.5 -end-0.5 w-4 h-4 rounded-full border-2 border-white dark:border-[#1A2F50]"
                                  :style="{ background: authUser.role === 'admin' ? '#EF4444' : (authUser.role === 'consultant' ? '#3DAFB9' : '#10B981') }"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[13px] font-black text-ink truncate">{{ authUser.name }}</div>
                            <div class="text-[11px] text-ink-muted truncate">{{ authUser.email }}</div>
                            <div class="text-[10px] font-bold mt-0.5" style="color:#3DAFB9;">
                                {{ authUser.role === 'admin' ? 'مدير النظام' : (authUser.role === 'consultant' ? 'مستشار معتمد' : 'عضو') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="p-4 border-b border-[var(--border-soft)] grid grid-cols-2 gap-2">
                    <a href="/login"
                       class="flex items-center justify-center gap-1.5 h-11 rounded-2xl text-white font-bold text-[12.5px]"
                       style="background: linear-gradient(135deg,#2D4B7E,#3DAFB9); box-shadow: 0 8px 20px -6px rgba(61,175,185,.5);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>
                        تسجيل الدخول
                    </a>
                    <a href="/register"
                       class="flex items-center justify-center gap-1.5 h-11 rounded-2xl text-[#2D4B7E] dark:text-[#6BC8D2] font-bold text-[12.5px] border border-[#3DAFB9]/35 bg-white/50 dark:bg-transparent">
                        إنشاء حساب
                    </a>
                </div>

                <!-- Nav links -->
                <div class="p-3 space-y-0.5">
                    <a
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="group relative flex items-center gap-3 px-3 py-2.5 text-ink rounded-2xl transition-all text-[13.5px] font-bold overflow-hidden"
                        @click="mobileOpen = false"
                    >
                        <span class="absolute inset-0 bg-gradient-to-l rtl:bg-gradient-to-r from-[#3DAFB9]/0 to-[#2D4B7E]/0 group-hover:from-[#3DAFB9]/8 group-hover:to-[#2D4B7E]/6 transition-all rounded-2xl"></span>
                        <span class="relative w-8 h-8 rounded-xl bg-[#3DAFB9]/10 border border-[#3DAFB9]/20 flex items-center justify-center shrink-0 group-hover:bg-[#3DAFB9]/15 transition-colors" style="color:#3DAFB9;">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" v-html="link.iconPath"></svg>
                        </span>
                        <span class="relative flex-1">{{ link.label }}</span>
                        <svg class="relative w-3.5 h-3.5 text-[#3DAFB9] rtl:rotate-180 opacity-40 group-hover:opacity-100 group-hover:translate-x-0.5 rtl:group-hover:-translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Actions row: theme + language + become consultant -->
                <div class="p-3 border-t border-[var(--border-soft)] flex items-center gap-2">
                    <button @click="toggleTheme"
                            class="flex-1 flex items-center justify-center gap-1.5 h-10 rounded-xl text-ink-body text-[11.5px] font-bold border border-[var(--border-soft)] hover:border-[#3DAFB9]/40 transition-colors">
                        <svg v-if="!isDark" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path stroke-linecap="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                        <svg v-else class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                        {{ isDark ? 'وضع نهاري' : 'وضع ليلي' }}
                    </button>
                    <button @click="switchLocale(locale === 'ar' ? 'en' : 'ar')"
                            class="flex items-center justify-center gap-1 h-10 px-3 rounded-xl text-ink-body text-[11.5px] font-black border border-[var(--border-soft)] hover:border-[#3DAFB9]/40 transition-colors">
                        {{ locale === 'ar' ? 'EN' : 'ع' }}
                    </button>
                    <a v-if="!authUser" href="/become-a-consultant"
                       class="flex items-center justify-center gap-1 h-10 px-3 rounded-xl text-[11.5px] font-bold text-white"
                       style="background: linear-gradient(135deg,#3DAFB9,#2D4B7E);">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                        استشاري
                    </a>
                </div>

                <!-- User quick actions (logged in) -->
                <div v-if="authUser" class="p-3 border-t border-[var(--border-soft)] space-y-0.5">
                    <a v-for="item in userMenuItems" :key="item.href" :href="item.href"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[12.5px] text-ink-body hover:bg-[#3DAFB9]/10 hover:text-ink transition-colors font-semibold"
                       @click="mobileOpen = false">
                        <span v-html="item.icon" class="w-4 h-4 inline-flex" style="color:#3DAFB9;"></span>
                        {{ item.label }}
                    </a>
                    <form @submit.prevent="logout">
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[12.5px] text-red-500 hover:bg-red-500/10 transition-colors font-semibold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </transition>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { useTheme } from '@/composables/useTheme';
import { useI18n } from '@/composables/useI18n';

const { isDark, toggleTheme } = useTheme();
const { t, locale, switchLocale } = useI18n();

const page = usePage();
const authUser = computed(() => page.props.auth?.user ?? null);

// ─── Site settings from admin panel ───
const site = computed(() => page.props.site || {});
const siteName  = computed(() => site.value.name_ar || 'رواد بلا حدود');
const brandLogo = computed(() => {
    // Admin uploaded separate light/dark logos → use them; fallback to bundled assets.
    if (isDark.value) {
        return site.value.logo_dark || '/images/rowaad-logo-symbol-dark.png';
    }
    return site.value.logo || '/images/rowaad-logo-symbol.png';
});

const userMenuOpen = ref(false);

const userMenuItems = computed(() => {
    const role = authUser.value?.role;
    // Admins & consultants get BOTH the dashboard entry and their public profile
    if (role === 'admin' || role === 'consultant') {
        return [
            { label: 'لوحة التحكم', href: '/admin',
              icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>' },
            { label: 'ملفي الشخصي', href: '/profile',
              icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' },
        ];
    }
    // Regular user: no dashboard reference at all
    return [
        { label: 'ملفي الشخصي', href: '/profile',   icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' },
        { label: 'حجوزاتي',      href: '/bookings',  icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>' },
    ];
});

const logout = () => router.post('/logout');

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
    maxWidth: scrolled.value ? '1400px' : '1240px',
    padding: '5px 8px 5px 12px',
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
    { label: t('nav.home'),        href: '/',                     iconPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>' },
    { label: t('nav.about'),       href: '/about',                iconPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
    { label: t('nav.services'),    href: '/services',             iconPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>' },
    { label: t('nav.consultants'), href: '/consultants',          iconPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>' },
    { label: 'الفرص الاستثمارية',  href: '/investments',          iconPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>' },
    { label: 'دراسات الجدوى',      href: '/feasibility-studies',  iconPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>' },
    { label: t('nav.contact'),     href: '/contact',              iconPath: '<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>' },
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
