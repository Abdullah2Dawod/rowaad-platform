<template>
    <footer class="relative bg-[#0A1729] text-white pt-14 sm:pt-20 pb-8 overflow-hidden">
        <!-- Decorative gradient line at top -->
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#3DAFB9]/70 to-transparent"></div>
        <!-- Subtle bg pattern -->
        <div class="absolute inset-0 grid-pattern opacity-[0.04]"></div>
        <div class="absolute -top-32 right-20 w-[400px] h-[400px] rounded-full" style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>

        <div class="relative max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 sm:gap-10 lg:gap-8 mb-10 sm:mb-14">
                <!-- Brand — uses admin logo setting -->
                <div class="lg:col-span-4">
                    <img :src="brandLogo" :alt="siteName" class="h-24 object-contain mb-5 logo-glow" />
                    <p class="text-sm text-white/55 leading-relaxed mb-6 max-w-sm">
                        {{ tagline }}
                    </p>
                    <div class="flex gap-2.5 flex-wrap">
                        <a v-for="s in activeSocials" :key="s.name" :href="s.url" target="_blank" rel="noopener"
                           class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white/60 hover:text-white hover:bg-[#3DAFB9] hover:border-[#3DAFB9] transition-all"
                           :title="s.name">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" v-html="s.iconSvg"></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="lg:col-span-2">
                    <h4 class="text-[11px] font-bold text-[#3DAFB9] tracking-[0.2em] uppercase mb-5">روابط سريعة</h4>
                    <ul class="space-y-3">
                        <li v-for="link in quickLinks" :key="link.label">
                            <a :href="link.href" class="text-sm text-white/55 hover:text-white transition-colors">{{ link.label }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Services (from DB) -->
                <div class="lg:col-span-3">
                    <h4 class="text-[11px] font-bold text-[#3DAFB9] tracking-[0.2em] uppercase mb-5">خدماتنا</h4>
                    <ul class="space-y-3">
                        <li v-for="s in services" :key="s">
                            <a href="/services" class="text-sm text-white/55 hover:text-white transition-colors">{{ s }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact — uses admin contact settings -->
                <div class="lg:col-span-3">
                    <h4 class="text-[11px] font-bold text-[#3DAFB9] tracking-[0.2em] uppercase mb-5">تواصل معنا</h4>
                    <div class="space-y-4">
                        <a v-if="contactEmail" :href="`mailto:${contactEmail}`" class="flex items-start gap-3 group">
                            <div class="w-9 h-9 rounded-lg bg-[#3DAFB9]/15 flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:bg-[#3DAFB9]/25 transition-colors">
                                <svg class="w-4 h-4 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-white/40 mb-0.5 uppercase tracking-wider">البريد الإلكتروني</div>
                                <span class="text-sm text-white group-hover:text-[#3DAFB9] transition-colors" dir="ltr">{{ contactEmail }}</span>
                            </div>
                        </a>
                        <a v-if="contactPhone" :href="`tel:${contactPhone.replace(/\s/g,'')}`" class="flex items-start gap-3 group">
                            <div class="w-9 h-9 rounded-lg bg-[#3DAFB9]/15 flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:bg-[#3DAFB9]/25 transition-colors">
                                <svg class="w-4 h-4 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-white/40 mb-0.5 uppercase tracking-wider">اتصل بنا</div>
                                <span dir="ltr" class="text-sm text-white group-hover:text-[#3DAFB9] transition-colors">{{ contactPhone }}</span>
                            </div>
                        </a>
                        <div v-if="contactAddress" class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-[#3DAFB9]/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-white/40 mb-0.5 uppercase tracking-wider">الموقع</div>
                                <span class="text-sm text-white">{{ contactAddress }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/8 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-white/40">© {{ new Date().getFullYear() }} {{ siteName }} للإستشارات الإقتصادية. جميع الحقوق محفوظة.</p>
                <div class="flex gap-6">
                    <Link href="/privacy" class="text-xs text-white/40 hover:text-white transition-colors">سياسة الخصوصية</Link>
                    <Link href="/terms" class="text-xs text-white/40 hover:text-white transition-colors">شروط الاستخدام</Link>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

// ─── Site settings from admin panel (via HandleInertiaRequests) ───
const site = computed(() => page.props.site || {});
const siteName       = computed(() => site.value.name_ar || 'رواد بلا حدود');
const tagline        = computed(() => site.value.tagline_ar || 'نُمكّن رواد الأعمال والمؤسسات من تحقيق أهدافهم الاقتصادية من خلال استشارات احترافية ودراسات جدوى متكاملة.');
const brandLogo      = computed(() => site.value.logo_dark || '/images/rowaad-logo-transparent-dark.png');
const contactEmail   = computed(() => site.value.contact_email);
const contactPhone   = computed(() => site.value.contact_phone);
const contactAddress = computed(() => site.value.contact_address);

// ─── Social media (only show those with URLs) ───
const socialIcons = {
    twitter:   '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
    linkedin:  '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.063 2.063 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>',
    instagram: '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>',
    facebook:  '<path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 011.141.195v3.325a8.623 8.623 0 00-.653-.036 26.805 26.805 0 00-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 00-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647z"/>',
    youtube:   '<path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>',
    tiktok:    '<path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>',
    snapchat:  '<path d="M12.166.006C7.66-.056 3.982 3.55 3.878 8.056c-.024 1.02.03 2.04.11 3.055-.157.024-.313.05-.47.075-.36.06-.706.185-1.006.386-.457.301-.688.877-.42 1.393.198.376.582.635 1.006.727.62.135 1.256.203 1.898.203-.24.63-.63 1.194-1.135 1.643-.483.43-1.13.658-1.756.658-.267 0-.542-.036-.795-.126-.36-.13-.762-.06-1.018.223-.192.216-.253.518-.156.79.12.325.386.567.71.685.83.315 1.735.397 2.615.243.093.242.216.472.373.68.63.837 1.615 1.34 2.658 1.34.09 0 .18-.003.27-.01.9-.06 1.735-.5 2.303-1.207.09-.112.166-.24.243-.36.375.152.76.278 1.15.376.7.174 1.42.245 2.14.212.72-.033 1.43-.174 2.113-.418.204.036.408.058.615.058.72 0 1.43-.24 1.988-.7.68-.56 1.043-1.4.976-2.26.48-.03.955-.132 1.395-.302.325-.126.582-.375.71-.697.098-.27.038-.575-.154-.79-.256-.286-.66-.353-1.02-.223-.253.09-.528.126-.795.126-.626 0-1.273-.228-1.756-.658-.505-.45-.895-1.014-1.135-1.643.642 0 1.278-.068 1.898-.203.424-.092.808-.35 1.006-.727.268-.516.037-1.092-.42-1.393-.3-.2-.646-.324-1.006-.386-.157-.024-.313-.05-.47-.075.08-1.015.135-2.035.11-3.055C20.184 3.55 16.505-.056 12.166.006z"/>',
    whatsapp:  '<path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.264.489 1.696.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>',
    telegram:  '<path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>',
};

const socialConfigs = [
    { name: 'X',         key: 'twitter'   },
    { name: 'LinkedIn',  key: 'linkedin'  },
    { name: 'Instagram', key: 'instagram' },
    { name: 'Facebook',  key: 'facebook'  },
    { name: 'YouTube',   key: 'youtube'   },
    { name: 'TikTok',    key: 'tiktok'    },
    { name: 'Snapchat',  key: 'snapchat'  },
    { name: 'WhatsApp',  key: 'whatsapp'  },
    { name: 'Telegram',  key: 'telegram'  },
];

const activeSocials = computed(() => {
    const socials = site.value.social || {};
    return socialConfigs
        .map(s => ({ name: s.name, url: socials[s.key], iconSvg: socialIcons[s.key] }))
        .filter(s => s.url && s.url.trim() !== '');
});

const quickLinks = [
    { label: 'الرئيسية',      href: '/' },
    { label: 'من نحن',        href: '/about' },
    { label: 'المستشارون',    href: '/consultants' },
    { label: 'دراسات الجدوى', href: '/feasibility-studies' },
    { label: 'القطاعات',      href: '/sectors' },
    { label: 'تواصل معنا',    href: '/contact' },
];

const services = [
    'الاستشارات الاقتصادية',
    'دراسات الجدوى',
    'التخطيط الاستراتيجي',
    'التحوّل الرقمي',
    'الاستشارات المالية',
    'الحوكمة والامتثال',
];
</script>
