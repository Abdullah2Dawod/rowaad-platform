<template>
    <section class="rw-widget" dir="rtl">
        <!-- Header row: avg + CTA -->
        <div class="rw-header">
            <div class="rw-avg-block">
                <div class="rw-avg-num">{{ (localAvg || 0).toFixed(1) }}</div>
                <div>
                    <div class="rw-stars-display">
                        <svg v-for="i in 5" :key="i" viewBox="0 0 24 24" class="rw-star" :class="starClass(i, localAvg)" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="rw-count">{{ localCount }} تقييم</div>
                </div>
            </div>

            <button v-if="authUser" type="button" @click="openModal" class="rw-cta">
                {{ myReview ? 'عدّل تقييمك' : 'قيّم الآن' }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4">
                    <path stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
            <a v-else href="/login" class="rw-cta rw-cta-outline">سجّل دخولك للتقييم</a>
        </div>

        <!-- Reviews list -->
        <div v-if="reviews.length" class="rw-reviews">
            <div v-for="r in reviews" :key="r.id" class="rw-review">
                <div class="rw-review-head">
                    <div class="rw-review-avatar">{{ r.user_name.charAt(0) }}</div>
                    <div class="flex-1">
                        <div class="rw-review-name">{{ r.user_name }}</div>
                        <div class="rw-stars-display" style="gap:1px">
                            <svg v-for="i in 5" :key="i" viewBox="0 0 24 24" class="rw-star rw-star-sm" :class="starClass(i, r.rating)" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                    </div>
                    <span class="rw-review-date">{{ r.date }}</span>
                </div>
                <p v-if="r.comment" class="rw-review-comment">{{ r.comment }}</p>
            </div>
        </div>
        <div v-else class="rw-empty">لا توجد تقييمات بعد — كن أول من يقيّم.</div>

        <!-- Modal -->
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
                    leave-active-class="transition duration-150 ease-in" leave-to-class="opacity-0">
            <div v-if="showModal" @click.self="showModal = false" class="rw-modal-backdrop">
                <div class="rw-modal">
                    <button type="button" @click="showModal = false" class="rw-modal-close" aria-label="إغلاق">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4">
                            <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <h3 class="rw-modal-title">شاركنا تقييمك</h3>
                    <p class="rw-modal-sub">اختر تقييمك من 1 إلى 5 نجوم</p>
                    <div class="rw-stars-input">
                        <button v-for="i in 5" :key="i" type="button"
                                @click="tempRating = i" @mouseenter="hoverRating = i" @mouseleave="hoverRating = 0"
                                class="rw-star-btn" aria-label="نجمة">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="rw-star-lg"
                                 :class="i <= (hoverRating || tempRating) ? 'rw-star-active' : 'rw-star-empty'">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </button>
                    </div>
                    <textarea v-model="tempComment" placeholder="أخبرنا عن تجربتك (اختياري)..." rows="4" class="rw-textarea"></textarea>
                    <div class="rw-modal-actions">
                        <button type="button" @click="showModal = false" class="rw-btn-secondary">إلغاء</button>
                        <button type="button" @click="submit" :disabled="!tempRating || submitting" class="rw-btn-primary">
                            <span v-if="submitting" class="rw-spinner"></span>
                            <span v-else>إرسال</span>
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    type: { type: String, required: true }, // 'service' | 'feasibility' | 'investment'
    id: { type: [Number, String], required: true },
    initialAvg: { type: [Number, String], default: 0 },
    initialCount: { type: [Number, String], default: 0 },
});

const page = usePage();
const authUser = page.props.auth?.user ?? null;

const localAvg = ref(Number(props.initialAvg));
const localCount = ref(Number(props.initialCount));
const reviews = ref([]);
const myReview = ref(null);

const showModal = ref(false);
const submitting = ref(false);
const tempRating = ref(0);
const hoverRating = ref(0);
const tempComment = ref('');

const starClass = (i, avg) => {
    const v = Number(avg) || 0;
    if (i <= Math.floor(v)) return 'rw-star-active';
    if (i - 0.5 <= v)       return 'rw-star-half';
    return 'rw-star-empty';
};

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

const loadReviews = async () => {
    try {
        const r = await fetch(`/api/reviews/${props.type}/${props.id}`);
        const j = await r.json();
        reviews.value = j.reviews || [];
    } catch {}
};

const loadMine = async () => {
    if (!authUser) return;
    try {
        const r = await fetch(`/api/reviews/${props.type}/${props.id}/mine`);
        const j = await r.json();
        myReview.value = j.review;
        if (j.review) {
            tempRating.value = j.review.rating;
            tempComment.value = j.review.comment || '';
        }
    } catch {}
};

const openModal = () => { showModal.value = true; };

