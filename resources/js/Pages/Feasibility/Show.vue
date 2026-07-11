<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-14 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[10%] w-[420px] h-[420px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
            </div>

            <div class="relative max-w-[1200px] mx-auto px-6 lg:px-10">
                <Link href="/feasibility-studies" class="inline-flex items-center gap-2 text-[12px] text-ink-body hover:text-[#3DAFB9] font-bold mb-6 transition-colors">
                    <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                    كل دراسات الجدوى
                </Link>

                <div class="grid grid-cols-12 gap-8">
                    <!-- Left: content -->
                    <div class="col-span-12 lg:col-span-8">
                        <div v-if="study.specialization" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#3DAFB9]/10 border border-[#3DAFB9]/25 mb-4">
                            <span class="text-[11px] font-bold text-[#3DAFB9] tracking-wider">{{ study.specialization.name_ar }}</span>
                        </div>
                        <h1 class="text-3xl lg:text-[2.4rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-tight mb-4">{{ study.title }}</h1>
                        <p class="text-[15px] text-ink-body leading-[1.9] mb-8">{{ study.excerpt }}</p>

                        <div v-if="study.cover_image" class="rounded-[1.5rem] overflow-hidden border border-soft mb-8">
                            <img :src="study.cover_image.startsWith('http') ? study.cover_image : `/storage/${study.cover_image}`" :alt="study.title" class="w-full" />
                        </div>

                        <div class="prose prose-sm max-w-none">
                            <div class="text-[14.5px] text-ink-body leading-[1.9] whitespace-pre-line">{{ study.description }}</div>
                        </div>
                    </div>

                    <!-- Right: purchase card -->
                    <aside class="col-span-12 lg:col-span-4">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card sticky top-28">
                            <div class="text-center mb-6">
                                <span v-if="study.is_free" class="text-3xl font-black text-green-600">مجاناً</span>
                                <template v-else>
                                    <span class="text-3xl font-black text-gradient-brand">{{ formatPrice(study.price) }}</span>
                                    <span class="text-[13px] text-ink-muted mr-1">ر.س</span>
                                </template>
                            </div>
                            <button class="w-full py-3 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-black shadow-md hover:scale-[1.02] transition-transform">
                                {{ study.is_free ? 'حمّل الآن مجاناً' : 'شراء وتحميل' }}
                            </button>

                            <ul class="mt-6 pt-6 border-t border-soft space-y-3 text-[12.5px]">
                                <li v-if="study.pages_count" class="flex items-center justify-between">
                                    <span class="text-ink-muted">عدد الصفحات</span>
                                    <span class="font-bold text-ink">{{ study.pages_count }}</span>
                                </li>
                                <li v-if="study.sector" class="flex items-center justify-between">
                                    <span class="text-ink-muted">القطاع</span>
                                    <span class="font-bold text-ink">{{ study.sector }}</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-ink-muted">اللغة</span>
                                    <span class="font-bold text-ink">{{ study.language === 'ar' ? 'العربية' : 'English' }}</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-ink-muted">المشاهدات</span>
                                    <span class="font-bold text-ink">{{ study.views_count }}</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-ink-muted">التنزيلات</span>
                                    <span class="font-bold text-ink">{{ study.purchases_count }}</span>
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
defineProps({ study: Object });
const formatPrice = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));
</script>
