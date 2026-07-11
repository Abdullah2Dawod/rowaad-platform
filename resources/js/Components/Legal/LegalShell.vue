<template>
    <MainLayout>
        <!-- ============ HERO ============ -->
        <section class="relative pt-32 lg:pt-40 pb-12 overflow-hidden bg-paper">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[10%] w-[500px] h-[500px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>
                <div class="absolute bottom-0 left-[5%] w-[400px] h-[400px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.08), transparent 70%); animation-delay: 3s;"></div>
            </div>

            <div class="relative max-w-[1000px] mx-auto px-6 lg:px-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex items-end gap-[3px] h-5">
                        <span class="block w-[3px] h-3 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse"></span>
                        <span class="block w-[3px] h-5 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: .2s;"></span>
                        <span class="block w-[3px] h-3.5 bg-gradient-to-t from-[#2D4B7E] to-[#3DAFB9] rounded-full bar-pulse" style="animation-delay: .4s;"></span>
                    </div>
                    <span class="text-[11px] tracking-[0.25em] uppercase font-bold text-[#3DAFB9]">{{ eyebrow }}</span>
                </div>

                <h1 class="text-3xl md:text-4xl lg:text-[2.6rem] font-black text-[#2D4B7E] dark:text-[#C2EBEF] leading-tight mb-4">
                    <slot name="title" />
                </h1>
                <p class="text-[14.5px] text-ink-body leading-[1.9] max-w-3xl">
                    <slot name="subtitle" />
                </p>

                <!-- Meta strip -->
                <div class="mt-6 flex flex-wrap items-center gap-4 text-[11.5px] text-ink-muted">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 6v6l4 2"/><circle cx="12" cy="12" r="9"/></svg>
                        آخر تحديث: {{ lastUpdated }}
                    </span>
                    <span class="text-ink-muted/50">·</span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        وثيقة قانونية
                    </span>
                </div>
            </div>
        </section>

        <!-- ============ BODY (TOC + content) ============ -->
        <section class="relative pb-24">
            <div class="max-w-[1000px] mx-auto px-6 lg:px-10">
                <div class="grid grid-cols-12 gap-8">
                    <!-- TOC (sticky sidebar) -->
                    <aside class="hidden lg:block col-span-3">
                        <nav class="sticky top-28 rounded-2xl bg-elevated border border-soft p-5 shadow-card">
                            <div class="text-[10.5px] font-black text-[#3DAFB9] tracking-[0.25em] uppercase mb-3">المحتويات</div>
                            <ul class="space-y-1.5">
                                <li v-for="(item, i) in toc" :key="i">
                                    <a :href="`#${item.id}`"
                                       :class="['block text-[12.5px] leading-relaxed py-1.5 pr-3 rtl:pr-3 rtl:pl-0 border-r-2 rtl:border-r-2 transition-colors',
                                                activeId === item.id
                                                  ? 'text-[#2D4B7E] dark:text-[#6BC8D2] border-[#3DAFB9] font-bold'
                                                  : 'text-ink-body border-transparent hover:text-ink hover:border-[#3DAFB9]/40 font-medium']">
                                        {{ item.title }}
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </aside>

                    <!-- Content -->
                    <article class="col-span-12 lg:col-span-9">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 lg:p-10 shadow-card">
                            <slot />
                        </div>

                        <!-- Cross-link footer -->
                        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 p-5 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5 border border-[#3DAFB9]/20">
                            <div>
                                <div class="text-[13px] font-black text-ink">{{ otherLabel }}</div>
                                <div class="text-[11.5px] text-ink-body mt-1">اطّلع على {{ otherHint }} لمنصة رواد بلا حدود.</div>
                            </div>
                            <Link :href="otherHref"
                                  class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-elevated border border-soft text-ink text-[12.5px] font-bold hover:border-[#3DAFB9]/40 hover:shadow-card transition-all">
                                {{ otherCta }}
                                <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </Link>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    eyebrow:     { type: String, required: true },
    lastUpdated: { type: String, default: '2026-07-04' },
    toc:         { type: Array, required: true },
    otherLabel:  { type: String, required: true },
    otherHint:   { type: String, required: true },
    otherHref:   { type: String, required: true },
    otherCta:    { type: String, required: true },
});

const activeId = ref(props.toc[0]?.id ?? null);

let observer;
onMounted(() => {
    observer = new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (e.isIntersecting) activeId.value = e.target.id;
        });
    }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });

    props.toc.forEach(({ id }) => {
        const el = document.getElementById(id);
        if (el) observer.observe(el);
    });
});
onUnmounted(() => observer?.disconnect());
</script>
