<template>
    <MainLayout>
        <!-- ============ HERO ============ -->
        <section class="relative pt-32 lg:pt-40 pb-14 overflow-hidden bg-paper">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[10%] w-[520px] h-[520px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.13), transparent 70%);"></div>
                <div class="absolute bottom-0 left-[5%] w-[420px] h-[420px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.10), transparent 70%); animation-delay: 3s;"></div>
            </div>

            <div class="relative max-w-[1200px] mx-auto px-6 lg:px-10">
                <!-- Back link -->
                <Link href="/consultants" class="inline-flex items-center gap-2 text-[12px] text-ink-body hover:text-[#3DAFB9] font-bold mb-6 transition-colors">
                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                    كل المستشارين
                </Link>

                <div class="grid grid-cols-12 gap-8 items-start">
                    <!-- Avatar -->
                    <div class="col-span-12 md:col-span-4">
                        <div class="relative max-w-[300px] mx-auto md:mx-0">
                            <svg class="absolute -top-4 -right-4 w-32 h-32 opacity-60" style="animation: brandSpin 30s linear infinite;" viewBox="0 0 120 120" fill="none">
                                <circle cx="60" cy="60" r="57" stroke="#3DAFB9" stroke-width="1" stroke-dasharray="4 9" opacity="0.5"/>
                            </svg>
                            <div class="relative aspect-square rounded-[2rem] overflow-hidden shadow-card-hover border-4 border-elevated">
                                <img :src="consultant.avatar" :alt="consultant.name" class="w-full h-full object-cover" />
                            </div>
                            <div v-if="consultant.is_featured"
                                 class="absolute -bottom-3 right-4 rtl:right-auto rtl:left-4 inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white text-[11px] font-black shadow-md">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                مستشار مميّز
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="col-span-12 md:col-span-8">
                        <div v-if="consultant.specialization"
                             class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#3DAFB9]/10 border border-[#3DAFB9]/25 mb-4">
                            <img :src="specIcon(consultant.specialization.icon)" class="w-4 h-4" alt="" />
                            <span class="text-[11px] font-bold text-[#3DAFB9] tracking-wider">{{ consultant.specialization.name_ar }}</span>
                        </div>

                        <h1 class="text-3xl lg:text-[2.4rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-tight mb-1">{{ consultant.name }}</h1>
                        <div class="text-base text-ink-body font-bold mb-5">{{ consultant.title }}</div>

                        <!-- Stats -->
                        <div class="flex flex-wrap items-center gap-5 mb-6">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#F59E0B]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span class="text-[15px] font-black text-ink">{{ Number(consultant.rating_avg).toFixed(1) }}</span>
                                <span class="text-[11px] text-ink-muted">({{ consultant.rating_count }} تقييم)</span>
                            </div>
                            <div class="w-px h-4 bg-[var(--border-soft)]"></div>
                            <div class="text-[12.5px] text-ink-body">
                                <span class="font-black text-ink">{{ consultant.sessions_completed }}</span> جلسة مكتملة
                            </div>
                            <div class="w-px h-4 bg-[var(--border-soft)]"></div>
                            <div class="text-[12.5px] text-ink-body">
                                <span class="font-black text-ink">{{ consultant.years_experience }}+</span> سنوات خبرة
                            </div>
                            <div class="w-px h-4 bg-[var(--border-soft)]"></div>
                            <div class="text-[12.5px] text-ink-body inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ consultant.city }}
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="flex flex-wrap items-center gap-3">
                            <Link
                                :href="`/bookings/create/${consultant.id}`"
                                class="group inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white font-bold text-[14px] shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.03] transition-transform"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                                احجز استشارة
                            </Link>
                            <div class="px-5 py-3 rounded-full bg-elevated border border-soft">
                                <span class="text-[10px] text-ink-muted tracking-wider">سعر الجلسة</span>
                                <span class="mx-2 font-black text-ink text-lg">{{ formatPrice(consultant.hourly_rate) }}</span>
                                <span class="text-[11px] text-ink-body">ر.س</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ BODY ============ -->
        <section class="relative py-10 lg:py-14">
            <div class="max-w-[1200px] mx-auto px-6 lg:px-10">
                <div class="grid grid-cols-12 gap-8">

                    <!-- LEFT (Bio + Services) -->
                    <div class="col-span-12 lg:col-span-8 space-y-8">
                        <!-- About -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">نبذة</h2>
                            </div>
                            <p class="text-[14.5px] text-ink-body leading-[1.9] whitespace-pre-line">{{ consultant.bio }}</p>
                        </div>

                        <!-- Long bio (from rich_content) -->
                        <div v-if="rich.long_bio" class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">السيرة الموسّعة</h2>
                            </div>
                            <p class="text-[14px] text-ink-body leading-[2] whitespace-pre-line">{{ rich.long_bio }}</p>
                        </div>

                        <!-- Expertise areas -->
                        <div v-if="expertise.length" class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">مجالات الخبرة</h2>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span v-for="(e, i) in expertise" :key="i"
                                      class="px-3.5 py-1.5 rounded-full bg-gradient-to-br from-[#3DAFB9]/10 to-[#2D4B7E]/8 border border-[#3DAFB9]/25 text-[12.5px] font-bold text-[#2D4B7E] dark:text-[#6BC8D2]">
                                    {{ e }}
                                </span>
                            </div>
                        </div>

                        <!-- Services offered (rich) or legacy services -->
                        <div v-if="rich.services_offered?.length || consultant.services?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">الخدمات المقدَّمة</h2>
                            </div>
                            <div v-if="rich.services_offered?.length" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div v-for="(s, i) in rich.services_offered" :key="i"
                                     class="p-4 rounded-2xl border border-soft hover:border-[#3DAFB9]/40 transition-colors">
                                    <div class="flex items-start gap-2 mb-1.5">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/10 border border-[#3DAFB9]/20 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-[#3DAFB9]" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        </div>
                                        <h4 class="text-[13.5px] font-black text-ink leading-tight">{{ s.title }}</h4>
                                    </div>
                                    <p v-if="s.desc" class="text-[12.5px] text-ink-body leading-relaxed pl-10">{{ s.desc }}</p>
                                </div>
                            </div>
                            <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div v-for="(s, i) in consultant.services" :key="i"
                                     class="flex items-start gap-3 p-4 rounded-2xl border border-soft">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#3DAFB9]/12 to-[#2D4B7E]/10 border border-[#3DAFB9]/20 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-[14px] font-black text-ink">{{ s.title }}</div>
                                        <div v-if="s.duration_min" class="text-[11px] text-ink-muted mt-0.5">{{ s.duration_min }} دقيقة</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Experience timeline -->
                        <div v-if="rich.experience?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">الخبرات المهنية</h2>
                            </div>
                            <div class="relative pr-6 rtl:pr-0 rtl:pl-6">
                                <div class="absolute right-2 rtl:right-auto rtl:left-2 top-2 bottom-2 w-0.5 bg-gradient-to-b from-[#3DAFB9] via-[#2D4B7E] to-[#3DAFB9]/30"></div>
                                <div v-for="(x, i) in rich.experience" :key="i" class="relative flex items-start gap-4 pb-5 last:pb-0">
                                    <div class="shrink-0 -mr-6 rtl:mr-0 rtl:-ml-6 w-4 h-4 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] ring-4 ring-elevated mt-1"></div>
                                    <div class="flex-1">
                                        <div class="flex items-baseline justify-between gap-3 flex-wrap mb-1">
                                            <h4 class="text-[13.5px] font-black text-ink">{{ x.role }}</h4>
                                            <span class="text-[10.5px] text-ink-muted font-bold whitespace-nowrap">{{ x.period }}</span>
                                        </div>
                                        <div class="text-[12.5px] text-[#3DAFB9] font-bold mb-1">{{ x.company }}</div>
                                        <p v-if="x.desc" class="text-[12.5px] text-ink-body leading-relaxed">{{ x.desc }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Education + Certifications side by side -->
                        <div v-if="rich.education?.length || rich.certifications?.length" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div v-if="rich.education?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-[#3DAFB9]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/></svg>
                                    <h3 class="text-[14.5px] font-black text-ink">المؤهلات العلمية</h3>
                                </div>
                                <ul class="space-y-3">
                                    <li v-for="(ed, i) in rich.education" :key="i" class="p-3 rounded-xl bg-canvas border border-soft">
                                        <div class="text-[13px] font-black text-ink">{{ ed.degree }}</div>
                                        <div class="text-[12px] text-ink-body mt-0.5">{{ ed.institution }}<span v-if="ed.year"> · {{ ed.year }}</span></div>
                                    </li>
                                </ul>
                            </div>
                            <div v-if="rich.certifications?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-[#F59E0B]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 15.39l-3.76 2.27.99-4.28-3.32-2.88 4.38-.38L12 6.09l1.71 4.03 4.38.38-3.32 2.88.99 4.28M20 6a2 2 0 00-2-2h-3.82A2.99 2.99 0 0012 2a2.99 2.99 0 00-2.82 2H5.5A1.5 1.5 0 004 5.5V19a2 2 0 002 2h12a2 2 0 002-2V6z"/></svg>
                                    <h3 class="text-[14.5px] font-black text-ink">الشهادات المهنية</h3>
                                </div>
                                <ul class="space-y-3">
                                    <li v-for="(c, i) in rich.certifications" :key="i" class="p-3 rounded-xl bg-canvas border border-soft">
                                        <div class="text-[13px] font-black text-ink">{{ c.name }}</div>
                                        <div v-if="c.issuer" class="text-[12px] text-ink-body mt-0.5">{{ c.issuer }}<span v-if="c.year"> · {{ c.year }}</span></div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Achievements -->
                        <div v-if="achievements.length" class="rounded-[1.5rem] bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50] p-7 lg:p-8 relative overflow-hidden">
                            <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full aurora-drift"
                                 style="background: radial-gradient(circle, rgba(61,175,185,0.20), transparent 70%);"></div>
                            <div class="relative">
                                <div class="text-[10.5px] text-[#6BC8D2] tracking-widest font-black uppercase mb-2">إنجازات مميّزة</div>
                                <h2 class="text-xl font-black text-white mb-5">ما فخور به</h2>
                                <ul class="space-y-3">
                                    <li v-for="(a, i) in achievements" :key="i" class="flex items-start gap-3 text-white/90 text-[13.5px]">
                                        <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        <span class="leading-relaxed">{{ a }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT (Info card + Related) -->
                    <div class="col-span-12 lg:col-span-4 space-y-6">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 sticky top-28">
                            <h3 class="text-[13px] font-black text-ink-muted tracking-widest uppercase mb-4">معلومات المستشار</h3>
                            <dl class="space-y-3 text-[13px]">
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">مدة الجلسة</dt>
                                    <dd class="font-bold text-ink">{{ consultant.session_duration_min }} دقيقة</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">اللغات</dt>
                                    <dd class="font-bold text-ink">{{ (consultant.languages||[]).map(langLabel).join(' · ') || '—' }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">المدينة</dt>
                                    <dd class="font-bold text-ink">{{ consultant.city }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">الجنسية</dt>
                                    <dd class="font-bold text-ink">{{ consultant.nationality || '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Related consultants -->
                <div v-if="related?.length" class="mt-14">
                    <h2 class="text-2xl font-black text-ink mb-5">مستشارون في نفس التخصص</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Link
                            v-for="r in related" :key="r.id"
                            :href="`/consultants/${r.id}`"
                            class="group flex items-center gap-4 p-4 rounded-2xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card transition-all"
                        >
                            <img :src="r.avatar" :alt="r.name" class="w-14 h-14 rounded-full object-cover shrink-0" />
                            <div class="min-w-0 flex-1">
                                <div class="text-[14px] font-black text-ink group-hover:text-[#3DAFB9] transition-colors truncate">{{ r.name }}</div>
                                <div class="text-[11.5px] text-ink-body truncate mt-0.5">{{ r.title }}</div>
                                <div class="flex items-center gap-1 mt-1.5">
                                    <svg class="w-3 h-3 text-[#F59E0B]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <span class="text-[11px] font-bold text-ink-body">{{ Number(r.rating_avg).toFixed(1) }}</span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ BOOKING TEASER MODAL ============ -->
        <div v-if="openBooking" @click.self="openBooking = false"
             class="fixed inset-0 z-[80] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="relative w-full max-w-lg rounded-[1.5rem] bg-elevated border border-soft p-8 shadow-2xl">
                <button @click="openBooking = false" class="absolute top-4 left-4 rtl:left-4 rtl:right-auto w-8 h-8 rounded-full bg-canvas hover:bg-[#3DAFB9]/10 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-ink-body" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/10 border border-[#3DAFB9]/25 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-ink mb-2">حجز استشارة قريباً</h3>
                    <p class="text-[13.5px] text-ink-body leading-relaxed mb-5">
                        نظام الحجز يتم إعداده حالياً. سيتضمن اختيار الموعد، تفاصيل الاستشارة، والدفع الآمن.
                    </p>
                    <button @click="openBooking = false"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-105 transition-transform">
                        حسناً
                    </button>
                </div>
            </div>
        </div>

        <!-- ============ RATING WIDGET ============ -->
        <section class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10 pb-16">
            <ConsultantRating
                :consultant-id="consultant.id"
                :consultant-name="consultant.name"
                :initial-avg="Number(consultant.rating_avg) || 0"
                :initial-count="consultant.rating_count || 0"
            />
        </section>
    </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import ConsultantRating from '@/Components/ConsultantRating.vue';
import { useTheme } from '@/composables/useTheme';

const props = defineProps({
    consultant: Object,
    related:    Array,
});

// Rich content — hide sections that are empty
const normalize = (arr) => Array.isArray(arr)
    ? arr.map(x => (typeof x === 'string' ? x : (x?.item ?? ''))).filter(Boolean)
    : [];
const rich = computed(() => props.consultant.rich_content || {});
const expertise = computed(() => normalize(rich.value.expertise));
const achievements = computed(() => normalize(rich.value.achievements));

const { isDark } = useTheme();
const openBooking = ref(false);

const specIcon = (slug) => {
    const color = isDark.value ? '6BC8D2' : '2D4B7E';
    return `https://api.iconify.design/solar:${slug || 'user-bold-duotone'}.svg?color=%23${color}&width=48`;
};
const formatPrice = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));
const langLabel = (l) => ({ ar: 'العربية', en: 'English' }[l] || l);
</script>
