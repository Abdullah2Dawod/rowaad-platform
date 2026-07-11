<template>
    <MainLayout>
        <!-- ================================================================ -->
        <!-- HERO — Premium light theme with orbital consulting visualization   -->
        <!-- ================================================================ -->
        <section class="relative pt-28 sm:pt-32 lg:pt-28 pb-12 sm:pb-16 lg:pb-20 overflow-hidden bg-paper">
            <!-- BACKGROUND LAYERS -->
            <div class="absolute inset-0 pointer-events-none">
                <!-- Professional consulting background image (very subtle, blurred) -->
                <div class="absolute inset-0 opacity-[0.10] dark:opacity-[0.06]" style="filter: blur(3px);">
                    <img
                        src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1600&auto=format&fit=crop&q=50"
                        alt=""
                        class="w-full h-full object-cover"
                        loading="lazy"
                    />
                </div>
                <!-- Overlay to fade the image -->
                <div class="absolute inset-0 bg-gradient-to-b from-paper/70 via-paper/60 to-paper/90 dark:from-paper/85 dark:via-paper/75 dark:to-paper/95"></div>

                <!-- Soft mesh gradient -->
                <div class="absolute inset-0" style="background:
                    radial-gradient(ellipse 60% 50% at 85% 20%, rgba(61,175,185,0.10), transparent 70%),
                    radial-gradient(ellipse 50% 50% at 10% 80%, rgba(45,75,126,0.07), transparent 70%);"></div>
                <!-- Subtle grid -->
                <div class="absolute inset-0 grid-pattern opacity-60"></div>

                <!-- ============ CONSULTING THEMED ELEMENTS — SPREAD ACROSS ENTIRE HERO ============ -->
                <!-- All very subtle (5-10% opacity), positioned in safe zones AROUND the circle -->

                <!-- ====== TOP STRIP ====== -->

                <!-- Top-LEFT: data constellation (above circle area) -->
                <svg class="absolute top-[3%] left-[3%] w-64 h-48 opacity-[0.08] hidden lg:block" viewBox="0 0 280 200">
                    <line x1="30" y1="60" x2="120" y2="30" stroke="#3DAFB9" stroke-width="0.8" stroke-dasharray="2 3"/>
                    <line x1="120" y1="30" x2="220" y2="80" stroke="#3DAFB9" stroke-width="0.8" stroke-dasharray="2 3"/>
                    <line x1="120" y1="30" x2="180" y2="140" stroke="#2D4B7E" stroke-width="0.8" stroke-dasharray="2 3"/>
                    <line x1="220" y1="80" x2="260" y2="40" stroke="#3DAFB9" stroke-width="0.8" stroke-dasharray="2 3"/>
                    <circle cx="30" cy="60" r="3" fill="#3DAFB9"/>
                    <circle cx="120" cy="30" r="4" fill="#2D4B7E"/>
                    <circle cx="220" cy="80" r="3" fill="#3DAFB9"/>
                    <circle cx="260" cy="40" r="3" fill="#3DAFB9"/>
                    <circle cx="180" cy="140" r="3" fill="#2D4B7E"/>
                </svg>

                <!-- Top-CENTER: floating "+" indicator -->
                <div class="absolute top-[4%] left-[45%] floating-element opacity-[0.07] hidden md:block" style="animation-duration: 10s;">
                    <div class="text-2xl font-black text-[#3DAFB9]">+27%</div>
                </div>

                <!-- Top-RIGHT: trend chart -->
                <svg class="absolute top-[8%] right-[5%] w-72 h-40 opacity-[0.07] hidden md:block" viewBox="0 0 300 180" fill="none">
                    <line x1="0" y1="40" x2="300" y2="40" stroke="#2D4B7E" stroke-width="0.5" stroke-dasharray="2 4" opacity="0.4"/>
                    <line x1="0" y1="100" x2="300" y2="100" stroke="#2D4B7E" stroke-width="0.5" stroke-dasharray="2 4" opacity="0.4"/>
                    <path d="M10,160 Q 50,130 80,120 T 150,80 T 220,50 T 290,20" stroke="#2D4B7E" stroke-width="2" stroke-linecap="round" fill="none"/>
                    <circle cx="290" cy="20" r="4" fill="#3DAFB9">
                        <animate attributeName="r" values="4;7;4" dur="3s" repeatCount="indefinite"/>
                    </circle>
                </svg>

                <!-- ====== MIDDLE STRIP (RIGHT side only — circle is in left middle) ====== -->

                <!-- Middle-right: upward arrow -->
                <svg class="absolute top-[38%] right-[12%] w-7 h-7 opacity-[0.10] floating-element hidden md:block" style="animation-duration: 8s;" viewBox="0 0 24 24" fill="none" stroke="#3DAFB9" stroke-width="2">
                    <path d="M5 12l7-7 7 7M12 5v14" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <!-- Middle-right: percentage -->
                <div class="absolute top-[45%] right-[28%] floating-element opacity-[0.08] hidden md:block" style="animation-duration: 11s; animation-delay: 2s;">
                    <div class="text-lg font-black text-[#2D4B7E]">+15.4%</div>
                </div>

                <!-- Far left-middle (above circle): only small accents -->
                <svg class="absolute top-[10%] left-[42%] w-5 h-5 opacity-[0.09] floating-element hidden lg:block" style="animation-duration: 7s; animation-delay: 1s;" viewBox="0 0 24 24" fill="none" stroke="#2D4B7E" stroke-width="2">
                    <path d="M5 12l7-7 7 7M12 5v14" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <!-- ====== BOTTOM STRIP (full width, below circle, AVOIDING right CTA area) ====== -->

                <!-- Bottom-left corner: ROI text (only far left, away from CTAs) -->
                <div class="absolute bottom-[8%] left-[5%] floating-element opacity-[0.07] hidden lg:block" style="animation-duration: 12s; animation-delay: 3s;">
                    <div class="text-xl font-black text-[#3DAFB9]">▲ ROI</div>
                </div>

                <!-- Floating ambient dots (scattered around but avoiding circle's core area) -->
                <span v-for="(dot, i) in ambientDots" :key="i"
                    class="absolute rounded-full bg-[#3DAFB9]"
                    :style="{
                        top: dot.top + '%',
                        left: dot.left + '%',
                        width: dot.size + 'px',
                        height: dot.size + 'px',
                        opacity: dot.opacity,
                        animation: `floatParticle ${dot.duration}s ease-in-out ${dot.delay}s infinite`,
                    }"></span>

                <!-- Bottom fade -->
                <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-[var(--bg-paper)] to-transparent"></div>
            </div>

            <div class="relative max-w-[1400px] mx-auto px-4 sm:px-4 sm:px-6 lg:px-10">
                <div class="grid grid-cols-12 gap-6 sm:gap-8 lg:gap-12 items-center">

                    <!-- ============= LEFT VISUAL (RTL: appears LEFT) ============= -->
                    <div ref="heroVisual" class="col-span-12 lg:col-span-6 lg:order-2 opacity-0 relative">
                        <div class="hero-orbit-container relative aspect-square w-full max-w-[300px] sm:max-w-[400px] md:max-w-[480px] lg:max-w-[560px] mx-auto">
                            <!-- Soft brand halo behind -->
                            <div class="absolute inset-[10%] rounded-full" style="background: radial-gradient(circle, rgba(61,175,185,0.18), rgba(45,75,126,0.10) 40%, transparent 70%);"></div>

                            <!-- Concentric soft rings (proportional) -->
                            <div class="absolute inset-0 rounded-full border-[1.5px] border-[#3DAFB9]/15"></div>
                            <div class="absolute inset-[8%] rounded-full border-[1.5px] border-[#2D4B7E]/10"></div>
                            <div class="absolute inset-[16%] rounded-full border border-[#3DAFB9]/20"></div>
                            <div class="absolute inset-[26%] rounded-full border border-[#3DAFB9]/25"></div>

                            <!-- Rotating dashed ring (slow) -->
                            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 600 600" ref="dashedRing">
                                <circle cx="300" cy="300" r="290" fill="none" stroke="#3DAFB9" stroke-width="1.2" stroke-dasharray="2 10" opacity="0.5"/>
                                <circle cx="300" cy="300" r="218" fill="none" stroke="#2D4B7E" stroke-width="1" stroke-dasharray="3 12" opacity="0.35"/>
                            </svg>

                            <!-- Inner orbit (services) — distance proportional to container -->
                            <div class="orbit-inner-spin absolute inset-0">
                                <div
                                    v-for="(svc, i) in orbitServices"
                                    :key="svc.label"
                                    class="orbit-inner-item absolute top-1/2 left-1/2 group"
                                    :style="{
                                        transform: `rotate(${i * (360 / orbitServices.length)}deg) translateY(calc(-1 * var(--orbit-inner-r))) rotate(-${i * (360 / orbitServices.length)}deg)`,
                                    }"
                                >
                                    <div class="orbit-inner-counter w-full h-full">
                                        <div class="absolute inset-0 bg-[#3DAFB9]/15 dark:bg-[#6BC8D2]/25 blur-xl rounded-full"></div>
                                        <div class="relative w-full h-full rounded-2xl bg-elevated border border-[#3DAFB9]/30 dark:border-[#6BC8D2]/40 dark:bg-[#1A2F50] flex items-center justify-center shadow-card group-hover:shadow-card-hover group-hover:scale-110 transition-all duration-500">
                                            <img :src="iconUrl(svc.iconSlug, isDark)" :alt="svc.label" class="w-[65%] h-[65%] object-contain" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Outer orbit (sector chips) — hidden on very small, distance proportional -->
                            <div class="orbit-outer-spin absolute inset-0 hidden sm:block">
                                <div
                                    v-for="(sec, i) in orbitSectors"
                                    :key="sec.label"
                                    class="absolute top-1/2 left-1/2"
                                    :style="{
                                        transform: `translate(-50%, -50%) rotate(${i * (360 / orbitSectors.length)}deg) translateY(calc(-1 * var(--orbit-outer-r))) rotate(-${i * (360 / orbitSectors.length)}deg)`,
                                    }"
                                >
                                    <span class="orbit-outer-counter block px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full bg-elevated border border-soft dark:border-[#6BC8D2]/30 text-[9.5px] sm:text-[10px] font-semibold text-ink-body dark:text-white shadow-sm whitespace-nowrap">
                                        {{ sec.label }}
                                    </span>
                                </div>
                            </div>

                            <!-- Center brand core (scales with container) -->
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[36%] h-[36%]">
                                <!-- Pulsing rings -->
                                <div class="absolute inset-0 rounded-full bg-[#3DAFB9]/15 animate-ping" style="animation-duration: 3.5s;"></div>
                                <div class="absolute inset-3 rounded-full bg-[#3DAFB9]/08 animate-ping" style="animation-duration: 4.5s; animation-delay: 1s;"></div>

                                <!-- Core circle with logo (auto-switches with theme) -->
                                <div class="relative w-full h-full rounded-full bg-elevated border-[3px] border-[#3DAFB9]/30 dark:border-[#6BC8D2]/50 flex items-center justify-center shadow-brand">
                                    <img :src="isDark ? '/images/rowaad-logo-transparent-dark.png' : '/images/rowaad-logo-transparent.png'" alt="رواد" class="w-[75%] h-[75%] object-contain logo-pulse" />
                                </div>
                            </div>

                            <!-- Pulse dot traveling around outer ring -->
                            <div class="absolute top-1/2 left-1/2 w-3 h-3 rounded-full bg-[#3DAFB9] shadow-[0_0_16px_rgba(61,175,185,0.8)] hidden sm:block" ref="pulseDot1"></div>
                            <div class="absolute top-1/2 left-1/2 w-2 h-2 rounded-full bg-[#2D4B7E] shadow-[0_0_12px_rgba(45,75,126,0.6)] hidden sm:block" ref="pulseDot2"></div>
                        </div>

                    </div>

                    <!-- ============= RIGHT TEXT (RTL: appears RIGHT) ============= -->
                    <div class="col-span-12 lg:col-span-6 lg:order-1">
                        <!-- ============ EDITORIAL-STYLE BRAND MARK (no badge, no border) ============ -->
                        <div ref="heroTop" class="opacity-0 mb-7 flex items-center gap-3.5">
                            <!-- Animated 3-bar accent (unique signature) -->
                            <div class="flex items-end gap-[3px] h-5">
                                <span class="block w-[3px] h-3 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: 0s;"></span>
                                <span class="block w-[3px] h-5 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: 0.2s;"></span>
                                <span class="block w-[3px] h-3.5 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: 0.4s;"></span>
                            </div>
                            <!-- Typographic brand line -->
                            <div class="flex items-baseline gap-2 text-[12.5px] leading-none">
                                <span class="font-black text-[#2D4B7E] dark:text-[#6BC8D2] tracking-tight">رواد بلا حدود</span>
                                <span class="w-1 h-1 rounded-full bg-[#3DAFB9]"></span>
                                <span class="text-ink-body tracking-[0.05em] font-medium">للإستشارات الإقتصادية</span>
                            </div>
                        </div>

                        <!-- Headline — navy from logo instead of pure black -->
                        <h1 ref="heroTitle" class="opacity-0 text-[1.75rem] sm:text-[2.25rem] md:text-[2.5rem] lg:text-[3rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.18] tracking-tight mb-4 sm:mb-5">
                            <span class="block">قرارات اقتصادية</span>
                            <span class="block">
                                <span class="text-gradient-brand">مدروسة</span>،
                                نتائج
                            </span>
                            <span class="relative inline-block">
                                <span class="text-gradient-brand">استثنائية</span>
                                <svg class="absolute -bottom-2.5 left-0 right-0 w-full pointer-events-none" viewBox="0 0 240 14" preserveAspectRatio="none">
                                    <path d="M3 9 Q 60 2, 120 7 T 237 7" stroke="#3DAFB9" stroke-width="2.5" fill="none" stroke-linecap="round" opacity="0.55"/>
                                </svg>
                            </span>
                        </h1>

                        <!-- Subtitle -->
                        <p ref="heroSub" class="opacity-0 text-[14px] sm:text-[15px] lg:text-base text-ink-body leading-[1.85] mb-7 sm:mb-9 max-w-lg">
                            نحن شريكك في صناعة <span class="text-[#2D4B7E] dark:text-[#C2EBEF] font-bold">القرارات الاستراتيجية</span>.
                            نُقدّم استشارات اقتصادية متخصصة ودراسات جدوى احترافية لرواد الأعمال والمؤسسات الطموحة.
                        </p>

                        <!-- CTAs — matching navbar hover style, arrow AFTER text -->
                        <div ref="heroCta" class="opacity-0 flex flex-col sm:flex-row items-stretch sm:items-start gap-3 mb-8 sm:mb-10 w-full sm:w-auto">
                            <!-- Primary CTA - bold filled style with sweep animation -->
                            <a
                                href="#contact"
                                class="group relative inline-flex items-center justify-center gap-2.5 px-6 sm:px-7 py-3 sm:py-3.5 rounded-full text-white overflow-hidden bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] shadow-lg shadow-[#3DAFB9]/25 hover:shadow-xl hover:shadow-[#3DAFB9]/35 hover:-translate-y-0.5 transition-all duration-500"
                            >
                                <!-- Shine sweep on hover -->
                                <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full rtl:translate-x-full rtl:group-hover:-translate-x-full transition-transform duration-1000 ease-out"></span>
                                <span class="relative z-10 text-[14px] font-bold whitespace-nowrap">احجز استشارتك المجانية</span>
                                <svg class="relative z-10 w-4 h-4 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>

                            <!-- Secondary CTA - outlined with navbar-matching fill animation -->
                            <a
                                href="#services"
                                class="group relative inline-flex items-center justify-center gap-2.5 px-6 sm:px-7 py-3 sm:py-3.5 rounded-full text-[#2D4B7E] dark:text-[#6BC8D2] hover:text-white dark:hover:text-white transition-all duration-500 overflow-hidden bg-paper"
                                style="border: 1px solid rgba(61, 175, 185, 0.40);"
                            >
                                <!-- Smooth fill animation (same as navbar button) -->
                                <span class="absolute inset-0 bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] translate-x-full rtl:-translate-x-full group-hover:translate-x-0 transition-transform duration-500 ease-out"></span>
                                <span class="relative z-10 text-[14px] font-bold whitespace-nowrap">اكتشف خدماتنا</span>
                                <svg class="relative z-10 w-4 h-4 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>

                        <!-- (Hero highlights removed per request) -->
                        <div ref="heroTrust" class="opacity-0"></div>
                    </div>
                </div>

            </div>
        </section>

        <!-- ================================================================ -->
        <!-- SERVICES MARQUEE — Floating pill chips, seamless infinite        -->
        <!-- Distinctive design: rounded card container, decorative orbs at edges -->
        <!-- ================================================================ -->
        <section class="relative py-14 lg:py-20 overflow-hidden">
            <div class="max-w-[1320px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="relative">
                    <!-- Decorative orbs at corners (entry/exit points) -->
                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 z-30 w-16 h-16 rounded-full bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/15 dark:from-[#3DAFB9]/25 dark:to-[#6BC8D2]/20 blur-2xl pointer-events-none"></div>
                    <div class="absolute -right-3 top-1/2 -translate-y-1/2 z-30 w-16 h-16 rounded-full bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/15 dark:from-[#3DAFB9]/25 dark:to-[#6BC8D2]/20 blur-2xl pointer-events-none"></div>

                    <!-- Container card with rounded edges + soft border -->
                    <div class="relative rounded-[2rem] overflow-hidden border border-soft bg-gradient-to-r from-transparent via-canvas/40 to-transparent dark:via-[#0F1F38]/40 backdrop-blur-sm">
                        <!-- Top accent line -->
                        <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#3DAFB9]/40 to-transparent"></div>
                        <!-- Bottom accent line -->
                        <div class="absolute bottom-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#3DAFB9]/40 to-transparent"></div>

                        <!-- Marquee track: 2 identical copies, -50% animation = perfect seamless loop -->
                        <div class="mask-fade-edges py-6 lg:py-7">
                            <div class="marquee-track" aria-hidden="false">
                                <!-- Copy 1 -->
                                <div class="flex shrink-0">
                                    <div
                                        v-for="(svc, i) in marqueeServices"
                                        :key="`a-${i}`"
                                        class="flex items-center gap-2 mx-2.5 lg:mx-3 shrink-0 px-4 lg:px-5 py-2 lg:py-2.5 rounded-full bg-elevated border border-soft shadow-sm dark:border-[#6BC8D2]/20"
                                    >
                                        <span class="w-7 h-7 lg:w-8 lg:h-8 rounded-full bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/12 dark:from-[#3DAFB9]/25 dark:to-[#6BC8D2]/15 flex items-center justify-center shrink-0">
                                            <img :src="iconUrl(svc.iconSlug, isDark)" :alt="svc.name" class="w-4 h-4 lg:w-4.5 lg:h-4.5 object-contain" loading="eager" />
                                        </span>
                                        <span class="text-[12px] lg:text-sm font-bold text-ink-strong tracking-tight whitespace-nowrap">{{ svc.name }}</span>
                                    </div>
                                </div>
                                <!-- Copy 2 (identical, aria-hidden so screen readers don't repeat) -->
                                <div class="flex shrink-0" aria-hidden="true">
                                    <div
                                        v-for="(svc, i) in marqueeServices"
                                        :key="`b-${i}`"
                                        class="flex items-center gap-2 mx-2.5 lg:mx-3 shrink-0 px-4 lg:px-5 py-2 lg:py-2.5 rounded-full bg-elevated border border-soft shadow-sm dark:border-[#6BC8D2]/20"
                                    >
                                        <span class="w-7 h-7 lg:w-8 lg:h-8 rounded-full bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/12 dark:from-[#3DAFB9]/25 dark:to-[#6BC8D2]/15 flex items-center justify-center shrink-0">
                                            <img :src="iconUrl(svc.iconSlug, isDark)" alt="" class="w-4 h-4 lg:w-4.5 lg:h-4.5 object-contain" loading="eager" />
                                        </span>
                                        <span class="text-[12px] lg:text-sm font-bold text-ink-strong tracking-tight whitespace-nowrap">{{ svc.name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <SectionBridge />

        <!-- ================================================================ -->
        <!-- WHO WE ARE                                                          -->
        <!-- ================================================================ -->
        <section id="about" class="relative py-14 sm:py-20 lg:py-24 overflow-hidden">
            <!-- Enhanced section background -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-10 right-0 w-[600px] h-[600px] rounded-full aurora-drift" style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>
                <div class="absolute bottom-10 left-0 w-[500px] h-[500px] rounded-full" style="background: radial-gradient(circle, rgba(45,75,126,0.08), transparent 70%);"></div>
                <div class="absolute inset-0 grid-pattern opacity-25"></div>
                <!-- Subtle ambient dots -->
                <span v-for="n in 8" :key="n"
                    class="absolute rounded-full bg-[#3DAFB9]"
                    :style="{
                        top: (5 + Math.random() * 90) + '%',
                        left: (5 + Math.random() * 90) + '%',
                        width: (3 + Math.random() * 3) + 'px',
                        height: (3 + Math.random() * 3) + 'px',
                        opacity: 0.08 + Math.random() * 0.10,
                        animation: `floatParticle ${12 + Math.random() * 8}s ease-in-out ${Math.random() * 5}s infinite`,
                    }"></span>
            </div>

            <div class="relative max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="grid grid-cols-12 gap-12 items-start lg:items-center">
                    <div ref="aboutHeader" class="col-span-12 lg:col-span-5 opacity-0">
                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <span class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">01 · من نحن</span>
                                <div class="h-px flex-1 bg-gradient-to-r from-[#3DAFB9] to-transparent"></div>
                            </div>
                            <h2 class="text-4xl lg:text-5xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.1]">
                                نُمكّن<br>
                                <span class="text-gradient-brand">القرارات الاقتصادية</span><br>
                                بدقة وخبرة
                            </h2>
                            <p class="text-ink-body leading-relaxed">
                                نحن منصة استشارية متخصصة تجمع بين الخبرة المحلية والمعايير العالمية، نخدم رواد الأعمال والمؤسسات في رحلتهم نحو النمو والازدهار.
                            </p>
                            <a href="/about" class="inline-flex items-center gap-2 text-sm font-bold text-[#2D4B7E] dark:text-[#6BC8D2] hover:text-[#3DAFB9] transition-colors group">
                                تعرّف على القصة كاملة
                                <svg class="w-4 h-4 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>

                    <div ref="aboutContent" class="col-span-12 lg:col-span-7 opacity-0">

                        <!-- ================================================================
                             HUB & BRANCHES — central node with 4 branching pillars
                             (yaqn.sa-inspired, consulting-themed, soft circular images)
                             ================================================================ -->

                        <!-- ===== DESKTOP: radial hub layout ===== -->
                        <div class="hidden lg:block relative" style="min-height: 620px;">

                            <!-- Connecting branches SVG (behind everything) -->
                            <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 700 620" preserveAspectRatio="none" fill="none">
                                <defs>
                                    <linearGradient id="branchGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#2D4B7E" stop-opacity="0.45"/>
                                        <stop offset="100%" stop-color="#3DAFB9" stop-opacity="0.45"/>
                                    </linearGradient>
                                </defs>
                                <!-- 4 curved branches — endpoints land EXACTLY at node-circle centers -->
                                <path d="M398,262 Q 500,155 582,64"  stroke="url(#branchGrad)" stroke-width="1.5" stroke-dasharray="5 7" class="dash-flow"/>
                                <path d="M302,262 Q 200,155 118,64"  stroke="url(#branchGrad)" stroke-width="1.5" stroke-dasharray="5 7" class="dash-flow" style="animation-delay: 0.6s;"/>
                                <path d="M302,358 Q 200,428 118,468" stroke="url(#branchGrad)" stroke-width="1.5" stroke-dasharray="5 7" class="dash-flow" style="animation-delay: 1.2s;"/>
                                <path d="M398,358 Q 500,428 582,468" stroke="url(#branchGrad)" stroke-width="1.5" stroke-dasharray="5 7" class="dash-flow" style="animation-delay: 1.8s;"/>
                                <!-- Pulsing junction dots on branches (midpoints) -->
                                <circle cx="497" cy="152" r="3.5" fill="#3DAFB9"><animate attributeName="opacity" values="1;0.3;1" dur="2.5s" repeatCount="indefinite"/></circle>
                                <circle cx="203" cy="152" r="3.5" fill="#2D4B7E"><animate attributeName="opacity" values="0.3;1;0.3" dur="2.5s" repeatCount="indefinite"/></circle>
                                <circle cx="203" cy="422" r="3.5" fill="#3DAFB9"><animate attributeName="opacity" values="1;0.3;1" dur="3s" repeatCount="indefinite"/></circle>
                                <circle cx="497" cy="422" r="3.5" fill="#2D4B7E"><animate attributeName="opacity" values="0.3;1;0.3" dur="3s" repeatCount="indefinite"/></circle>
                            </svg>

                            <!-- ===== CENTRAL HUB ===== -->
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
                                <div class="relative">
                                    <!-- Pulsing halo rings -->
                                    <div class="absolute inset-0 rounded-full bg-[#3DAFB9]/12 animate-ping" style="animation-duration: 3.5s;"></div>
                                    <div class="absolute -inset-4 rounded-full border border-[#3DAFB9]/20" style="animation: brandSpin 24s linear infinite;"></div>
                                    <div class="absolute -inset-8 rounded-full border border-dashed border-[#3DAFB9]/15" style="animation: brandSpin 40s linear infinite reverse;"></div>
                                    <!-- Hub core -->
                                    <div class="relative w-36 h-36 rounded-full bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50] border-2 border-[#3DAFB9]/40 shadow-brand flex flex-col items-center justify-center text-center p-4">
                                        <img :src="'/images/rowaad-logo-symbol-dark.png'" alt="رواد" class="w-14 h-auto object-contain mb-1.5" />
                                        <span class="text-[10px] font-bold text-white leading-tight">لماذا رواد<br>بلا حدود؟</span>
                                    </div>
                                </div>
                            </div>

                            <!-- ===== 4 BRANCH NODES ===== -->
                            <div
                                v-for="(item, i) in pillars"
                                :key="i"
                                class="absolute z-10 w-52 group"
                                :style="{
                                    top:  i < 2 ? '0%' : 'auto',
                                    bottom: i >= 2 ? '0%' : 'auto',
                                    left: (i === 1 || i === 2) ? '2%' : 'auto',
                                    right: (i === 0 || i === 3) ? '2%' : 'auto',
                                }"
                            >
                                <div class="flex flex-col items-center text-center">
                                    <!-- Circular image node -->
                                    <div class="relative mb-4">
                                        <!-- Soft glow -->
                                        <div class="absolute -inset-2 rounded-full bg-[#3DAFB9]/0 group-hover:bg-[#3DAFB9]/20 blur-xl transition-all duration-700"></div>
                                        <!-- Rotating dashed ring -->
                                        <svg class="absolute -inset-2.5 w-[calc(100%+1.25rem)] h-[calc(100%+1.25rem)] opacity-50 group-hover:opacity-100 transition-opacity duration-500" style="animation: brandSpin 20s linear infinite;" viewBox="0 0 120 120" fill="none">
                                            <circle cx="60" cy="60" r="57" stroke="#3DAFB9" stroke-width="1.5" stroke-dasharray="4 9"/>
                                        </svg>
                                        <!-- Image circle (soft treatment) -->
                                        <div class="relative w-28 h-28 rounded-full overflow-hidden ring-4 ring-elevated shadow-card group-hover:shadow-card-hover transition-all duration-500 group-hover:scale-105">
                                            <img
                                                :src="item.image"
                                                :alt="item.title"
                                                loading="lazy"
                                                class="w-full h-full object-cover saturate-[0.6] contrast-[0.92] group-hover:saturate-[0.9] transition-all duration-700"
                                            />
                                            <!-- Soft brand tint (softens harshness) -->
                                            <div class="absolute inset-0 bg-gradient-to-br from-[#2D4B7E]/40 to-[#3DAFB9]/25 group-hover:from-[#2D4B7E]/20 group-hover:to-[#3DAFB9]/10 transition-all duration-700 mix-blend-multiply"></div>
                                        </div>
                                        <!-- Icon badge overlapping the circle -->
                                        <div class="absolute -bottom-1 -right-1 rtl:-right-auto rtl:-left-1 w-10 h-10 rounded-full bg-elevated border-2 border-[#3DAFB9]/35 shadow-card flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 z-10">
                                            <img :src="iconUrl(item.iconSlug, isDark)" class="w-5 h-5" :alt="item.title" />
                                        </div>
                                        <!-- Number chip -->
                                        <div class="absolute -top-1 -left-1 rtl:-left-auto rtl:-right-1 w-6 h-6 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center shadow-md z-10">
                                            <span class="text-[9px] font-black text-white">{{ String(i + 1).padStart(2, '0') }}</span>
                                        </div>
                                    </div>
                                    <!-- Text -->
                                    <h4 class="text-[15px] font-bold text-ink mb-1.5 group-hover:text-[#2D4B7E] dark:group-hover:text-[#6BC8D2] transition-colors duration-500">{{ item.title }}</h4>
                                    <p class="text-[12px] text-ink-body leading-relaxed">{{ item.desc }}</p>
                                    <!-- Accent line grows on hover -->
                                    <div class="mt-2.5 h-0.5 w-6 rounded-full bg-gradient-to-r from-[#3DAFB9] to-[#2D4B7E] group-hover:w-16 transition-all duration-500"></div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== MOBILE: vertical branch timeline ===== -->
                        <div class="lg:hidden relative">
                            <!-- Center spine -->
                            <div class="absolute right-[3.4rem] rtl:right-[3.4rem] top-4 bottom-4 w-px bg-gradient-to-b from-[#3DAFB9]/50 via-[#2D4B7E]/30 to-[#3DAFB9]/50"></div>
                            <div class="space-y-7">
                                <div v-for="(item, i) in pillars" :key="i" class="relative flex items-start gap-5 group">
                                    <!-- Circular image node -->
                                    <div class="relative shrink-0">
                                        <div class="relative w-[6.8rem] h-[6.8rem] rounded-full overflow-hidden ring-4 ring-elevated shadow-card">
                                            <img :src="item.image" :alt="item.title" loading="lazy" class="w-full h-full object-cover saturate-[0.6] contrast-[0.92]" />
                                            <div class="absolute inset-0 bg-gradient-to-br from-[#2D4B7E]/40 to-[#3DAFB9]/25 mix-blend-multiply"></div>
                                        </div>
                                        <div class="absolute -bottom-1 -left-1 w-9 h-9 rounded-full bg-elevated border-2 border-[#3DAFB9]/35 shadow-card flex items-center justify-center">
                                            <img :src="iconUrl(item.iconSlug, isDark)" class="w-4.5 h-4.5" :alt="item.title" />
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center shadow-md">
                                            <span class="text-[9px] font-black text-white">{{ String(i + 1).padStart(2, '0') }}</span>
                                        </div>
                                    </div>
                                    <!-- Text -->
                                    <div class="pt-3">
                                        <h4 class="text-[15px] font-bold text-ink mb-1">{{ item.title }}</h4>
                                        <p class="text-[12.5px] text-ink-body leading-relaxed">{{ item.desc }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <SectionBridge />

        <!-- ================================================================ -->
        <!-- SERVICES — STICKY-SCROLL EXPERTISE (rebuilt, sticky works)         -->
        <!-- NOTE: no overflow-hidden on the section (breaks position: sticky) -->
        <!-- ================================================================ -->
        <section id="services" ref="expertiseSection" class="relative py-14 sm:py-20 lg:py-28 bg-canvas">
            <!-- Background layers wrapped in clipped div to preserve sticky -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 right-0 w-[600px] h-[600px] rounded-full" style="background: radial-gradient(circle, rgba(61,175,185,0.08), transparent 70%);"></div>
                <div class="absolute bottom-0 left-0 w-[500px] h-[500px] rounded-full" style="background: radial-gradient(circle, rgba(45,75,126,0.06), transparent 70%);"></div>
                <div class="absolute inset-0 dot-pattern opacity-30"></div>
                <!-- Scroll progress rail (LEFT edge) -->
                <div class="hidden lg:block absolute left-2 top-0 bottom-0 w-[3px]">
                    <div class="w-full h-full bg-gradient-to-b from-transparent via-[#3DAFB9]/10 to-transparent"></div>
                    <div class="absolute top-0 left-0 w-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E] rounded-full transition-all duration-700 ease-out"
                         :style="{ height: `${((activeDomain + 1) / expertise.length) * 100}%` }"></div>
                </div>
            </div>

            <!-- Section header -->
            <div class="relative max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10 mb-16 lg:mb-24">
                <div ref="servicesHeader" class="opacity-0 max-w-3xl">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">02 · مجالات خبراتنا</span>
                        <div class="h-px w-24 bg-gradient-to-r from-[#3DAFB9] to-transparent"></div>
                    </div>
                    <h2 class="text-4xl lg:text-5xl xl:text-6xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.08] tracking-tight">
                        خبرة متخصصة<br>
                        في <span class="text-gradient-brand">6 مجالات</span> استشارية
                    </h2>
                    <p class="mt-5 text-ink-body max-w-xl leading-relaxed">
                        نستثمر خبرتنا العميقة في مجالات الاستشارات الاقتصادية لنقدّم حلولاً متخصصة تُحدث فرقاً حقيقياً في نمو أعمالك.
                    </p>
                </div>
            </div>

            <!-- ===== STICKY-SCROLL BODY ===== -->
            <div class="relative max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="lg:grid lg:grid-cols-12 lg:gap-12 xl:gap-16">

                    <!-- ===== CARDS COLUMN (FIRST in DOM = RIGHT in RTL) ===== -->
                    <!-- lg:pb gives the sticky illustration room to stay centered for card #6 -->
                    <div class="lg:col-span-7 lg:pb-36">
                        <div class="space-y-8 lg:space-y-10">
                            <article
                                v-for="(d, i) in expertise"
                                :key="i"
                                :data-expertise-card="i"
                                :class="[
                                    'group relative rounded-[2rem] border p-8 lg:p-10 overflow-hidden transition-all duration-500',
                                    'min-h-[60vh] lg:min-h-[70vh] flex flex-col justify-center',
                                    activeDomain === i
                                        ? 'bg-elevated border-[#3DAFB9]/50 shadow-card-hover'
                                        : 'bg-elevated/70 border-soft hover:border-[#3DAFB9]/30'
                                ]"
                            >
                                <!-- Active state top accent line -->
                                <div class="absolute top-0 left-8 right-8 h-px bg-gradient-to-r from-transparent via-[#3DAFB9] to-transparent transition-opacity duration-700"
                                     :style="{ opacity: activeDomain === i ? 1 : 0 }"></div>

                                <!-- Corner decorative brackets (active) -->
                                <div class="absolute top-4 left-4 rtl:left-auto rtl:right-4 w-6 h-6 border-t-2 border-l-2 rtl:border-l-0 rtl:border-r-2 border-[#3DAFB9] transition-opacity duration-700"
                                     :style="{ opacity: activeDomain === i ? 0.5 : 0 }"></div>
                                <div class="absolute bottom-4 right-4 rtl:right-auto rtl:left-4 w-6 h-6 border-b-2 border-r-2 rtl:border-r-0 rtl:border-l-2 border-[#3DAFB9] transition-opacity duration-700"
                                     :style="{ opacity: activeDomain === i ? 0.5 : 0 }"></div>

                                <!-- Header row: big number + tag/icon -->
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex items-center gap-4">
                                        <span :class="[
                                            'text-6xl lg:text-7xl font-black transition-all duration-500 leading-none tracking-tight',
                                            activeDomain === i
                                                ? 'text-gradient-brand'
                                                : 'text-[#2D4B7E]/40 dark:text-[#6BC8D2]/45'
                                        ]">
                                            {{ String(i + 1).padStart(2, '0') }}
                                        </span>
                                        <!-- Active pulse dot -->
                                        <div v-if="activeDomain === i" class="relative w-2.5 h-2.5 mt-4">
                                            <span class="absolute inset-0 rounded-full bg-[#3DAFB9] animate-ping"></span>
                                            <span class="relative block w-full h-full rounded-full bg-[#3DAFB9]"></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] text-[#3DAFB9] tracking-[0.25em] font-bold uppercase">{{ d.tag }}</span>
                                        <div :class="[
                                            'w-12 h-12 rounded-2xl border flex items-center justify-center transition-all duration-500',
                                            activeDomain === i
                                                ? 'bg-gradient-to-br from-[#3DAFB9]/25 to-[#2D4B7E]/20 border-[#3DAFB9]/50 scale-110 rotate-3'
                                                : 'bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/8 border-[#3DAFB9]/20'
                                        ]">
                                            <img :src="iconUrl(d.iconSlug, isDark)" class="w-6 h-6" :alt="d.title" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Title -->
                                <h3 :class="[
                                    'text-2xl lg:text-3xl font-black mb-4 leading-tight transition-colors duration-500',
                                    activeDomain === i ? 'text-[#2D4B7E] dark:text-[#C2EBEF]' : 'text-ink'
                                ]">
                                    {{ d.title }}
                                </h3>

                                <!-- Description -->
                                <p class="text-ink-body leading-relaxed mb-6 max-w-xl">{{ d.desc }}</p>

                                <!-- Features grid -->
                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-8">
                                    <li v-for="(f, fi) in d.features" :key="fi"
                                        :class="[
                                            'flex items-start gap-2.5 text-sm p-2.5 rounded-xl transition-all duration-500',
                                            activeDomain === i
                                                ? 'bg-[#3DAFB9]/5 border border-[#3DAFB9]/15'
                                                : 'border border-transparent'
                                        ]">
                                        <span class="w-5 h-5 rounded-full bg-gradient-to-br from-[#3DAFB9]/20 to-[#2D4B7E]/15 border border-[#3DAFB9]/30 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-2.5 h-2.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                        <span class="text-ink-body leading-tight">{{ f }}</span>
                                    </li>
                                </ul>

                                <!-- CTA -->
                                <a href="/services" class="inline-flex items-center gap-2 text-sm font-bold text-[#2D4B7E] dark:text-[#6BC8D2] hover:text-[#3DAFB9] transition-colors group/link">
                                    <span>تعرّف على المزيد</span>
                                    <span class="w-8 h-8 rounded-full border border-[#3DAFB9]/30 flex items-center justify-center group-hover/link:bg-[#3DAFB9] group-hover/link:border-[#3DAFB9] group-hover/link:text-white transition-all">
                                        <svg class="w-3.5 h-3.5 rtl:rotate-180 group-hover/link:translate-x-0.5 rtl:group-hover/link:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </span>
                                </a>

                                <!-- MOBILE: show illustration under card (since sticky column is hidden on mobile) -->
                                <div class="lg:hidden mt-8 rounded-2xl border border-soft bg-elevated/60 p-6 aspect-square overflow-hidden relative">
                                    <div class="absolute inset-0 grid-pattern-sm opacity-40"></div>
                                    <div class="relative w-full h-full flex items-center justify-center">
                                        <ExpertiseIllust :variant="i" :is-dark="isDark" />
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>

                    <!-- ===== ILLUSTRATION COLUMN (SECOND in DOM = LEFT in RTL) ===== -->
                    <!-- Sticky container sits BELOW navbar; SVG centered within visible area -->
                    <div class="hidden lg:block lg:col-span-5">
                        <div class="sticky top-24 flex items-center justify-center overflow-hidden" style="height: calc(100vh - 7rem);">
                            <div class="relative w-full max-w-sm py-6">
                                <!-- ============ CONSULTING-THEMED FLOATING BACKGROUND SYMBOLS ============ -->
                                <div class="absolute inset-0 pointer-events-none overflow-hidden">
                                    <!-- Currency & percentage badges (very faint) -->
                                    <div class="absolute top-[6%] left-[8%] text-3xl font-black text-[#2D4B7E]/[0.07] dark:text-[#6BC8D2]/[0.10] floating-element" style="animation-duration: 12s;">$</div>
                                    <div class="absolute top-[18%] right-[12%] text-2xl font-black text-[#3DAFB9]/[0.08] floating-element" style="animation-duration: 10s; animation-delay: 2s;">%</div>
                                    <div class="absolute bottom-[22%] left-[5%] text-3xl font-black text-[#3DAFB9]/[0.07] floating-element" style="animation-duration: 14s; animation-delay: 1s;">€</div>
                                    <div class="absolute top-[40%] right-[3%] text-xl font-black text-[#2D4B7E]/[0.06] dark:text-[#6BC8D2]/[0.10] floating-element" style="animation-duration: 11s; animation-delay: 3s;">+18%</div>
                                    <div class="absolute bottom-[8%] right-[18%] text-2xl font-black text-[#3DAFB9]/[0.07] floating-element" style="animation-duration: 13s; animation-delay: 4s;">▲</div>

                                    <!-- Tiny SVG icons scattered -->
                                    <svg class="absolute top-[12%] right-[35%] w-6 h-6 opacity-[0.10] floating-element" style="animation-duration: 9s; animation-delay: 1s;" viewBox="0 0 24 24" fill="none" stroke="#3DAFB9" stroke-width="2">
                                        <path d="M3 17l6-6 4 4 8-8" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 7l7 0 0 7" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <svg class="absolute bottom-[35%] right-[8%] w-5 h-5 opacity-[0.10] floating-element" style="animation-duration: 11s;" viewBox="0 0 24 24" fill="none" stroke="#2D4B7E" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <circle cx="12" cy="12" r="4" fill="currentColor"/>
                                    </svg>
                                    <svg class="absolute top-[55%] left-[12%] w-6 h-6 opacity-[0.10] floating-element" style="animation-duration: 10s; animation-delay: 2s;" viewBox="0 0 24 24" fill="none" stroke="#3DAFB9" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <path d="M7 10l3 3 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <svg class="absolute top-[70%] left-[35%] w-4 h-4 opacity-[0.10] floating-element" style="animation-duration: 8s;" viewBox="0 0 24 24" fill="#3DAFB9">
                                        <path d="M12 2l3 6 6 1-4.5 4 1 6.5L12 17l-5.5 3 1-6.5-4.5-4 6-1z"/>
                                    </svg>

                                    <!-- Faint mini chart bar -->
                                    <svg class="absolute top-[25%] left-[18%] w-16 h-10 opacity-[0.09]" viewBox="0 0 60 40" fill="none">
                                        <rect x="4" y="24" width="8" height="14" fill="#3DAFB9" rx="1"/>
                                        <rect x="16" y="18" width="8" height="20" fill="#2D4B7E" rx="1"/>
                                        <rect x="28" y="10" width="8" height="28" fill="#3DAFB9" rx="1"/>
                                        <rect x="40" y="4" width="8" height="34" fill="#2D4B7E" rx="1"/>
                                    </svg>

                                    <!-- Faint dashed connection lines -->
                                    <svg class="absolute inset-0 w-full h-full opacity-[0.08]" viewBox="0 0 400 600" fill="none" preserveAspectRatio="none">
                                        <path d="M 40 100 Q 200 200, 350 150" stroke="#3DAFB9" stroke-width="1" stroke-dasharray="4 8" fill="none"/>
                                        <path d="M 30 400 Q 200 350, 380 480" stroke="#2D4B7E" stroke-width="1" stroke-dasharray="4 8" fill="none"/>
                                    </svg>

                                    <!-- Ambient dots -->
                                    <span v-for="n in 10" :key="n"
                                        class="absolute rounded-full bg-[#3DAFB9]"
                                        :style="{
                                            top: (Math.random() * 90 + 5) + '%',
                                            left: (Math.random() * 90 + 5) + '%',
                                            width: (2.5 + Math.random() * 3) + 'px',
                                            height: (2.5 + Math.random() * 3) + 'px',
                                            opacity: 0.10 + Math.random() * 0.08,
                                            animation: `floatParticle ${11 + Math.random() * 7}s ease-in-out ${Math.random() * 5}s infinite`,
                                        }"></span>
                                </div>

                                <!-- Radial glow behind illustration -->
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] rounded-full blur-[100px] transition-all duration-700 pointer-events-none"
                                     :style="{ background: `radial-gradient(circle, ${activeDomain % 2 === 0 ? 'rgba(61,175,185,0.12)' : 'rgba(45,75,126,0.12)'}, transparent 70%)` }"></div>

                                <!-- Giant number BEHIND illustration (subtle, positioned inside) -->
                                <div class="absolute top-4 right-4 rtl:right-auto rtl:left-4 pointer-events-none select-none z-0">
                                    <transition name="bignum" mode="out-in">
                                        <span :key="activeDomain" class="block text-[7rem] xl:text-[8rem] font-black leading-none text-[#2D4B7E]/[0.07] dark:text-[#6BC8D2]/[0.10]" style="filter: blur(0.3px);">
                                            {{ String(activeDomain + 1).padStart(2, '0') }}
                                        </span>
                                    </transition>
                                </div>

                                <!-- Illustration — smaller, cleaner -->
                                <div class="relative z-10 w-full flex items-center justify-center py-4">
                                    <transition name="illust" mode="out-in">
                                        <div :key="activeDomain" class="w-full max-w-[320px] flex items-center justify-center"
                                             style="filter: blur(0.3px); opacity: 0.92;">
                                            <ExpertiseIllust :variant="activeDomain" :is-dark="isDark" />
                                        </div>
                                    </transition>
                                </div>

                                <!-- Info + progress bar (below illustration, no card wrapper) -->
                                <div class="relative z-10 mt-6 px-2">
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="text-[11px] text-ink-muted tracking-[0.3em] uppercase font-bold">
                                            {{ String(activeDomain + 1).padStart(2, '0') }} / 06
                                        </div>
                                        <div class="h-px flex-1 bg-gradient-to-r from-[#3DAFB9]/40 to-transparent"></div>
                                        <transition name="fade-swap" mode="out-in">
                                            <span :key="activeDomain" class="text-[10px] text-[#3DAFB9] tracking-widest font-bold">{{ expertise[activeDomain]?.tag }}</span>
                                        </transition>
                                    </div>
                                    <transition name="fade-swap" mode="out-in">
                                        <div :key="activeDomain" class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-5 leading-tight">
                                            {{ expertise[activeDomain]?.title }}
                                        </div>
                                    </transition>
                                    <div class="flex gap-1.5">
                                        <button
                                            v-for="(d, i) in expertise"
                                            :key="i"
                                            @click="scrollToCard(i)"
                                            :aria-label="d.title"
                                            :class="[
                                                'h-1.5 rounded-full transition-all duration-500 cursor-pointer',
                                                activeDomain === i
                                                    ? 'w-12 bg-gradient-to-r from-[#3DAFB9] to-[#2D4B7E]'
                                                    : 'w-4 bg-[var(--border-soft)] hover:bg-[#3DAFB9]/50'
                                            ]"
                                        ></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <SectionBridge />

        <!-- ================================================================ -->
        <!-- METHODOLOGY — 6 Consulting Service Stages                          -->
        <!-- (Based on the old Rowaad site's مراحل تقديم الخدمات الاستشارية)   -->
        <!-- ================================================================ -->
        <section id="methodology" class="relative py-14 sm:py-20 lg:py-24 overflow-hidden">
            <!-- ===== Rich background ===== -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 right-[10%] w-[500px] h-[500px] rounded-full aurora-drift" style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>
                <div class="absolute bottom-0 left-[10%] w-[450px] h-[450px] rounded-full" style="background: radial-gradient(circle, rgba(45,75,126,0.08), transparent 70%);"></div>
                <div class="absolute inset-0 dot-pattern opacity-30"></div>

                <!-- Floating consulting symbols -->
                <div class="absolute top-[12%] left-[7%] text-2xl font-black text-[#3DAFB9]/[0.08] floating-element" style="animation-duration: 12s;">%</div>
                <div class="absolute top-[20%] right-[8%] text-3xl font-black text-[#2D4B7E]/[0.07] dark:text-[#6BC8D2]/[0.10] floating-element" style="animation-duration: 10s; animation-delay: 2s;">↗</div>
                <div class="absolute bottom-[15%] right-[12%] text-2xl font-black text-[#3DAFB9]/[0.08] floating-element" style="animation-duration: 14s; animation-delay: 1s;">◈</div>
                <div class="absolute bottom-[25%] left-[8%] text-xl font-black text-[#2D4B7E]/[0.07] dark:text-[#6BC8D2]/[0.10] floating-element" style="animation-duration: 11s;">+18%</div>

                <!-- Tiny decorative SVGs -->
                <svg class="absolute top-[30%] left-[15%] w-8 h-8 opacity-[0.08] floating-element" style="animation-duration: 9s; animation-delay: 2s;" viewBox="0 0 24 24" fill="none" stroke="#3DAFB9" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M8 12l3 3 5-6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg class="absolute bottom-[35%] right-[20%] w-8 h-8 opacity-[0.08] floating-element" style="animation-duration: 11s;" viewBox="0 0 24 24" fill="none" stroke="#2D4B7E" stroke-width="1.5">
                    <path d="M3 17l6-6 4 4 8-8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 7h7v7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <!-- Ambient dots -->
                <span v-for="n in 8" :key="n"
                    class="absolute rounded-full bg-[#3DAFB9]"
                    :style="{
                        top: (Math.random() * 90 + 5) + '%',
                        left: (Math.random() * 90 + 5) + '%',
                        width: (3 + Math.random() * 3) + 'px',
                        height: (3 + Math.random() * 3) + 'px',
                        opacity: 0.10 + Math.random() * 0.08,
                        animation: `floatParticle ${12 + Math.random() * 6}s ease-in-out ${Math.random() * 5}s infinite`,
                    }"></span>
            </div>

            <div class="relative max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">
                <!-- Header -->
                <div ref="journeyHeader" class="text-center max-w-3xl mx-auto mb-16 lg:mb-20 opacity-0">
                    <div class="flex items-center justify-center gap-3 mb-5">
                        <div class="h-px w-12 bg-gradient-to-r from-transparent to-[#3DAFB9]"></div>
                        <span class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">03 · منهجيّتنا</span>
                        <div class="h-px w-12 bg-gradient-to-l from-transparent to-[#3DAFB9]"></div>
                    </div>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.08] tracking-tight mb-5">
                        مراحل تقديم <span class="text-gradient-brand">الخدمات الاستشارية</span>
                    </h2>
                    <p class="text-base lg:text-lg text-ink-body leading-relaxed">
                        نتبع منهجية علمية متكاملة من 6 مراحل مترابطة، تضمن تقديم استشارات دقيقة وفعّالة تحقّق أهدافك بكفاءة وموثوقية.
                    </p>
                </div>

                <!-- ===== Steps Timeline ===== -->
                <div ref="journeySteps" class="opacity-0 relative">
                    <!-- Central animated connector line (desktop 2xl only) -->
                    <div class="hidden 2xl:block absolute top-[92px] left-16 right-16 h-px pointer-events-none z-0">
                        <div class="w-full h-full bg-gradient-to-r from-transparent via-[#3DAFB9]/30 to-transparent"></div>
                        <!-- Traveling dot along the line -->
                        <div class="absolute top-1/2 -translate-y-1/2 w-3 h-3 rounded-full bg-[#3DAFB9] shadow-[0_0_12px_rgba(61,175,185,0.6)]"
                             style="animation: methodDot 6s linear infinite;"></div>
                    </div>

                    <!-- Grid: 1 col on mobile, 2 on sm, 3 on lg, 6 on 2xl -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6 gap-5 lg:gap-6 relative z-10">
                        <div
                            v-for="(step, i) in methodology"
                            :key="i"
                            class="group relative flex flex-col items-center text-center p-6 lg:p-7 rounded-3xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover transition-all duration-500 hover:-translate-y-2 overflow-hidden"
                            :style="{ animationDelay: (i * 100) + 'ms' }"
                        >
                            <!-- Faint oversized icon watermark (consulting glyph, not a duplicate number) -->
                            <img
                                :src="iconUrl(step.iconSlug, isDark)"
                                alt=""
                                class="absolute -bottom-5 -left-5 rtl:-left-auto rtl:-right-5 w-28 h-28 opacity-[0.06] dark:opacity-[0.09] pointer-events-none select-none rotate-[-12deg] group-hover:opacity-[0.14] group-hover:rotate-[-6deg] group-hover:scale-110 transition-all duration-700"
                            />

                            <!-- Hexagonal icon container -->
                            <div class="relative mb-5 mt-2">
                                <!-- Hexagonal shape with gradient -->
                                <div class="relative w-20 h-20 flex items-center justify-center transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">
                                    <!-- SVG hexagon background -->
                                    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 100" fill="none">
                                        <defs>
                                            <linearGradient :id="`hex-grad-${i}`" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" stop-color="#3DAFB9" stop-opacity="0.15"/>
                                                <stop offset="100%" stop-color="#2D4B7E" stop-opacity="0.10"/>
                                            </linearGradient>
                                        </defs>
                                        <polygon points="50,5 90,27 90,73 50,95 10,73 10,27" :fill="`url(#hex-grad-${i})`" stroke="#3DAFB9" stroke-width="1.5" stroke-opacity="0.4"/>
                                    </svg>
                                    <!-- Icon on top -->
                                    <img :src="iconUrl(step.iconSlug, isDark)" class="relative w-9 h-9 lg:w-10 lg:h-10 z-10" :alt="step.title" />
                                </div>

                                <!-- Number badge floating on the hexagon -->
                                <div class="absolute -top-2 -right-2 rtl:-right-auto rtl:-left-2 w-7 h-7 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center text-[11px] font-black text-white shadow-lg shadow-[#3DAFB9]/30 group-hover:scale-110 transition-transform duration-500">
                                    {{ String(i + 1).padStart(2, '0') }}
                                </div>
                            </div>

                            <!-- Content -->
                            <h4 class="text-lg lg:text-xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-2 leading-tight group-hover:text-[#3DAFB9] dark:group-hover:text-[#6BC8D2] transition-colors">
                                {{ step.title }}
                            </h4>
                            <p class="text-sm text-ink-body leading-relaxed">{{ step.desc }}</p>

                            <!-- Bottom accent line (grows on hover) -->
                            <div class="mt-4 h-0.5 w-8 rounded-full bg-gradient-to-r from-[#3DAFB9] to-[#2D4B7E] group-hover:w-full transition-all duration-500"></div>

                            <!-- Arrow connector (desktop 2xl only, showing continuation) -->
                            <svg v-if="i < methodology.length - 1" class="hidden 2xl:block absolute top-[92px] left-full rtl:left-auto rtl:right-full w-6 h-6 -mt-3 text-[#3DAFB9]/40 z-20 pointer-events-none rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <SectionBridge />

        <!-- ================================================================ -->
        <!-- SECTORS — Interactive Featured Showcase (compact, fits viewport)   -->
        <!-- ================================================================ -->
        <section id="sectors" class="relative py-14 sm:py-20 lg:py-24 bg-canvas overflow-hidden">
            <!-- Cleaner background -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 left-[15%] w-[500px] h-[500px] rounded-full" style="background: radial-gradient(circle, rgba(61,175,185,0.08), transparent 70%);"></div>
                <div class="absolute bottom-0 right-[10%] w-[400px] h-[400px] rounded-full" style="background: radial-gradient(circle, rgba(45,75,126,0.06), transparent 70%);"></div>
                <div class="absolute inset-0 dot-pattern opacity-25"></div>

                <!-- Minimal floating dots -->
                <span v-for="n in 5" :key="n"
                    class="absolute rounded-full bg-[#3DAFB9]"
                    :style="{
                        top: (Math.random() * 90 + 5) + '%',
                        left: (Math.random() * 90 + 5) + '%',
                        width: (3 + Math.random() * 3) + 'px',
                        height: (3 + Math.random() * 3) + 'px',
                        opacity: 0.10 + Math.random() * 0.08,
                        animation: `floatParticle ${11 + Math.random() * 7}s ease-in-out ${Math.random() * 5}s infinite`,
                    }"></span>
            </div>

            <div class="relative max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <!-- Compact header (centered, smaller) -->
                <div ref="sectorsHeader" class="text-center max-w-2xl mx-auto mb-10 opacity-0">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="h-px w-10 bg-gradient-to-r from-transparent to-[#3DAFB9]"></div>
                        <span class="text-[10px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">04 · القطاعات</span>
                        <div class="h-px w-10 bg-gradient-to-l from-transparent to-[#3DAFB9]"></div>
                    </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.1] tracking-tight mb-3">
                        خبرة عابرة <span class="text-gradient-brand">للقطاعات</span>
                    </h2>
                    <p class="text-sm lg:text-base text-ink-body leading-relaxed">
                        نخدم نخبة من القطاعات الحيوية، نقدم لكل قطاع خبرة عميقة ومتخصصة.
                    </p>
                </div>

                <!-- ===== COMPACT INTERACTIVE SHOWCASE ===== -->
                <div ref="sectorsGrid" class="opacity-0">
                    <div class="grid grid-cols-12 gap-3 lg:gap-4">

                        <!-- =========== FEATURED CARD (col-span-7) — MEDIUM SIZE =========== -->
                        <div
                            class="col-span-12 lg:col-span-7 relative rounded-[1.75rem] overflow-hidden group"
                            style="min-height: 470px;"
                            @mouseenter="pauseSectorAutoplay = true"
                            @mouseleave="pauseSectorAutoplay = false"
                        >
                            <!-- Dark gradient background (always dark for premium feel) -->
                            <div class="absolute inset-0 bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50]"></div>

                            <!-- Animated aurora glow that shifts by sector -->
                            <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full blur-[100px] transition-all duration-1000"
                                 :style="{ background: `radial-gradient(circle, ${activeSector % 2 === 0 ? 'rgba(61,175,185,0.35)' : 'rgba(107,200,210,0.30)'}, transparent 70%)` }"></div>
                            <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] rounded-full blur-[100px] transition-all duration-1000"
                                 :style="{ background: `radial-gradient(circle, ${activeSector % 2 === 0 ? 'rgba(45,75,126,0.35)' : 'rgba(61,175,185,0.25)'}, transparent 70%)` }"></div>

                            <!-- Grid pattern overlay -->
                            <div class="absolute inset-0 opacity-[0.08]" style="background-image: linear-gradient(rgba(107,200,210,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(107,200,210,0.5) 1px, transparent 1px); background-size: 60px 60px;"></div>

                            <!-- Rotating gradient border (creative touch) -->
                            <div class="absolute inset-0 rounded-[2rem] p-[1.5px] pointer-events-none"
                                 style="background: conic-gradient(from 0deg, transparent 0deg, rgba(61,175,185,0.6) 90deg, transparent 180deg, rgba(45,75,126,0.6) 270deg, transparent 360deg); animation: conicSpin 8s linear infinite; mask: linear-gradient(#000, #000) content-box, linear-gradient(#000, #000); mask-composite: exclude; -webkit-mask: linear-gradient(#000, #000) content-box, linear-gradient(#000, #000); -webkit-mask-composite: xor;">
                                <div class="w-full h-full rounded-[2rem] bg-[#0A1729]"></div>
                            </div>

                            <!-- MEDIUM Content -->
                            <div class="relative h-full flex flex-col justify-between p-7 lg:p-9">
                                <!-- Top row -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] tracking-[0.3em] text-[#6BC8D2] uppercase font-bold">FEATURED</span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#3DAFB9] animate-pulse"></span>
                                    </div>
                                    <!-- Progress dots -->
                                    <div class="flex gap-1">
                                        <button
                                            v-for="(s, i) in sectorsData"
                                            :key="i"
                                            @click="setActiveSector(i)"
                                            :class="[
                                                'h-1 rounded-full transition-all duration-500',
                                                activeSector === i
                                                    ? 'w-7 bg-gradient-to-r from-[#3DAFB9] to-[#6BC8D2]'
                                                    : 'w-3 bg-white/15 hover:bg-white/30'
                                            ]"
                                        ></button>
                                    </div>
                                </div>

                                <!-- Center: content -->
                                <div class="flex-1 flex items-center justify-between gap-4 py-4">
                                    <!-- LEFT: Text -->
                                    <div class="flex-1 min-w-0">
                                        <transition name="sector-swap" mode="out-in">
                                            <div :key="activeSector" class="space-y-3">
                                                <!-- Number + tag row -->
                                                <div class="flex items-center gap-3">
                                                    <span class="text-5xl lg:text-6xl font-black leading-none text-transparent" style="-webkit-text-stroke: 1.5px rgba(107,200,210,0.35);">
                                                        {{ String(activeSector + 1).padStart(2, '0') }}
                                                    </span>
                                                    <div class="h-px flex-1 bg-gradient-to-r from-[#3DAFB9]/50 to-transparent max-w-[70px]"></div>
                                                    <span class="text-[10px] text-[#6BC8D2] tracking-[0.3em] uppercase font-bold">{{ sectorsData[activeSector]?.tag }}</span>
                                                </div>
                                                <!-- Title -->
                                                <h3 class="text-3xl lg:text-4xl font-black text-white leading-tight tracking-tight">
                                                    {{ sectorsData[activeSector]?.name }}
                                                </h3>
                                                <!-- Description -->
                                                <p class="text-[15px] text-white/70 leading-relaxed max-w-md">
                                                    {{ sectorsData[activeSector]?.desc }}
                                                </p>
                                                <!-- Stats -->
                                                <div class="flex items-center gap-5 pt-1.5">
                                                    <div>
                                                        <div class="text-2xl lg:text-3xl font-black text-[#6BC8D2] leading-none">{{ sectorsData[activeSector]?.stat1 }}</div>
                                                        <div class="text-[10px] text-white/50 tracking-widest uppercase mt-1.5">{{ sectorsData[activeSector]?.stat1Label }}</div>
                                                    </div>
                                                    <div class="w-px h-10 bg-white/15"></div>
                                                    <div>
                                                        <div class="text-2xl lg:text-3xl font-black text-[#6BC8D2] leading-none">{{ sectorsData[activeSector]?.stat2 }}</div>
                                                        <div class="text-[10px] text-white/50 tracking-widest uppercase mt-1.5">{{ sectorsData[activeSector]?.stat2Label }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>

                                    <!-- RIGHT: Icon showcase (smaller) -->
                                    <div class="hidden md:block shrink-0">
                                        <transition name="sector-icon" mode="out-in">
                                            <div :key="activeSector" class="relative">
                                                <!-- Glow behind -->
                                                <div class="absolute inset-0 bg-[#3DAFB9]/40 blur-2xl rounded-full"></div>
                                                <!-- Rotating dashed ring -->
                                                <svg class="absolute -inset-3 w-[calc(100%+1.5rem)] h-[calc(100%+1.5rem)]" style="animation: brandSpin 30s linear infinite;" viewBox="0 0 120 120" fill="none">
                                                    <circle cx="60" cy="60" r="58" stroke="rgba(107,200,210,0.4)" stroke-width="1" stroke-dasharray="3 8"/>
                                                </svg>
                                                <!-- Icon container (hexagonal) -->
                                                <div class="relative w-28 h-28 lg:w-32 lg:h-32 flex items-center justify-center">
                                                    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 100" fill="none">
                                                        <defs>
                                                            <linearGradient id="sec-hex" x1="0%" y1="0%" x2="100%" y2="100%">
                                                                <stop offset="0%" stop-color="#3DAFB9" stop-opacity="0.20"/>
                                                                <stop offset="100%" stop-color="#2D4B7E" stop-opacity="0.20"/>
                                                            </linearGradient>
                                                        </defs>
                                                        <polygon points="50,5 90,27 90,73 50,95 10,73 10,27" fill="url(#sec-hex)" stroke="#6BC8D2" stroke-width="1" stroke-opacity="0.5"/>
                                                    </svg>
                                                    <img :src="iconUrlWhite(sectorsData[activeSector]?.iconSlug)" class="relative w-14 h-14 lg:w-16 lg:h-16" :alt="sectorsData[activeSector]?.name" />
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>

                                <!-- Bottom: CTA -->
                                <div class="flex items-center justify-between">
                                    <a href="/sectors" class="group/link inline-flex items-center gap-2.5 text-[13px] font-bold text-white hover:text-[#6BC8D2] transition-colors">
                                        <span>استكشف القطاع</span>
                                        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center group-hover/link:scale-110 transition-transform shadow-md shadow-[#3DAFB9]/30">
                                            <svg class="w-4 h-4 rtl:rotate-180 text-white group-hover/link:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </span>
                                    </a>
                                    <div class="text-[10px] text-white/40 tracking-widest font-semibold">
                                        {{ String(activeSector + 1).padStart(2, '0') }} / {{ String(sectorsData.length).padStart(2, '0') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- =========== TILES GRID (col-span-5) — MEDIUM 2×4 =========== -->
                        <!-- Each tile ~108px tall × 4 rows + gap = matches featured 470px -->
                        <div class="col-span-12 lg:col-span-5 grid grid-cols-2 grid-rows-4 gap-3 lg:gap-3.5" style="max-height: 470px;">
                            <button
                                v-for="(sector, i) in sectorsData"
                                :key="sector.name"
                                @click="setActiveSector(i)"
                                @mouseenter="pauseSectorAutoplay = true"
                                @mouseleave="pauseSectorAutoplay = false"
                                :class="[
                                    'group/tile relative rounded-2xl border transition-all duration-500 flex items-center gap-3 px-4 py-3 overflow-hidden cursor-pointer text-right rtl:text-right',
                                    activeSector === i
                                        ? 'bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/12 border-[#3DAFB9]/50 shadow-md shadow-[#3DAFB9]/10'
                                        : 'bg-elevated border-soft hover:border-[#3DAFB9]/40 hover:shadow-sm'
                                ]"
                            >
                                <!-- Active glow -->
                                <div v-if="activeSector === i" class="absolute -top-4 -right-4 w-20 h-20 rounded-full bg-[#3DAFB9]/25 blur-xl"></div>

                                <!-- Icon container -->
                                <div :class="[
                                    'shrink-0 w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-500 relative z-10',
                                    activeSector === i
                                        ? 'bg-gradient-to-br from-[#3DAFB9]/25 to-[#2D4B7E]/20 scale-110'
                                        : 'bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/8 group-hover/tile:from-[#3DAFB9]/20 group-hover/tile:to-[#2D4B7E]/20'
                                ]">
                                    <img :src="iconUrl(sector.iconSlug, isDark)" class="w-6 h-6" :alt="sector.name" />
                                </div>

                                <!-- Text -->
                                <div class="flex-1 min-w-0 relative z-10">
                                    <div :class="[
                                        'text-[13.5px] font-bold leading-tight transition-colors duration-500 whitespace-nowrap',
                                        activeSector === i
                                            ? 'text-[#2D4B7E] dark:text-[#C2EBEF]'
                                            : 'text-ink group-hover/tile:text-[#2D4B7E] dark:group-hover/tile:text-[#6BC8D2]'
                                    ]">
                                        {{ sector.name }}
                                    </div>
                                    <div class="text-[9px] text-ink-muted mt-1 tracking-widest uppercase font-semibold">{{ sector.tag }}</div>
                                </div>

                                <!-- Number -->
                                <span :class="[
                                    'shrink-0 text-[11px] font-bold tracking-wider transition-colors duration-500 relative z-10',
                                    activeSector === i ? 'text-[#3DAFB9]' : 'text-ink-muted'
                                ]">{{ String(i + 1).padStart(2, '0') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <SectionBridge />

        <!-- ================================================================ -->
        <!-- TESTIMONIALS — Premium orbit-avatar carousel                       -->
        <!-- ================================================================ -->
        <section class="relative py-14 sm:py-20 lg:py-24 overflow-hidden">
            <!-- Ambient background -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] rounded-full" style="background: radial-gradient(ellipse, rgba(61,175,185,0.08), transparent 70%);"></div>
                <div class="absolute inset-0 dot-pattern opacity-20"></div>
            </div>

            <div class="relative max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-10">
                <!-- Header -->
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="h-px w-10 bg-gradient-to-r from-transparent to-[#3DAFB9]"></div>
                        <span class="text-[10px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">05 · شهادات عملائنا</span>
                        <div class="h-px w-10 bg-gradient-to-l from-transparent to-[#3DAFB9]"></div>
                    </div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-[1.1] tracking-tight">
                        ماذا يقول <span class="text-gradient-brand">شركاؤنا</span>؟
                    </h2>
                </div>

                <div ref="quoteSection" class="opacity-0">
                    <!-- ==========================================================
                         LIVE CONSULTATION SESSION — testimonials as a real chat
                         (out-of-the-box: no cards, no classic carousel)
                         ========================================================== -->
                    <div class="relative max-w-3xl mx-auto"
                         @mouseenter="pauseTestimonial = true"
                         @mouseleave="pauseTestimonial = false">

                        <!-- Soft aurora behind the window -->
                        <div class="absolute -inset-10 rounded-[3rem] pointer-events-none aurora-drift"
                             style="background: radial-gradient(ellipse 60% 55% at 30% 30%, rgba(61,175,185,0.14), transparent 70%), radial-gradient(ellipse 55% 55% at 75% 70%, rgba(45,75,126,0.12), transparent 70%);"></div>

                        <!-- Chat window -->
                        <div class="relative rounded-[1.5rem] bg-elevated border border-soft shadow-card-hover overflow-hidden">

                            <!-- Window header — consultation session bar -->
                            <div class="relative flex items-center justify-between px-5 py-3.5 bg-gradient-to-l rtl:bg-gradient-to-r from-[#0A1729] to-[#1A2F50]">
                                <div class="flex items-center gap-3">
                                    <div class="relative w-9 h-9 rounded-full bg-white/95 flex items-center justify-center p-1">
                                        <img src="/images/rowaad-logo-symbol.png" alt="رواد" class="w-full h-auto object-contain" />
                                        <span class="absolute -bottom-0.5 -left-0.5 w-3 h-3 rounded-full bg-green-400 border-2 border-[#0A1729]"></span>
                                    </div>
                                    <div>
                                        <div class="text-[13px] font-bold text-white leading-tight">جلسة استشارية · رواد بلا حدود</div>
                                        <div class="text-[10px] text-green-400 flex items-center gap-1 mt-0.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                            متصل الآن
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-white/15"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-white/15"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#3DAFB9]/60"></span>
                                </div>
                            </div>

                            <!-- Chat body -->
                            <div class="relative px-5 lg:px-8 py-7 min-h-[330px] bg-canvas">
                                <div class="absolute inset-0 dot-pattern opacity-30 pointer-events-none"></div>

                                <!-- Consultant question (static) -->
                                <div class="relative flex items-end gap-2.5 mb-5">
                                    <div class="w-8 h-8 rounded-full bg-white/95 border border-soft flex items-center justify-center p-1 shrink-0">
                                        <img src="/images/rowaad-logo-symbol.png" alt="" class="w-full h-auto object-contain" />
                                    </div>
                                    <div class="max-w-[75%] rounded-2xl rounded-br-md rtl:rounded-br-2xl rtl:rounded-bl-md bg-elevated border border-soft px-4 py-3 shadow-sm">
                                        <p class="text-[13.5px] text-ink-body leading-relaxed">كيف كانت تجربتكم مع رواد بلا حدود؟ 👋</p>
                                    </div>
                                </div>

                                <!-- Client response (re-animates on every switch via :key) -->
                                <div :key="`chat-${activeTestimonial}`" class="relative">
                                    <!-- Typing indicator: shows then hides -->
                                    <div class="chat-typing flex justify-end rtl:justify-end mb-1">
                                        <div class="inline-flex items-center gap-1 rounded-2xl bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] px-4 py-3">
                                            <span class="typing-dot w-1.5 h-1.5 rounded-full bg-white/90"></span>
                                            <span class="typing-dot w-1.5 h-1.5 rounded-full bg-white/90" style="animation-delay: .15s;"></span>
                                            <span class="typing-dot w-1.5 h-1.5 rounded-full bg-white/90" style="animation-delay: .3s;"></span>
                                        </div>
                                    </div>

                                    <!-- The testimonial bubble -->
                                    <div class="chat-bubble flex items-end justify-end gap-2.5">
                                        <div class="max-w-[85%] lg:max-w-[78%]">
                                            <div class="rounded-2xl rounded-bl-md rtl:rounded-bl-2xl rtl:rounded-br-md bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] px-5 py-4 shadow-lg shadow-[#3DAFB9]/15">
                                                <p class="text-[14px] lg:text-[15px] text-white leading-relaxed">{{ testimonials[activeTestimonial].quote }}</p>
                                                <div class="flex items-center gap-1 mt-3">
                                                    <svg v-for="s in 5" :key="s" class="w-3 h-3 text-[#C2EBEF]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                </div>
                                            </div>
                                            <!-- Sender identity -->
                                            <div class="flex items-center justify-end gap-2 mt-2 px-1">
                                                <span class="text-[11px] text-ink-muted">{{ testimonials[activeTestimonial].role }}</span>
                                                <span class="text-[12px] font-bold text-ink">{{ testimonials[activeTestimonial].name }}</span>
                                            </div>
                                        </div>
                                        <span class="flex w-8 h-8 rounded-full bg-gradient-to-br items-center justify-center text-white font-bold text-[11px] shadow-md shrink-0"
                                              :class="testimonials[activeTestimonial].avatarBg">{{ testimonials[activeTestimonial].initial }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom bar: fake input + client switcher -->
                            <div class="relative border-t border-soft bg-elevated px-4 py-3 flex items-center gap-3">
                                <!-- Client avatars = switcher with progress ring -->
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button v-for="(t, i) in testimonials" :key="i" @click="setActiveTestimonial(i)"
                                            class="relative w-8 h-8 transition-all duration-300"
                                            :class="activeTestimonial === i ? '' : 'opacity-45 hover:opacity-90 scale-90'">
                                        <svg v-if="activeTestimonial === i" class="absolute -inset-[3px] w-[calc(100%+6px)] h-[calc(100%+6px)] -rotate-90" viewBox="0 0 40 40">
                                            <circle cx="20" cy="20" r="18" fill="none" stroke="rgba(61,175,185,0.2)" stroke-width="2.5"/>
                                            <circle cx="20" cy="20" r="18" fill="none" stroke="#3DAFB9" stroke-width="2.5" stroke-linecap="round"
                                                    stroke-dasharray="113" stroke-dashoffset="113"
                                                    style="animation: testimonialRing 6s linear forwards;" :key="`ring-${activeTestimonial}`"/>
                                        </svg>
                                        <span class="flex w-8 h-8 rounded-full bg-gradient-to-br items-center justify-center text-white font-bold text-[10px]"
                                              :class="t.avatarBg">{{ t.initial }}</span>
                                    </button>
                                </div>
                                <!-- Decorative input -->
                                <div class="flex-1 flex items-center gap-2 rounded-full bg-canvas border border-soft px-4 py-2">
                                    <span class="text-[12px] text-ink-muted">شاركنا تجربتك مع رواد...</span>
                                </div>
                                <a href="/contact" class="w-9 h-9 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center shrink-0 hover:scale-110 transition-transform shadow-md shadow-[#3DAFB9]/25">
                                    <svg class="w-4 h-4 text-white rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Floating mini-stats around the window -->
                        <div class="hidden lg:flex absolute -left-24 top-10 flex-col items-center px-4 py-3 rounded-2xl bg-elevated border border-soft shadow-card animate-[float_6s_ease-in-out_infinite]">
                            <span class="text-xl font-black text-gradient-brand">98%</span>
                            <span class="text-[9px] text-ink-muted tracking-widest mt-0.5">رضا العملاء</span>
                        </div>
                        <div class="hidden lg:flex absolute -right-24 bottom-14 flex-col items-center px-4 py-3 rounded-2xl bg-elevated border border-soft shadow-card animate-[float_7s_ease-in-out_infinite_1s]">
                            <span class="text-xl font-black text-gradient-brand">+500</span>
                            <span class="text-[9px] text-ink-muted tracking-widest mt-0.5">مشروع ناجح</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================================================================ -->
        <!-- CTA — Interactive consultation launcher                            -->
        <!-- ================================================================ -->
        <section id="contact" class="relative py-14 sm:py-20 lg:py-24 bg-canvas overflow-hidden">
            <div class="absolute inset-0 dot-pattern opacity-40"></div>

            <div class="relative max-w-[1250px] mx-auto px-4 sm:px-6 lg:px-10">
                <div ref="ctaSection" class="opacity-0">
                    <!-- Premium dark panel -->
                    <div class="relative rounded-[2rem] bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50] overflow-hidden">
                        <!-- Aurora + grid -->
                        <div class="absolute -top-32 right-1/4 w-[500px] h-[500px] rounded-full bg-[#3DAFB9]/20 blur-[110px]"></div>
                        <div class="absolute -bottom-32 left-1/4 w-[450px] h-[450px] rounded-full bg-[#2D4B7E]/30 blur-[110px]"></div>
                        <div class="absolute inset-0 opacity-[0.06]" style="background-image: linear-gradient(rgba(107,200,210,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(107,200,210,0.5) 1px, transparent 1px); background-size: 56px 56px;"></div>

                        <!-- Floating consulting glyphs -->
                        <div class="absolute top-[12%] left-[6%] text-2xl font-black text-[#6BC8D2]/15 floating-element" style="animation-duration: 11s;">%</div>
                        <div class="absolute bottom-[18%] left-[14%] text-xl font-black text-[#6BC8D2]/12 floating-element" style="animation-duration: 13s; animation-delay: 2s;">▲</div>
                        <div class="absolute top-[22%] right-[10%] text-2xl font-black text-[#6BC8D2]/15 floating-element" style="animation-duration: 9s; animation-delay: 1s;">$</div>

                        <div class="relative grid grid-cols-12 gap-8 items-center p-8 lg:p-14">
                            <!-- ===== TEXT SIDE ===== -->
                            <div class="col-span-12 lg:col-span-7">
                                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/[0.06] border border-[#3DAFB9]/30 mb-6">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#3DAFB9] opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#3DAFB9]"></span>
                                    </span>
                                    <span class="text-[10px] text-[#6BC8D2] tracking-[0.25em] uppercase font-bold">مستشارونا متاحون الآن</span>
                                </div>

                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-[1.12] tracking-tight mb-5">
                                    استشارتك الأولى
                                    <span class="text-gradient-brand">مجانية</span><br>
                                    فلماذا الانتظار؟
                                </h2>
                                <p class="text-[15px] lg:text-base text-white/60 leading-relaxed max-w-lg mb-8">
                                    جلسة استكشافية مع مستشار متخصص، نفهم فيها تحدياتك ونرسم معاً أول خطوة نحو قرارك الاقتصادي الصحيح.
                                </p>

                                <!-- Trust chips -->
                                <div class="flex flex-wrap gap-2.5 mb-9">
                                    <span v-for="chip in ctaChips" :key="chip" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/[0.05] border border-white/10 text-[11px] text-white/75 font-semibold">
                                        <svg class="w-3 h-3 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        {{ chip }}
                                    </span>
                                </div>

                                <!-- Quick contact row -->
                                <div class="flex flex-wrap items-center gap-5 text-[12px] text-white/50">
                                    <a href="mailto:info@rowaad.org" class="inline-flex items-center gap-2 hover:text-[#6BC8D2] transition-colors">
                                        <svg class="w-3.5 h-3.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        info@rowaad.org
                                    </a>
                                    <span class="w-px h-4 bg-white/15"></span>
                                    <a href="tel:+966" dir="ltr" class="inline-flex items-center gap-2 hover:text-[#6BC8D2] transition-colors">
                                        <svg class="w-3.5 h-3.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        +966 5XX XXX XXX
                                    </a>
                                    <span class="w-px h-4 bg-white/15"></span>
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        الرياض، السعودية
                                    </span>
                                </div>
                            </div>

                            <!-- ===== ORBITAL CTA BUTTON SIDE ===== -->
                            <div class="col-span-12 lg:col-span-5 flex justify-center">
                                <div class="relative w-64 h-64 lg:w-72 lg:h-72 flex items-center justify-center">
                                    <!-- Orbiting mini icons -->
                                    <div class="orbit-inner-spin absolute inset-0">
                                        <div v-for="(g, i) in ctaOrbitIcons" :key="i"
                                             class="absolute top-1/2 left-1/2 w-9 h-9 -mt-[18px] -ml-[18px]"
                                             :style="{ transform: `rotate(${i * (360 / ctaOrbitIcons.length)}deg) translateY(-118px) rotate(-${i * (360 / ctaOrbitIcons.length)}deg)` }">
                                            <div class="orbit-inner-counter w-full h-full rounded-xl bg-white/[0.07] border border-[#6BC8D2]/25 backdrop-blur-sm flex items-center justify-center">
                                                <img :src="iconUrlWhite(g)" class="w-4.5 h-4.5" alt="" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Rings -->
                                    <div class="absolute inset-0 rounded-full border border-dashed border-[#6BC8D2]/20" style="animation: brandSpin 45s linear infinite;"></div>
                                    <div class="absolute inset-8 rounded-full border border-[#6BC8D2]/15"></div>
                                    <!-- Pulsing halo -->
                                    <div class="absolute inset-14 rounded-full bg-[#3DAFB9]/15 animate-ping" style="animation-duration: 3s;"></div>

                                    <!-- THE BUTTON -->
                                    <a href="/contact"
                                       class="group relative w-40 h-40 lg:w-44 lg:h-44 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex flex-col items-center justify-center text-center shadow-[0_20px_60px_-10px_rgba(61,175,185,0.5)] hover:shadow-[0_25px_80px_-10px_rgba(61,175,185,0.7)] hover:scale-105 transition-all duration-500 overflow-hidden">
                                        <!-- Shine sweep -->
                                        <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/25 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-out"></span>
                                        <svg class="relative w-7 h-7 text-white mb-2 rtl:rotate-180 group-hover:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        <span class="relative text-white font-black text-base lg:text-lg leading-tight">احجز استشارتك<br>المجانية</span>
                                        <span class="relative text-[10px] text-white/70 mt-1.5 tracking-wider">رد خلال 24 ساعة</span>
                                    </a>

                                    <!-- WhatsApp satellite button -->
                                    <a href="https://wa.me/" target="_blank"
                                       class="absolute bottom-3 right-3 rtl:right-auto rtl:left-3 w-12 h-12 rounded-full bg-[#0A1729] border border-green-400/40 flex items-center justify-center shadow-lg hover:scale-110 hover:border-green-400/80 transition-all duration-300 z-10">
                                        <svg class="w-5 h-5 text-green-400" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, reactive, h } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import MainLayout from '@/Layouts/MainLayout.vue';
import ExpertiseIllust from '@/Components/ExpertiseIllust.vue';
import SectionBridge from '@/Components/SectionBridge.vue';
import { useTheme } from '@/composables/useTheme';

const { isDark } = useTheme();

gsap.registerPlugin(ScrollTrigger);

// === Refs ===
const heroTop = ref(null);
const heroTitle = ref(null);
const heroSub = ref(null);
const heroCta = ref(null);
const heroTrust = ref(null);
const heroVisual = ref(null);
const dashedRing = ref(null);
const innerOrbit = ref(null);
const outerOrbit = ref(null);
const pulseDot1 = ref(null);
const pulseDot2 = ref(null);
const aboutHeader = ref(null);
const aboutContent = ref(null);
const servicesHeader = ref(null);
const serviceCards = reactive([]);
const journeyHeader = ref(null);
const journeySteps = ref(null);
const sectorsHeader = ref(null);
const sectorsGrid = ref(null);
const quoteSection = ref(null);
const ctaSection = ref(null);
const metricEls = reactive([]);

// === Icons (SVG functional components - 1.5 stroke premium look) ===
const stroke = { fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor', 'stroke-width': '1.5', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' };

const IconAnalytics = () => h('svg', stroke, [h('path', { d: 'M3 3v18h18M7 14l4-4 4 4 6-7' }), h('circle', { cx: '7', cy: '14', r: '1.5', fill: 'currentColor' }), h('circle', { cx: '11', cy: '10', r: '1.5', fill: 'currentColor' }), h('circle', { cx: '15', cy: '14', r: '1.5', fill: 'currentColor' }), h('circle', { cx: '21', cy: '7', r: '1.5', fill: 'currentColor' })]);
const IconDocument = () => h('svg', stroke, [h('path', { d: 'M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z' }), h('path', { d: 'M14 2v6h6M9 13h6M9 17h6' })]);
const IconTarget = () => h('svg', stroke, [h('circle', { cx: '12', cy: '12', r: '10' }), h('circle', { cx: '12', cy: '12', r: '6' }), h('circle', { cx: '12', cy: '12', r: '2', fill: 'currentColor' })]);
const IconBriefcase = () => h('svg', stroke, [h('rect', { x: '2', y: '7', width: '20', height: '14', rx: '2' }), h('path', { d: 'M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2' })]);
const IconCoin = () => h('svg', stroke, [h('circle', { cx: '12', cy: '12', r: '9' }), h('path', { d: 'M12 7v10M9 9h4a2 2 0 010 4h-2a2 2 0 000 4h4' })]);
const IconBolt = () => h('svg', stroke, [h('path', { d: 'M13 2L4 14h7l-1 8 9-12h-7l1-8z' })]);
const IconShield = () => h('svg', stroke, [h('path', { d: 'M12 2L4 5v7c0 5 3.5 9 8 10 4.5-1 8-5 8-10V5l-8-3z' }), h('path', { d: 'M9 12l2 2 4-4' })]);
const IconUsers = () => h('svg', stroke, [h('path', { d: 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2' }), h('circle', { cx: '9', cy: '7', r: '4' }), h('path', { d: 'M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75' })]);

// === Sector icons ===
const SecBuilding = () => h('svg', stroke, [h('path', { d: 'M3 21h18M5 21V7l8-4v18M19 21V11l-6-3M9 9h.01M9 13h.01M9 17h.01' })]);
const SecFactory = () => h('svg', stroke, [h('path', { d: 'M2 20V8l5 3V8l5 3V8l5 3v9H2zM6 14h2m4 0h2m4 0h2' })]);
const SecCart = () => h('svg', stroke, [h('path', { d: 'M3 3h2l2 12h12l2-8H7M9 21a2 2 0 100-4 2 2 0 000 4zM20 21a2 2 0 100-4 2 2 0 000 4z' })]);
const SecHealth = () => h('svg', stroke, [h('path', { d: 'M12 21s-7-4-9-10c-1-3 1-6 4-6 2 0 3 1 5 3 2-2 3-3 5-3 3 0 5 3 4 6-2 6-9 10-9 10z' })]);
const SecEdu = () => h('svg', stroke, [h('path', { d: 'M22 9L12 4 2 9l10 5 10-5zM6 11v6c0 1 3 3 6 3s6-2 6-3v-6' })]);
const SecTech = () => h('svg', stroke, [h('rect', { x: '2', y: '3', width: '20', height: '14', rx: '2' }), h('path', { d: 'M8 21h8M12 17v4' })]);
const SecAgri = () => h('svg', stroke, [h('path', { d: 'M12 22c4-4 4-10 0-14-4 4-4 10 0 14zM12 8c-4-4-10-4-10 0 4 0 6 4 10 0zM12 8c4-4 10-4 10 0-4 0-6 4-10 0z' })]);
const SecPlane = () => h('svg', stroke, [h('path', { d: 'M21 12l-9 3v6l-3-3-3 3v-6L3 12l5-3V3l4 3 4-3v6l5 3z' })]);

// === Data ===
const heroStats = [
    { value: '500', suffix: '+', label: 'مشروع ناجح' },
    { value: '15', suffix: '+', label: 'سنوات خبرة' },
    { value: '50', suffix: '+', label: 'مستشار خبير' },
    { value: '12', suffix: '+', label: 'دولة' },
];

// === EXPERTISE DOMAINS — Sticky-scroll section (athar-inspired) ===
const activeDomain = ref(0);
const expertiseSection = ref(null);

// === SECTORS — Interactive Featured Showcase ===
const activeSector = ref(0);
const pauseSectorAutoplay = ref(false);
let sectorAutoplayTimer = null;

const setActiveSector = (i) => {
    activeSector.value = i;
    // Reset autoplay
    if (sectorAutoplayTimer) clearInterval(sectorAutoplayTimer);
    startSectorAutoplay();
};

const startSectorAutoplay = () => {
    if (sectorAutoplayTimer) clearInterval(sectorAutoplayTimer);
    sectorAutoplayTimer = setInterval(() => {
        if (!pauseSectorAutoplay.value) {
            activeSector.value = (activeSector.value + 1) % 8;
        }
    }, 5000);
};

// White-tinted icon for the dark featured card
const iconUrlWhite = (slug) => {
    if (!slug) return '';
    return `https://api.iconify.design/solar:${slug}.svg?color=%236BC8D2&width=80&height=80`;
};

// === TESTIMONIALS — orbit-avatar carousel ===
const activeTestimonial = ref(0);
const pauseTestimonial = ref(false);
let testimonialTimer = null;

const testimonials = [
    {
        initial: 'م', name: 'محمد العتيبي', role: 'الرئيس التنفيذي · مجموعة الأعمال الذكية',
        avatarBg: 'from-[#3DAFB9] to-[#2D4B7E]',
        quote: 'رواد بلا حدود ليسوا مجرد مستشارين، بل شركاء حقيقيون في رحلة نموّ شركتنا. تحليلهم العميق ودراساتهم الدقيقة ساعدتنا على اتخاذ قرارات غيّرت مستقبل أعمالنا.',
    },
    {
        initial: 'س', name: 'سارة الحربي', role: 'مديرة الاستثمار · صندوق نماء',
        avatarBg: 'from-[#6BC8D2] to-[#3DAFB9]',
        quote: 'دراسة الجدوى التي أعدّوها لنا كانت بمستوى عالمي — أرقام دقيقة، سيناريوهات واقعية، وتوصيات واضحة. وفّرت علينا أشهراً من التردد.',
    },
    {
        initial: 'خ', name: 'خالد السالم', role: 'مؤسس · متاجر الخليج',
        avatarBg: 'from-[#2D4B7E] to-[#1A2F50]',
        quote: 'من أول جلسة استشارية شعرنا بالفرق. فريق يستمع أولاً ثم يقترح، وخطة التوسع التي رسموها لنا ضاعفت إيراداتنا خلال عام واحد.',
    },
    {
        initial: 'ن', name: 'نورة الزهراني', role: 'المدير المالي · شركة البنيان',
        avatarBg: 'from-[#4575AC] to-[#2D4B7E]',
        quote: 'إعادة الهيكلة المالية التي قادوها كانت نقطة تحوّل. احترافية عالية، سرية تامة، ونتائج ملموسة في الربع الأول.',
    },
];

const setActiveTestimonial = (i) => {
    activeTestimonial.value = i;
    startTestimonialAutoplay();
};
const startTestimonialAutoplay = () => {
    if (testimonialTimer) clearInterval(testimonialTimer);
    testimonialTimer = setInterval(() => {
        if (!pauseTestimonial.value) {
            activeTestimonial.value = (activeTestimonial.value + 1) % testimonials.length;
        }
    }, 6000);
};

// === CTA data ===
const ctaChips = ['استشارة أولى مجانية', 'رد خلال 24 ساعة', 'سرية تامة (NDA)', 'بدون أي التزام'];
const ctaOrbitIcons = ['chart-2-bold-duotone', 'documents-bold-duotone', 'target-bold-duotone', 'wallet-money-bold-duotone', 'cpu-bolt-bold-duotone', 'case-minimalistic-bold-duotone'];

const expertise = [
    {
        iconSlug: 'chart-2-bold-duotone',
        tag: 'ECONOMIC',
        title: 'الاستشارات الاقتصادية',
        desc: 'تحليل اقتصادي شامل وقراءات معمّقة للأسواق مدعومة بالبيانات لاتخاذ قرارات استثنائية تُحدث فرقاً استراتيجياً في نمو أعمالك.',
        features: ['تحليل الأسواق المتقدم', 'دراسات اقتصادية شاملة', 'استشراف الفرص والمخاطر', 'نماذج تنبؤية مبنية على البيانات'],
    },
    {
        iconSlug: 'documents-bold-duotone',
        tag: 'FEASIBILITY',
        title: 'دراسات الجدوى المتكاملة',
        desc: 'دراسات جدوى متكاملة (فنية، مالية، تسويقية، تشغيلية) تضمن اتخاذ قرارات استثمارية مدروسة وتقلل من المخاطر إلى أدنى حد.',
        features: ['الجدوى الفنية والتشغيلية', 'التحليل المالي والاستثماري', 'دراسة السوق والمنافسين', 'تقييم المخاطر والحساسية'],
    },
    {
        iconSlug: 'target-bold-duotone',
        tag: 'STRATEGY',
        title: 'التخطيط الاستراتيجي',
        desc: 'بناء خطط استراتيجية واضحة قابلة للقياس والتنفيذ، تُترجم الرؤية إلى خطوات عملية تحقق أهداف مؤسستك بعيدة المدى.',
        features: ['صياغة الرؤية والرسالة', 'خارطة طريق تفصيلية', 'مؤشرات الأداء الرئيسية', 'خطط التنفيذ والمتابعة'],
    },
    {
        iconSlug: 'rocket-2-bold-duotone',
        tag: 'GROWTH',
        title: 'تطوير الأعمال والنمو',
        desc: 'حلول مبتكرة لتوسيع نطاق أعمالك، دخول أسواق جديدة، وزيادة الكفاءة التشغيلية عبر منهجيات مثبتة عالمياً.',
        features: ['استراتيجيات النمو والتوسع', 'دخول أسواق جديدة', 'تحسين الكفاءة التشغيلية', 'إعادة هيكلة المؤسسات'],
    },
    {
        iconSlug: 'wallet-money-bold-duotone',
        tag: 'FINANCIAL',
        title: 'الاستشارات المالية',
        desc: 'إدارة مالية احترافية وتحليل عميق للمؤشرات المالية لتحسين أداء مؤسستك المالي وضمان استدامة النمو.',
        features: ['التحليل المالي المتقدم', 'إدارة رأس المال العامل', 'استراتيجيات التمويل', 'التخطيط المالي طويل المدى'],
    },
    {
        iconSlug: 'cpu-bolt-bold-duotone',
        tag: 'AI · NEW',
        title: 'حلول الذكاء الاصطناعي',
        desc: 'دمج تقنيات الذكاء الاصطناعي والتعلم الآلي في عملياتك الاستشارية لتحليل بيانات ضخمة واتخاذ قرارات ذكية بسرعة أعلى.',
        features: ['التحليل التنبؤي بالـ AI', 'أتمتة العمليات', 'نماذج تعلم آلي مخصصة', 'لوحات معلومات ذكية'],
    },
];

const scrollToCard = (i) => {
    if (!expertiseSection.value) return;
    const el = expertiseSection.value.querySelector(`[data-expertise-card="${i}"]`);
    if (!el) return;
    const y = el.getBoundingClientRect().top + window.scrollY - 140;
    window.scrollTo({ top: y, behavior: 'smooth' });
};

// Ambient floating dots — safe zones that avoid BOTH the circle (left) and the CTA area (right-bottom)
// Circle area: left 5-45%, top 25-75%
// CTA area: right 55-95%, top 60-95%  (where the buttons sit)
const dotZones = [
    // Top strip (full width, far from buttons)
    { topMin: 2,  topMax: 20, leftMin: 5,  leftMax: 95 },
    // Bottom-left strip (left of circle, away from CTAs)
    { topMin: 80, topMax: 95, leftMin: 2,  leftMax: 40 },
    // Right column upper (text/title area, above buttons)
    { topMin: 18, topMax: 55, leftMin: 58, leftMax: 95 },
];
const ambientDots = Array.from({ length: 11 }, () => {
    const zone = dotZones[Math.floor(Math.random() * dotZones.length)];
    return {
        top: zone.topMin + Math.random() * (zone.topMax - zone.topMin),
        left: zone.leftMin + Math.random() * (zone.leftMax - zone.leftMin),
        size: 3 + Math.random() * 4,
        opacity: 0.12 + Math.random() * 0.10,
        duration: 10 + Math.random() * 8,
        delay: Math.random() * 6,
    };
});

const avatarPalette = [
    'bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E]',
    'bg-gradient-to-br from-[#2D4B7E] to-[#1A2F50]',
    'bg-gradient-to-br from-[#6BC8D2] to-[#3DAFB9]',
    'bg-gradient-to-br from-[#4575AC] to-[#2D4B7E]',
];

// Hero highlight value props (3 quick wins for visitors)
const heroHighlights = [
    { icon: IconBolt, title: 'استجابة سريعة', desc: 'خلال 24 ساعة' },
    { icon: IconShield, title: 'سرية تامة', desc: 'اتفاقية NDA' },
    { icon: IconTarget, title: 'حلول مخصصة', desc: 'مصمّمة لأعمالك' },
];

// Orbital services use Solar icons (same set as the marquee below for identity consistency)
const orbitServices = [
    { iconSlug: 'chart-2-bold-duotone',          label: 'تحليل' },
    { iconSlug: 'documents-bold-duotone',        label: 'دراسات' },
    { iconSlug: 'target-bold-duotone',           label: 'استراتيجية' },
    { iconSlug: 'case-minimalistic-bold-duotone', label: 'استشارات' },
    { iconSlug: 'wallet-money-bold-duotone',     label: 'مالية' },
    { iconSlug: 'cpu-bolt-bold-duotone',         label: 'ذكاء اصطناعي' },
];

const orbitSectors = [
    { label: 'العقارات' }, { label: 'الصناعة' }, { label: 'التقنية' }, { label: 'الصحة' },
    { label: 'التعليم' }, { label: 'التجزئة' }, { label: 'السياحة' }, { label: 'الزراعة' },
];

const trustItems = [
    'استشارات اقتصادية', 'دراسات جدوى', 'تخطيط استراتيجي', 'تطوير أعمال',
    'استشارات مالية', 'ذكاء اصطناعي', 'تحليل أسواق', 'إعادة هيكلة',
];

// === SERVICES MARQUEE: Solar Icons (premium, distinctive, brand-colored) ===
// Solar set has modern bold icons - we color them with brand colors so they match identity
const iconUrl = (slug, dark = false) => {
    const color = dark ? '6BC8D2' : '2D4B7E';
    return `https://api.iconify.design/solar:${slug}.svg?color=%23${color}&width=64&height=64`;
};

const marqueeServices = [
    { iconSlug: 'chart-2-bold-duotone',          name: 'الاستشارات الاقتصادية' },
    { iconSlug: 'documents-bold-duotone',        name: 'دراسات الجدوى' },
    { iconSlug: 'target-bold-duotone',           name: 'التخطيط الاستراتيجي' },
    { iconSlug: 'rocket-2-bold-duotone',         name: 'تطوير الأعمال' },
    { iconSlug: 'wallet-money-bold-duotone',     name: 'الاستشارات المالية' },
    { iconSlug: 'cpu-bolt-bold-duotone',         name: 'الذكاء الاصطناعي' },
    { iconSlug: 'chart-square-bold-duotone',     name: 'تحليل الأسواق' },
    { iconSlug: 'case-minimalistic-bold-duotone', name: 'إعادة الهيكلة' },
];

const pillars = [
    {
        iconSlug: 'graph-up-bold-duotone',
        title: 'تحليل مبني على البيانات',
        desc: 'كل توصية مدعومة ببيانات دقيقة ونماذج تحليل متقدمة.',
        image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&auto=format&fit=crop&q=60',
    },
    {
        iconSlug: 'shield-check-bold-duotone',
        title: 'موثوقية وضمان',
        desc: 'سجل حافل بالنجاحات وعملاء يثقون بنا منذ 2010.',
        image: 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=800&auto=format&fit=crop&q=60',
    },
    {
        iconSlug: 'target-bold-duotone',
        title: 'دقة في التنفيذ',
        desc: 'حلول مخصصة لكل عميل تحقق أهدافه المحددة.',
        image: 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800&auto=format&fit=crop&q=60',
    },
    {
        iconSlug: 'users-group-two-rounded-bold-duotone',
        title: 'فريق متخصص',
        desc: 'نخبة من الخبراء المعتمدين في مختلف القطاعات.',
        image: 'https://images.unsplash.com/photo-1664575602554-2087b04935a5?w=800&auto=format&fit=crop&q=60',
    },
];

// === Legacy (kept for backwards compatibility) ===
const journey = [
    { title: 'الاكتشاف والتحليل', desc: 'فهم عميق لاحتياجاتك، أهدافك، والتحديات التي تواجهها.' },
    { title: 'التخطيط الاستراتيجي', desc: 'بناء خارطة طريق واضحة مع مؤشرات قياس دقيقة.' },
    { title: 'التنفيذ والتطبيق', desc: 'تطبيق الحلول بشكل عملي مع متابعة مستمرة لكل مرحلة.' },
    { title: 'القياس والتطوير', desc: 'تقييم النتائج وتحسين مستمر لضمان أقصى عائد.' },
];

// === 6 Consulting Service Stages (from old Rowaad methodology) ===
const methodology = [
    {
        iconSlug: 'magnifer-zoom-in-bold-duotone',
        title: 'التشخيص',
        desc: 'دراسة الوضع الحالي وتحليل المشكلات والفرص بعمق.',
    },
    {
        iconSlug: 'list-check-bold-duotone',
        title: 'تحديد الأولويات',
        desc: 'ترتيب المهام والأهداف بحسب أهميتها وتأثيرها.',
    },
    {
        iconSlug: 'map-bold-duotone',
        title: 'التخطيط',
        desc: 'صياغة خارطة طريق تفصيلية بمعايير قابلة للقياس.',
    },
    {
        iconSlug: 'play-stream-bold-duotone',
        title: 'التنفيذ والمتابعة',
        desc: 'تطبيق الحلول عملياً مع متابعة دقيقة لكل خطوة.',
    },
    {
        iconSlug: 'eye-scan-bold-duotone',
        title: 'الرقابة',
        desc: 'مراقبة الأداء وضمان الالتزام بالمعايير الموضوعة.',
    },
    {
        iconSlug: 'document-add-bold-duotone',
        title: 'التقارير',
        desc: 'إعداد تقارير شاملة توضح النتائج والتوصيات.',
    },
];

const sectors = [
    { icon: SecBuilding, name: 'العقارات', tag: 'REAL ESTATE' },
    { icon: SecFactory, name: 'الصناعة', tag: 'INDUSTRY' },
    { icon: SecCart, name: 'التجزئة', tag: 'RETAIL' },
    { icon: SecHealth, name: 'الصحة', tag: 'HEALTHCARE' },
    { icon: SecEdu, name: 'التعليم', tag: 'EDUCATION' },
    { icon: SecTech, name: 'التقنية', tag: 'TECHNOLOGY' },
    { icon: SecAgri, name: 'الزراعة', tag: 'AGRICULTURE' },
    { icon: SecPlane, name: 'السياحة', tag: 'TOURISM' },
];

// === Rich sector data for Interactive Featured Showcase ===
const sectorsData = [
    {
        iconSlug: 'buildings-2-bold-duotone',
        name: 'العقارات',
        tag: 'REAL ESTATE',
        desc: 'استشارات متخصصة في تطوير المشاريع العقارية وتقييم الفرص الاستثمارية في السوق السعودي والإقليمي.',
        stat1: '+50', stat1Label: 'مشروع', stat2: '+2.5B', stat2Label: 'قيمة إجمالية',
    },
    {
        iconSlug: 'city-bold-duotone',
        name: 'الصناعة',
        tag: 'INDUSTRY',
        desc: 'دراسات تحسين العمليات الإنتاجية وتطوير القطاعات الصناعية بمنهجيات عالمية.',
        stat1: '+35', stat1Label: 'مصنع', stat2: '+42%', stat2Label: 'تحسين كفاءة',
    },
    {
        iconSlug: 'cart-large-2-bold-duotone',
        name: 'التجزئة',
        tag: 'RETAIL',
        desc: 'استراتيجيات نمو التجزئة، تحسين تجربة العملاء، وتطوير قنوات البيع المتعددة.',
        stat1: '+80', stat1Label: 'علامة تجارية', stat2: '+65%', stat2Label: 'نمو مبيعات',
    },
    {
        iconSlug: 'health-bold-duotone',
        name: 'الصحة',
        tag: 'HEALTHCARE',
        desc: 'استشارات لتطوير المنشآت الصحية وتحسين جودة الخدمات الطبية والإدارية.',
        stat1: '+25', stat1Label: 'منشأة صحية', stat2: '98%', stat2Label: 'رضا المرضى',
    },
    {
        iconSlug: 'square-academic-cap-bold-duotone',
        name: 'التعليم',
        tag: 'EDUCATION',
        desc: 'حلول مبتكرة للمؤسسات التعليمية وتطوير المناهج والبرامج الأكاديمية.',
        stat1: '+40', stat1Label: 'مؤسسة', stat2: '+150K', stat2Label: 'طالب مستفيد',
    },
    {
        iconSlug: 'server-square-cloud-bold-duotone',
        name: 'التقنية',
        tag: 'TECHNOLOGY',
        desc: 'دعم شركات التقنية في التحول الرقمي واستراتيجيات النمو والتوسع في الأسواق.',
        stat1: '+60', stat1Label: 'شركة تقنية', stat2: '+3x', stat2Label: 'معدل النمو',
    },
    {
        iconSlug: 'leaf-bold-duotone',
        name: 'الزراعة',
        tag: 'AGRICULTURE',
        desc: 'تطوير القطاع الزراعي بحلول استدامة وتقنيات حديثة تعزز الأمن الغذائي.',
        stat1: '+20', stat1Label: 'مشروع زراعي', stat2: '+55%', stat2Label: 'إنتاجية',
    },
    {
        iconSlug: 'plain-bold-duotone',
        name: 'السياحة',
        tag: 'TOURISM',
        desc: 'تنمية القطاع السياحي وتطوير الوجهات ضمن رؤية 2030 السياحية الطموحة.',
        stat1: '+30', stat1Label: 'وجهة سياحية', stat2: '+2M', stat2Label: 'زائر سنوياً',
    },
];

onUnmounted(() => {
    if (sectorAutoplayTimer) clearInterval(sectorAutoplayTimer);
    if (testimonialTimer) clearInterval(testimonialTimer);
});

onMounted(() => {
    // Start autoplays
    startSectorAutoplay();
    startTestimonialAutoplay();

    // === Hero entrance timeline ===
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    tl.to(heroTop.value, { opacity: 1, y: 0, duration: 0.6, delay: 0.2 })
      .fromTo(heroTitle.value, { opacity: 0, y: 40 }, { opacity: 1, y: 0, duration: 1 }, '-=0.3')
      .fromTo(heroSub.value, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.7 }, '-=0.6')
      .fromTo(heroCta.value, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.7 }, '-=0.4')
      .fromTo(heroTrust.value, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.5 }, '-=0.3')
      .fromTo(heroVisual.value, { opacity: 0, scale: 0.92 }, { opacity: 1, scale: 1, duration: 1.2 }, '-=1.4');

    // === Orbital rotations - dashed ring only (orbits handled by CSS with counter-rotation) ===
    if (dashedRing.value) gsap.to(dashedRing.value, { rotation: 360, duration: 70, repeat: -1, ease: 'none', transformOrigin: 'center' });

    // Pulse dots traveling around — origins are pulled from the container's CSS vars
    // so the orbit path scales exactly with the visual on all breakpoints.
    if (pulseDot1.value) {
        const readOrigin = () => {
            const el = pulseDot1.value?.closest('.hero-orbit-container');
            const r = el ? parseFloat(getComputedStyle(el).getPropertyValue('--orbit-inner-r')) || 170 : 170;
            return `50% ${r}px`;
        };
        gsap.to(pulseDot1.value, { rotation: 360, duration: 8, repeat: -1, ease: 'none', transformOrigin: readOrigin() });
    }
    if (pulseDot2.value) {
        const readOrigin = () => {
            const el = pulseDot2.value?.closest('.hero-orbit-container');
            const r = el ? parseFloat(getComputedStyle(el).getPropertyValue('--orbit-outer-r')) || 275 : 275;
            return `50% ${r}px`;
        };
        gsap.to(pulseDot2.value, { rotation: -360, duration: 14, repeat: -1, ease: 'none', transformOrigin: readOrigin() });
    }

    // === Scroll-triggered reveals ===
    const reveal = (trigger, els, opts = {}) => {
        if (!trigger || !trigger.value) return;
        ScrollTrigger.create({
            trigger: trigger.value, start: 'top 80%', once: true,
            onEnter: () => {
                const items = Array.isArray(els) ? els : [els];
                items.forEach((el, i) => {
                    if (!el || !(el.value || el)) return;
                    gsap.fromTo(el.value || el, { opacity: 0, y: 40 }, { opacity: 1, y: 0, duration: 0.8, delay: i * 0.1, ease: 'power3.out' });
                });
            },
        });
    };

    reveal(aboutHeader, [aboutHeader, aboutContent]);

    // === Expertise sticky-scroll ===
    // Wider band + best-match algorithm ensures ALL cards (including #6) activate reliably
    if (expertiseSection.value) {
        const cards = expertiseSection.value.querySelectorAll('[data-expertise-card]');

        // Track current state of all cards
        const cardStates = new Map();

        const observer = new IntersectionObserver(
            (entries) => {
                // Update state for all changed entries
                entries.forEach((e) => {
                    const idx = parseInt(e.target.dataset.expertiseCard);
                    cardStates.set(idx, {
                        ratio: e.intersectionRatio,
                        isIntersecting: e.isIntersecting,
                    });
                });

                // Find the card with the highest visibility ratio (best match)
                let bestIdx = null;
                let bestRatio = 0;
                cardStates.forEach((state, idx) => {
                    if (state.isIntersecting && state.ratio > bestRatio) {
                        bestRatio = state.ratio;
                        bestIdx = idx;
                    }
                });

                if (bestIdx !== null) activeDomain.value = bestIdx;
            },
            {
                // Band centered around viewport middle (matches vertically-centered SVG position)
                // -45% top / -45% bottom = 10% tall band at exact center
                rootMargin: '-45% 0px -45% 0px',
                threshold: [0, 0.25, 0.5, 0.75, 1.0],
            }
        );
        cards.forEach((c) => observer.observe(c));
    }
    reveal(servicesHeader, [servicesHeader]);
    ScrollTrigger.create({
        trigger: servicesHeader.value, start: 'top 75%', once: true,
        onEnter: () => {
            serviceCards.forEach((card, i) => {
                if (!card) return;
                gsap.fromTo(card, { opacity: 0, y: 50, scale: 0.97 }, { opacity: 1, y: 0, scale: 1, duration: 0.7, delay: i * 0.08, ease: 'power3.out' });
            });
        },
    });
    reveal(journeyHeader, [journeyHeader, journeySteps]);
    reveal(sectorsHeader, [sectorsHeader, sectorsGrid]);
    reveal(quoteSection, [quoteSection]);
    reveal(ctaSection, [ctaSection]);
});
</script>
