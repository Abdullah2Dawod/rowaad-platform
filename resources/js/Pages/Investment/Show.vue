<template>
    <MainLayout>
        <!-- ═══════════ HERO ═══════════ -->
        <section class="relative overflow-hidden bg-paper">
            <!-- Cover -->
            <div class="relative h-[42vh] lg:h-[52vh] min-h-[380px] overflow-hidden">
                <img v-if="opportunity.cover_image" :src="opportunity.cover_image" :alt="opportunity.title"
                     class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-[#0A1729] via-[#0A1729]/60 to-[#0A1729]/25"></div>
                <div class="absolute inset-0" style="background: radial-gradient(ellipse at bottom, rgba(61,175,185,0.15), transparent 60%);"></div>
            </div>

            <!-- Overlay content -->
            <div class="absolute inset-0 flex items-end pb-8 lg:pb-14">
                <div class="max-w-[1300px] mx-auto px-6 lg:px-10 w-full">
                    <Link href="/investments" class="inline-flex items-center gap-2 text-[12px] text-white/70 hover:text-white font-bold mb-5 transition-colors">
                        <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                        كل الفرص الاستثمارية
                    </Link>

                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/95 text-[#2D4B7E] text-[11px] font-black">
                            {{ opportunity.sector }}
                        </span>
                        <span v-if="opportunity.city" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/15 backdrop-blur border border-white/25 text-white text-[11px] font-bold">
                            📍 {{ opportunity.city }}
                        </span>
                        <span v-if="opportunity.source === 'gov_api'"
                              class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/15 backdrop-blur border border-white/25 text-white text-[11px] font-bold">
                            🏛️ {{ opportunity.source_name }}
                        </span>
                        <span :class="['inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-black',
                                       riskClass(opportunity.risk_level)]">
                            مخاطر: {{ riskLabel(opportunity.risk_level) }}
                        </span>
                    </div>

                    <h1 class="text-3xl lg:text-[2.8rem] font-black text-white leading-[1.15] mb-2">{{ opportunity.title }}</h1>
                    <p v-if="opportunity.subtitle" class="text-[15px] lg:text-lg text-white/80 max-w-2xl">{{ opportunity.subtitle }}</p>
                </div>
            </div>
        </section>

        <!-- ═══════════ BODY ═══════════ -->
        <section class="relative py-10 lg:py-14 bg-paper">
            <div class="max-w-[1300px] mx-auto px-6 lg:px-10">
                <div class="grid grid-cols-12 gap-8">
                    <!-- Left: content -->
                    <div class="col-span-12 lg:col-span-8 space-y-8">
                        <!-- Highlights -->
                        <div v-if="opportunity.highlights?.length" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div v-for="(h, i) in opportunity.highlights" :key="i"
                                 class="p-4 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5 border border-[#3DAFB9]/20">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/10 border border-[#3DAFB9]/25 flex items-center justify-center mb-2">
                                    <img :src="icon(h.icon)" class="w-4.5 h-4.5" alt="" />
                                </div>
                                <div class="text-[10.5px] text-ink-muted tracking-wider">{{ h.title }}</div>
                                <div class="text-[14px] font-black text-ink mt-0.5">{{ h.value }}</div>
                            </div>
                        </div>

                        <!-- Overview -->
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8 shadow-card">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">وصف الفرصة</h2>
                            </div>
                            <div class="text-[14.5px] text-ink-body leading-[1.95] whitespace-pre-line prose prose-sm max-w-none" v-html="markdown(opportunity.description)"></div>
                        </div>

                        <!-- Financial summary -->
                        <div class="rounded-[1.5rem] bg-gradient-to-br from-[#0A1729] via-[#122440] to-[#1A2F50] p-7 lg:p-8 shadow-card-hover relative overflow-hidden">
                            <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full aurora-drift"
                                 style="background: radial-gradient(circle, rgba(61,175,185,0.25), transparent 70%);"></div>
                            <div class="relative">
                                <div class="text-[10.5px] text-[#6BC8D2] tracking-widest font-black uppercase mb-4">الملخّص المالي</div>
                                <h3 class="text-2xl font-black text-white mb-6">أرقام الفرصة</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                                    <div>
                                        <div class="text-[10px] text-white/50 tracking-wider mb-1">قيمة الاستثمار</div>
                                        <div class="text-lg font-black text-white">{{ formatMoney(opportunity.investment_min) }}</div>
                                        <div v-if="opportunity.investment_max" class="text-[10.5px] text-white/50 mt-0.5">
                                            حتى {{ formatMoney(opportunity.investment_max) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-white/50 tracking-wider mb-1">العائد المتوقّع</div>
                                        <div class="text-lg font-black bg-clip-text text-transparent bg-gradient-to-l from-[#6BC8D2] to-[#3DAFB9]">{{ opportunity.expected_roi }}%</div>
                                        <div class="text-[10.5px] text-white/50 mt-0.5">سنوياً</div>
                                    </div>
                                    <div v-if="opportunity.payback_months">
                                        <div class="text-[10px] text-white/50 tracking-wider mb-1">فترة الاسترداد</div>
                                        <div class="text-lg font-black text-white">{{ opportunity.payback_months }} شهر</div>
                                    </div>
                                    <div v-if="opportunity.duration_years">
                                        <div class="text-[10px] text-white/50 tracking-wider mb-1">مدة المشروع</div>
                                        <div class="text-lg font-black text-white">{{ opportunity.duration_years }} سنوات</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ═══════ RICH CONTENT ═══════ -->
                        <!-- Executive summary -->
                        <div v-if="rich.executive_summary" class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8 shadow-card">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">الملخص التنفيذي</h2>
                            </div>
                            <p class="text-[14.5px] text-ink-body leading-[2] whitespace-pre-line">{{ rich.executive_summary }}</p>
                        </div>

                        <!-- Investment highlights (metric cards) -->
                        <div v-if="rich.investment_highlights?.length" class="rounded-[1.5rem] bg-gradient-to-br from-[#EAF6F7] to-white dark:from-[#0F2340]/60 dark:to-[#122440]/40 border border-[#3DAFB9]/25 p-7 lg:p-8">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">أبرز مؤشرات الاستثمار</h2>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                <div v-for="(m, i) in rich.investment_highlights" :key="i"
                                     class="p-4 rounded-xl bg-white dark:bg-[#0A1729]/60 border border-[#3DAFB9]/20">
                                    <div class="text-[10.5px] text-ink-muted mb-1 font-medium">{{ m.label }}</div>
                                    <div class="text-[17px] font-black text-[#2D4B7E] dark:text-[#6BC8D2]">{{ m.value }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Why this opportunity -->
                        <div v-if="rich.opportunity_reasons?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8 shadow-card">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">لماذا هذه الفرصة الآن؟</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="(r, i) in rich.opportunity_reasons" :key="i"
                                     class="p-4 rounded-xl bg-canvas border border-soft hover:border-[#3DAFB9]/35 transition-colors">
                                    <div class="flex items-start gap-2 mb-2">
                                        <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] text-white text-[11px] font-black flex items-center justify-center shrink-0">{{ i+1 }}</span>
                                        <h4 class="text-[14px] font-black text-ink leading-snug">{{ r.title }}</h4>
                                    </div>
                                    <p class="text-[12.5px] text-ink-body leading-[1.85] pl-9">{{ r.desc }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Market data -->
                        <div v-if="rich.market_data?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h3 class="text-lg font-black text-ink">بيانات السوق</h3>
                            </div>
                            <dl class="divide-y divide-soft">
                                <div v-for="(m, i) in rich.market_data" :key="i" class="flex items-center justify-between py-2.5 text-[13px]">
                                    <dt class="text-ink-muted">{{ m.label }}</dt>
                                    <dd class="font-black text-ink">{{ m.value }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Financial projections table -->
                        <div v-if="rich.financial_projections?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card overflow-hidden">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h3 class="text-lg font-black text-ink">التوقعات المالية</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-[13px]">
                                    <thead>
                                        <tr class="border-b border-soft text-ink-muted text-[11px] font-bold uppercase tracking-wider">
                                            <th class="text-start py-3 px-3">السنة</th>
                                            <th class="text-start py-3 px-3">الإيرادات</th>
                                            <th class="text-start py-3 px-3">صافي الربح</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-soft">
                                        <tr v-for="(p, i) in rich.financial_projections" :key="i">
                                            <td class="py-3 px-3 font-black text-ink">{{ p.year }}</td>
                                            <td class="py-3 px-3 font-bold text-ink">{{ p.revenue }}</td>
                                            <td class="py-3 px-3 font-bold text-emerald-600 dark:text-emerald-400">{{ p.profit }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div v-if="rich.timeline?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-8 shadow-card">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                                <h2 class="text-xl font-black text-ink">الجدول الزمني للتنفيذ</h2>
                            </div>
                            <div class="relative pr-6 rtl:pr-0 rtl:pl-6">
                                <div class="absolute right-2 rtl:right-auto rtl:left-2 top-2 bottom-2 w-0.5 bg-gradient-to-b from-[#3DAFB9] via-[#2D4B7E] to-[#3DAFB9]/30"></div>
                                <div v-for="(p, i) in rich.timeline" :key="i" class="relative flex items-start gap-4 pb-6 last:pb-0">
                                    <div class="shrink-0 -mr-6 rtl:mr-0 rtl:-ml-6 w-10 h-10 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] text-white text-[11px] font-black flex items-center justify-center shadow-md ring-4 ring-elevated">
                                        {{ String(i+1).padStart(2, '0') }}
                                    </div>
                                    <div class="flex-1 pt-1">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <h4 class="text-[14px] font-black text-ink">{{ p.phase }}</h4>
                                            <span class="text-[10.5px] px-2 py-0.5 rounded-full bg-[#3DAFB9]/12 text-[#3DAFB9] font-bold">{{ p.duration }}</span>
                                        </div>
                                        <p class="text-[13px] text-ink-body leading-relaxed">{{ p.desc }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Risks + Investor Perks side by side -->
                        <div v-if="rich.risks?.length || investorPerks.length" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div v-if="rich.risks?.length" class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.48 0l-7.1 12.25A2 2 0 005 19z"/></svg>
                                    <h3 class="text-[15px] font-black text-ink">المخاطر وحلولها</h3>
                                </div>
                                <div class="space-y-3">
                                    <div v-for="(r, i) in rich.risks" :key="i" class="p-3 rounded-xl bg-canvas border border-soft">
                                        <div class="text-[10.5px] text-amber-700 dark:text-amber-400 font-bold mb-1">الخطر</div>
                                        <p class="text-[12.5px] text-ink-body leading-relaxed">{{ r.risk }}</p>
                                        <div class="text-[10.5px] text-emerald-700 dark:text-emerald-400 font-bold mt-2 mb-1">الحل</div>
                                        <p class="text-[12.5px] text-ink-body leading-relaxed">{{ r.mitigation }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-if="investorPerks.length" class="rounded-[1.5rem] bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9] p-6 shadow-card">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-[#C2EBEF]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <h3 class="text-[15px] font-black text-white">ماذا يحصل عليه المستثمر</h3>
                                </div>
                                <ul class="space-y-2.5">
                                    <li v-for="(p, i) in investorPerks" :key="i" class="flex items-start gap-2 text-white/95 text-[13px]">
                                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-[#C2EBEF]" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622"/></svg>
                                        <span class="leading-relaxed">{{ p }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Right: apply CTA -->
                    <aside class="col-span-12 lg:col-span-4">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card sticky top-28">
                            <div class="mb-5">
                                <div class="text-[10px] text-[#3DAFB9] tracking-widest font-black uppercase mb-1">مهتم؟</div>
                                <h3 class="text-lg font-black text-ink leading-snug">تقدّم بطلبك الآن</h3>
                                <p class="text-[12.5px] text-ink-body mt-1 leading-relaxed">
                                    سيتواصل معك فريق رواد خلال 48 ساعة لتقديم تفاصيل كاملة ومناقشة الشروط.
                                </p>
                            </div>

                            <button @click="showModal = true"
                                    class="w-full py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-[1.02] transition-transform">
                                <span class="inline-flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                                    تقديم طلب استثمار
                                </span>
                            </button>

                            <dl class="mt-6 pt-6 border-t border-soft space-y-3 text-[12.5px]">
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">تاريخ النشر</dt>
                                    <dd class="font-bold text-ink">{{ opportunity.published_at }}</dd>
                                </div>
                                <div v-if="opportunity.deadline_at" class="flex items-center justify-between">
                                    <dt class="text-ink-muted">آخر موعد</dt>
                                    <dd class="font-bold text-orange-600 dark:text-orange-400">{{ opportunity.deadline_at }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">المشاهدات</dt>
                                    <dd class="font-bold text-ink">{{ opportunity.views_count }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">الطلبات</dt>
                                    <dd class="font-bold text-ink">{{ opportunity.applications_count }}</dd>
                                </div>
                            </dl>

                            <div v-if="opportunity.source === 'gov_api'" class="mt-5 pt-5 border-t border-soft">
                                <div class="text-[10.5px] text-ink-muted tracking-wider mb-1">المصدر الرسمي</div>
                                <div class="text-[12.5px] font-black text-ink">🏛️ {{ opportunity.source_name }}</div>
                            </div>
                        </div>
                    </aside>
                </div>

                <!-- Related -->
                <div v-if="related?.length" class="mt-14">
                    <h2 class="text-2xl font-black text-ink mb-5">فرص مشابهة في {{ opportunity.sector }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Link v-for="r in related" :key="r.id" :href="`/investments/${r.id}`"
                              class="group relative flex flex-col rounded-2xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card overflow-hidden transition-all">
                            <div class="aspect-[16/9] overflow-hidden">
                                <img v-if="r.cover_image" :src="r.cover_image" :alt="r.title" class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700" />
                            </div>
                            <div class="p-4">
                                <div class="text-[15px] font-black text-ink group-hover:text-[#3DAFB9] transition-colors line-clamp-2 leading-snug">{{ r.title }}</div>
                                <div class="flex items-center justify-between mt-3 text-[11.5px]">
                                    <span class="text-ink-muted">{{ r.sector }}</span>
                                    <span class="font-black text-green-600">{{ r.expected_roi }}%</span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════ APPLICATION MODAL (WIDE / PREMIUM) ═══════════ -->
        <PremiumModal v-model="showModal"
                      eyebrow="طلب استثمار"
                      :title="submitted ? 'تم استلام طلبك بنجاح' : 'التسجيل في الفرصة الاستثمارية'"
                      :subtitle="submitted ? null : opportunity.title"
                      max-width="820px">
            <div v-if="!submitted">
                <form @submit.prevent="submit" class="space-y-5">
                    <PremiumField v-model="form.company_name" label="اسم الشركة" required
                                  icon="buildings-2" placeholder="اسم شركتك أو مؤسستك"
                                  :error="form.errors.company_name" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <PremiumField v-model="form.contact_name" label="اسم المسؤول" required
                                      icon="user" placeholder="الاسم الكامل"
                                      :error="form.errors.contact_name" />
                        <PremiumField v-model="form.contact_phone" label="رقم الجوال" required type="tel"
                                      icon="phone" dir="ltr" placeholder="+9665XXXXXXXX"
                                      :error="form.errors.contact_phone" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <PremiumField v-model="form.contact_email" label="البريد الإلكتروني" required type="email"
                                      icon="letter" dir="ltr" placeholder="you@company.com"
                                      :error="form.errors.contact_email" />
                        <PremiumField v-model="form.investment_amount" label="المبلغ المُقترح" hint="اختياري"
                                      type="number" icon="wallet-money" prefix="ر.س"
                                      placeholder="100,000,000" :error="form.errors.investment_amount" />
                    </div>

                    <PremiumField v-model="form.message" label="رسالة إضافية" hint="اختياري" type="textarea"
                                  icon="document-text" placeholder="أي تفاصيل ترغب في مشاركتها معنا حول اهتمامك بهذه الفرصة..."
                                  :maxlength="2000" show-counter :rows="4"
                                  :error="form.errors.message" />

                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5 border border-[#3DAFB9]/20">
                        <img :src="iconLocked" alt="" class="w-6 h-6 shrink-0 mt-0.5" />
                        <div class="text-[12px] text-ink-body leading-relaxed">
                            <span class="font-bold text-ink">بياناتك سرّية.</span>
                            نستخدمها حصراً لدراسة طلبك الاستثماري والتواصل معك، ولا نشاركها مع أي طرف خارجي.
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-soft">
                        <button type="button" @click="showModal = false"
                                class="px-5 py-2.5 rounded-full text-[13px] text-ink-body hover:text-ink font-bold transition-colors">
                            إلغاء
                        </button>
                        <button type="submit" :disabled="form.processing"
                                class="premium-submit inline-flex items-center gap-2 px-8 py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13.5px] font-black shadow-lg shadow-[#3DAFB9]/30 disabled:opacity-60 transition-all">
                            <span v-if="form.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            إرسال الطلب
                        </button>
                    </div>
                </form>
            </div>

            <div v-else class="text-center py-6">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <span class="absolute inset-0 rounded-full bg-[#3DAFB9]/20 animate-ping"></span>
                    <span class="absolute inset-2 rounded-full bg-[#3DAFB9]/25"></span>
                    <div class="relative w-full h-full rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] flex items-center justify-center shadow-xl shadow-[#3DAFB9]/40">
                        <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
                <p class="text-[14px] text-ink-body leading-[1.9] max-w-md mx-auto mb-8">
                    شكراً لاهتمامك! سيتواصل معك فريق رواد الاستثماري خلال <span class="font-black text-ink">48 ساعة عمل</span>
                    لمناقشة تفاصيل الفرصة وترتيب اجتماع تعريفي.
                </p>
                <button @click="showModal = false; submitted = false; form.reset();"
                        class="inline-flex px-7 py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13.5px] font-black shadow-lg shadow-[#3DAFB9]/30 hover:scale-105 transition-transform">
                    ممتاز
                </button>
            </div>
        </PremiumModal>
    </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import PremiumModal from '@/Components/UI/PremiumModal.vue';
import PremiumField from '@/Components/UI/PremiumField.vue';
import { useTheme } from '@/composables/useTheme';

const props = defineProps({
    opportunity: Object,
    related:     Array,
});

const normalize = (arr) => Array.isArray(arr)
    ? arr.map(x => (typeof x === 'string' ? x : (x?.item ?? ''))).filter(Boolean)
    : [];
const rich = computed(() => props.opportunity.rich_content || {});
const investorPerks = computed(() => normalize(rich.value.investor_perks));

const { isDark } = useTheme();
const showModal = ref(false);
const submitted = ref(false);

// Brand-colored Solar icon for privacy notice
const iconLocked = computed(() => {
    const color = isDark.value ? '6BC8D2' : '3DAFB9';
    return `https://api.iconify.design/solar:shield-keyhole-bold-duotone.svg?color=%23${color}&width=48`;
});

const form = useForm({
    company_name:      '',
    contact_name:      '',
    contact_email:     '',
    contact_phone:     '',
    investment_amount: null,
    message:           '',
});

const submit = () => form.post(`/investments/${props.opportunity.id}/apply`, {
    preserveScroll: true,
    onSuccess: () => { submitted.value = true; form.reset(); },
});

const icon = (slug) => {
    const color = isDark.value ? '6BC8D2' : '3DAFB9';
    return `https://api.iconify.design/solar:${slug || 'chart-square-bold-duotone'}.svg?color=%23${color}&width=48`;
};

const formatMoney = (n) => {
    if (n >= 1_000_000_000) return (n / 1_000_000_000).toFixed(1) + ' مليار ر.س';
    if (n >= 1_000_000)     return (n / 1_000_000).toFixed(1) + ' مليون ر.س';
    return new Intl.NumberFormat('ar-SA').format(n) + ' ر.س';
};

const riskLabel = (r) => ({ low: 'منخفضة', medium: 'متوسطة', high: 'مرتفعة' }[r] || r);
const riskClass = (r) => ({
    low:    'bg-green-500 text-white',
    medium: 'bg-orange-500 text-white',
    high:   'bg-red-500 text-white',
}[r] || 'bg-gray-500 text-white');

// Very small markdown → HTML converter (headings + lists + bold)
const markdown = (md) => {
    if (!md) return '';
    let html = md
        .replace(/^## (.+)$/gm, '<h3 class="text-[16px] font-black text-ink mt-6 mb-3">$1</h3>')
        .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
        .replace(/^\* (.+)$/gm, '<li class="text-[14px] text-ink-body leading-relaxed mb-1.5">$1</li>');
    html = html.replace(/(<li[^>]*>.+?<\/li>\s*)+/gs, m => `<ul class="list-disc pr-6 rtl:pr-6 mb-4 space-y-1">${m}</ul>`);
    return html;
};
</script>

<style scoped>
.premium-submit:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 12px 32px -8px rgba(61, 175, 185, 0.45);
}
</style>