const submit = async () => {
    if (!tempRating.value) return;
    submitting.value = true;
    try {
        const r = await fetch(`/api/reviews/${props.type}/${props.id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
            body: JSON.stringify({ rating: tempRating.value, comment: tempComment.value }),
        });
        if (!r.ok) throw new Error();
        showModal.value = false;
        // Refresh both
        await Promise.all([loadReviews(), loadMine()]);
        // Update local aggregate optimistically (server also updates DB)
        // Refetch page-level count via a small dedicated endpoint would be nicer;
        // recompute locally from returned list length as fallback:
        localCount.value = reviews.value.length;
        if (reviews.value.length) {
            localAvg.value = reviews.value.reduce((a, x) => a + x.rating, 0) / reviews.value.length;
        }
    } finally {
        submitting.value = false;
    }
};

onMounted(() => { loadReviews(); loadMine(); });
</script>

<style scoped>
.rw-widget { padding: 24px; border-radius: 20px; background: var(--elevated, #fff); border: 1px solid var(--soft, rgba(15,23,42,0.08)); font-family: inherit; }
.rw-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-bottom: 18px; border-bottom: 1px solid var(--soft, rgba(15,23,42,0.08)); }
.rw-avg-block { display: flex; align-items: center; gap: 14px; }
.rw-avg-num { font-size: 34px; font-weight: 900; color: #F59E0B; line-height: 1; }
.rw-stars-display { display: inline-flex; gap: 2px; }
.rw-star { width: 16px; height: 16px; }
.rw-star-sm { width: 12px; height: 12px; }
.rw-star-lg { width: 40px; height: 40px; }
.rw-star-active { color: #F59E0B; }
.rw-star-half { color: #F59E0B; opacity: 0.5; }
.rw-star-empty { color: rgba(148, 163, 184, 0.35); }
.rw-count { font-size: 11.5px; color: var(--ink-muted, #64748B); margin-top: 4px; }
.rw-cta { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 999px; font-size: 12px; font-weight: 800; color: #fff; background: linear-gradient(-90deg, #3DAFB9, #2D4B7E); border: none; cursor: pointer; transition: transform 200ms ease; }
.rw-cta:hover { transform: translateY(-1px); }
.rw-cta-outline { background: transparent; color: #3DAFB9; border: 1px solid rgba(61,175,185,0.35); text-decoration: none; }
.rw-reviews { margin-top: 18px; display: flex; flex-direction: column; gap: 14px; max-height: 380px; overflow-y: auto; padding-inline-end: 4px; }
.rw-review { padding: 14px; border-radius: 14px; background: var(--canvas, #F8FAFC); border: 1px solid var(--soft, rgba(15,23,42,0.06)); }
.rw-review-head { display: flex; align-items: center; gap: 10px; }
.rw-review-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #3DAFB9, #2D4B7E); color: #fff; font-weight: 900; font-size: 14px; display: inline-flex; align-items: center; justify-content: center; }
.rw-review-name { font-size: 12.5px; font-weight: 800; color: var(--ink, #1E293B); }
.rw-review-date { font-size: 10.5px; color: var(--ink-muted, #64748B); }
.rw-review-comment { margin-top: 8px; font-size: 12.5px; line-height: 1.85; color: var(--ink-body, #475569); }
.rw-empty { text-align: center; padding: 22px 12px; font-size: 12.5px; color: var(--ink-muted, #64748B); }
.rw-modal-backdrop { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 100; display: flex; align-items: center; justify-content: center; padding: 16px; }
.rw-modal { position: relative; width: 100%; max-width: 460px; background: var(--elevated, #fff); border: 1px solid var(--soft, rgba(15,23,42,0.08)); border-radius: 20px; padding: 30px 24px 24px; box-shadow: 0 20px 60px -20px rgba(0,0,0,0.35); }
.rw-modal-close { position: absolute; top: 12px; left: 12px; background: transparent; border: none; color: var(--ink-muted, #64748B); cursor: pointer; padding: 6px; border-radius: 8px; }
.rw-modal-close:hover { background: rgba(15,23,42,0.05); }
.rw-modal-title { font-size: 18px; font-weight: 900; color: var(--ink, #1E293B); margin-bottom: 4px; }
.rw-modal-sub { font-size: 12.5px; color: var(--ink-muted, #64748B); margin-bottom: 20px; }
.rw-stars-input { display: flex; justify-content: center; gap: 6px; margin-bottom: 18px; }
.rw-star-btn { background: transparent; border: none; padding: 4px; cursor: pointer; transition: transform 150ms ease; }
.rw-star-btn:hover { transform: scale(1.15); }
.rw-textarea { width: 100%; padding: 12px 14px; border-radius: 12px; background: var(--canvas, #F8FAFC); border: 1px solid var(--soft, rgba(15,23,42,0.1)); font-size: 13px; font-family: inherit; color: var(--ink, #1E293B); resize: vertical; min-height: 90px; }
.rw-textarea:focus { outline: none; border-color: #3DAFB9; box-shadow: 0 0 0 3px rgba(61,175,185,0.15); }
.rw-modal-actions { display: flex; justify-content: space-between; gap: 10px; margin-top: 16px; }
.rw-btn-secondary { padding: 10px 20px; border-radius: 999px; font-size: 12.5px; font-weight: 800; color: var(--ink-body, #475569); background: transparent; border: none; cursor: pointer; }
.rw-btn-secondary:hover { color: var(--ink, #1E293B); }
.rw-btn-primary { padding: 10px 26px; border-radius: 999px; font-size: 12.5px; font-weight: 800; color: #fff; background: linear-gradient(-90deg, #3DAFB9, #2D4B7E); border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 8px 18px -6px rgba(61,175,185,0.4); }
.rw-btn-primary:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; }
.rw-spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: rw-spin 0.7s linear infinite; }
@keyframes rw-spin { to { transform: rotate(360deg); } }
</style>
