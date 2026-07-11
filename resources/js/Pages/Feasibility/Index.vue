<template>
    <MainLayout>
        <section class="relative pt-28 sm:pt-32 lg:pt-40 pb-8 sm:pb-10 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[15%] w-[500px] h-[500px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.12), transparent 70%);"></div>
            </div>

            <div class="relative max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold mb-3">دراسات الجدوى</div>
                <div class="flex flex-wrap items-end justify-between gap-4 mb-3">
                    <div>
                        <h1 class="text-3xl lg:text-[2.4rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-tight">
                            سوق دراسات الجدوى<br>
                            <span class="text-gradient-brand">جاهزة للتنزيل الفوري</span>
                        </h1>
                        <p class="text-[14px] text-ink-body mt-3 max-w-lg">دراسات جدوى معتمدة من فريق رواد، أو ارفع دراستك للمراجعة والنشر.</p>
                    </div>
                    <Link href="/feasibility/submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                        اطرح دراستك للبيع
                    </Link>
                </div>
            </div>
        </section>

        <!-- Filters -->
        <section class="relative pt-4 pb-4">
            <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div class="rounded-2xl bg-elevated border border-soft p-4 lg:p-5 shadow-card">
                    <div class="flex flex-col lg:flex-row gap-3 lg:items-center">
                        <div class="relative flex-1">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-4 rtl:right-4 w-4 h-4 text-ink-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input v-model="q" @input="debouncedApply" type="text" placeholder="ابحث عن دراسة..."
                                   class="w-full pr-11 rtl:pr-11 rtl:pl-4 pl-4 py-2.5 rounded-full bg-canvas border border-soft text-[14px] text-ink placeholder:text-ink-muted focus:outline-none focus:border-[#3DAFB9] transition-colors" />
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2 overflow-x-auto pb-1 -mb-1">
                        <button @click="setSpec(null)"
                                :class="['shrink-0 px-4 py-2 rounded-full text-[12px] font-bold border transition-all whitespace-nowrap',
                                         !specialization ? 'bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white border-transparent shadow-sm' : 'bg-transparent border-soft text-ink-body hover:border-[#3DAFB9]/40 hover:text-ink']">
                            كل التخصصات
                        </button>
                        <button v-for="s in specializations" :key="s.slug" @click="setSpec(s.slug)"
                                :class="['shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[12px] font-bold border transition-all whitespace-nowrap',
                                         specialization === s.slug ? 'bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white border-transparent shadow-sm' : 'bg-transparent border-soft text-ink-body hover:border-[#3DAFB9]/40 hover:text-ink']">
                            {{ s.name_ar }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Grid -->
        <section class="relative pt-4 pb-24">
            <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-10">
                <div v-if="!studies.data.length" class="text-center py-16 rounded-[1.5rem] bg-elevated border border-soft">
                    <div class="w-16 h-16 rounded-full bg-[#3DAFB9]/10 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-ink-body">لا توجد دراسات مطابقة حالياً. كن أول من يطرح دراسة!</p>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <Link v-for="s in studies.data" :key="s.id" :href="`/feasibility-studies/${s.id}`"
                          class="group relative flex flex-col rounded-3xl bg-elevated border border-soft hover:border-[#3DAFB9]/40 hover:shadow-card-hover overflow-hidden transition-all duration-500 hover:-translate-y-1">
                        <div v-if="s.is_featured" class="absolute top-4 left-4 rtl:left-auto rtl:right-4 z-10 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white text-[10px] font-black shadow-md">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            مميّزة
                        </div>

                        <div class="relative aspect-[4/2.4] overflow-hidden bg-gradient-to-br from-[#2D4B7E]/20 to-[#3DAFB9]/20">
                            <img v-if="s.cover_image" :src="s.cover_image.startsWith('http') ? s.cover_image : `/storage/${s.cover_image}`" :alt="s.title" loading="lazy"
                                 class="w-full h-full object-cover saturate-[0.8] group-hover:saturate-100 group-hover:scale-[1.05] transition-all duration-[900ms]" />
                            <div v-else class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div v-if="s.specialization" class="absolute bottom-3 right-3 rtl:right-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/95 dark:bg-[#122440]/95 backdrop-blur border border-white/60 dark:border-[#3DAFB9]/30">
                                <span class="text-[10px] font-bold text-ink">{{ s.specialization.name_ar }}</span>
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="text-[15px] font-black text-ink group-hover:text-[#2D4B7E] dark:group-hover:text-[#6BC8D2] transition-colors line-clamp-2 leading-snug">{{ s.title }}</h3>
                            <p class="text-[12.5px] text-ink-body leading-relaxed mt-2 line-clamp-3 flex-1">{{ s.excerpt }}</p>
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-soft">
                                <div class="flex items-center gap-2 text-[10.5px] text-ink-muted">
                                    <span v-if="s.pages_count">{{ s.pages_count }} صفحة</span>
                                    <span v-if="s.pages_count">·</span>
                                    <span>{{ s.purchases_count }} عملية شراء</span>
                                </div>
                                <div class="text-left">
                                    <span v-if="s.is_free" class="text-[13px] font-black text-green-600">مجاناً</span>
                                    <template v-else>
                                        <span class="text-[15px] font-black text-ink">{{ formatPrice(s.price) }}</span>
                                        <span class="text-[10px] text-ink-muted mr-1">ر.س</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({ studies: Object, specializations: Array, filters: Object });

const q              = ref(props.filters?.q ?? '');
const specialization = ref(props.filters?.specialization ?? null);

const formatPrice = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));

const apply = () => router.get('/feasibility-studies', {
    q: q.value || undefined,
    specialization: specialization.value || undefined,
}, { preserveState: true, preserveScroll: true, replace: true });

let t;
const debouncedApply = () => { clearTimeout(t); t = setTimeout(apply, 400); };
const setSpec = (slug) => { specialization.value = slug; apply(); };
</script>
