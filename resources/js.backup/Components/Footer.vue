<template>
    <footer class="relative bg-[#0A1729] text-white pt-20 pb-8 overflow-hidden">
        <!-- Decorative gradient line at top -->
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#3DAFB9]/70 to-transparent"></div>
        <!-- Subtle bg pattern -->
        <div class="absolute inset-0 grid-pattern opacity-[0.04]"></div>
        <div class="absolute -top-32 right-20 w-[400px] h-[400px] rounded-full" style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>

        <div class="relative max-w-[1400px] mx-auto px-6 lg:px-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 mb-14">
                <!-- Brand -->
                <div class="lg:col-span-4">
                    <img src="/images/rowaad-logo-transparent-dark.png" alt="رواد بلا حدود" class="h-24 object-contain mb-5 logo-glow" />
                    <p class="text-sm text-white/55 leading-relaxed mb-6 max-w-sm">
                        نُمكّن رواد الأعمال والمؤسسات من تحقيق أهدافهم الاقتصادية من خلال استشارات احترافية ودراسات جدوى متكاملة.
                    </p>
                    <div class="flex gap-2.5">
                        <a v-for="s in socials" :key="s.name" :href="s.url" target="_blank" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white/60 hover:text-white hover:bg-[#3DAFB9] hover:border-[#3DAFB9] transition-all">
                            <span class="text-xs font-bold">{{ s.icon }}</span>
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

                <!-- Services -->
                <div class="lg:col-span-3">
                    <h4 class="text-[11px] font-bold text-[#3DAFB9] tracking-[0.2em] uppercase mb-5">خدماتنا</h4>
                    <ul class="space-y-3">
                        <li v-for="s in services" :key="s">
                            <a href="#services" class="text-sm text-white/55 hover:text-white transition-colors">{{ s }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="lg:col-span-3">
                    <h4 class="text-[11px] font-bold text-[#3DAFB9] tracking-[0.2em] uppercase mb-5">تواصل معنا</h4>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-[#3DAFB9]/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-white/40 mb-0.5 uppercase tracking-wider">البريد الإلكتروني</div>
                                <a href="mailto:info@rowaad.org" class="text-sm text-white hover:text-[#3DAFB9] transition-colors">info@rowaad.org</a>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-[#3DAFB9]/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-white/40 mb-0.5 uppercase tracking-wider">اتصل بنا</div>
                                <a href="tel:+966" dir="ltr" class="text-sm text-white hover:text-[#3DAFB9] transition-colors">+966 5XX XXX XXX</a>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-[#3DAFB9]/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-white/40 mb-0.5 uppercase tracking-wider">الموقع</div>
                                <span class="text-sm text-white">الرياض، المملكة العربية السعودية</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/8 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-white/40">© {{ new Date().getFullYear() }} رواد بلا حدود للإستشارات الإقتصادية. جميع الحقوق محفوظة.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-xs text-white/40 hover:text-white transition-colors">سياسة الخصوصية</a>
                    <a href="#" class="text-xs text-white/40 hover:text-white transition-colors">الشروط والأحكام</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
const socials = [
    { name: 'X', icon: '𝕏', url: '#' },
    { name: 'LinkedIn', icon: 'in', url: '#' },
    { name: 'Instagram', icon: 'IG', url: '#' },
    { name: 'YouTube', icon: '▶', url: '#' },
];
const quickLinks = [
    { label: 'الرئيسية', href: '/' },
    { label: 'من نحن', href: '#about' },
    { label: 'المستشارون', href: '#consultants' },
    { label: 'القطاعات', href: '#sectors' },
    { label: 'المدونة', href: '#blog' },
    { label: 'تواصل معنا', href: '#contact' },
];
const services = [
    'الاستشارات الاقتصادية',
    'دراسات الجدوى',
    'التخطيط الاستراتيجي',
    'تطوير الأعمال',
    'الاستشارات المالية',
    'حلول الذكاء الاصطناعي',
];
</script>
