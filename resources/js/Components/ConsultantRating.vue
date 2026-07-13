<template>
    <div class="rw-rating" dir="rtl">
        <!-- Current aggregate stars (display) -->
        <div class="rw-rating-summary">
            <div class="rw-rating-avg">
                <span class="rw-rating-avg-num">{{ (localAvg || 0).toFixed(1) }}</span>
                <div class="rw-rating-stars-display">
                    <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="rw-rating-star-svg"
                         :class="starClass(i, localAvg)" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
            </div>
            <div class="rw-rating-meta">
                <div class="rw-rating-count">{{ localCount }} تقييم</div>
                <button v-if="authUser && !isSelf" type="button" @click="openModal" class="rw-rating-cta">
                    {{ myReview ? 'عدّل تقييمك' : 'قيّم هذا المستشار' }}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
            </div>
        </div>

        <!-- Rating Modal -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isOpen" class="rw-rating-modal-backdrop" @click.self="closeModal">
                <transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                >
                    <div v-if="isOpen" class="rw-rating-modal">
                        <div class="rw-rating-modal-strip"></div>
                        <button type="button" @click="closeModal" class="rw-rating-modal-close" aria-label="إغلاق">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="rw-rating-modal-head">
                            <div class="rw-rating-modal-eyebrow">تقييم مستشار</div>
                            <h3 class="rw-rating-modal-title">كيف كانت تجربتك مع {{ consultantName }}؟</h3>
                            <p class="rw-rating-modal-sub">ساعد الآخرين على معرفة جودة الاستشارة — تقييمك يُخزن ويُعرض للعملاء.</p>
                        </div>

                        <div class="rw-rating-modal-stars">
                            <button
                                v-for="i in 5" :key="i"
                                type="button"
                                @click="draftRating = i"
                                @mouseenter="hoverRating = i"
                                @mouseleave="hoverRating = 0"
                                class="rw-rating-star-btn"
                                :class="{ 'is-filled': i <= (hoverRating || draftRating) }"
                                :aria-label="`${i} نجوم`"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="rw-rating-modal-hint">{{ ratingLabel }}</div>

                        <textarea
                            v-model="draftComment"
                            placeholder="اكتب رأيك (اختياري) — سيظهر بجانب اسمك في قائمة التقييمات."
                            class="rw-rating-modal-textarea"
                            maxlength="1000"
                            rows="3"
                        ></textarea>

                        <div class="rw-rating-modal-actions">
                            <button type="button" @click="closeModal" class="rw-rating-btn-ghost">إلغاء</button>
                            <button
                                type="button"
                                @click="submitRating"
                                :disabled="!draftRating || submitting"
                                class="rw-rating-btn-primary"
                            >
                                <span v-if="!submitting">{{ myReview ? 'حفظ التعديل' : 'إرسال التقييم' }}</span>
                                <span v-else class="rw-rating-spinner"></span>
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>

        <!-- Login prompt if not authed -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
        >
            <div v-if="showLoginPrompt" class="rw-rating-toast">
                <span>سجّل دخولك لتتمكّن من التقييم</span>
                <a href="/login" class="rw-rating-toast-link">تسجيل الدخول →</a>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    consultantId:   { type: Number, required: true },
    consultantName: { type: String, default: 'المستشار' },
    isOwn:          { type: Boolean, default: false }, // Is current user this consultant?
    initialAvg:     { type: Number, default: 0 },
    initialCount:   { type: Number, default: 0 },
});

const emit = defineEmits(['updated']);

const page = usePage();
const authUser = computed(() => page.props.auth?.user ?? null);
const isSelf   = computed(() => props.isOwn || (authUser.value?.id && authUser.value.id === props.consultantId));

const localAvg   = ref(props.initialAvg);
const localCount = ref(props.initialCount);
const myReview   = ref(null);

const isOpen        = ref(false);
const showLoginPrompt = ref(false);
const draftRating   = ref(0);
const hoverRating   = ref(0);
const draftComment  = ref('');
const submitting    = ref(false);

const ratingLabel = computed(() => {
    const r = hoverRating.value || draftRating.value;
    return ({
        0: 'اختر عدد النجوم',
        1: '⭐ ضعيف',
        2: '⭐⭐ مقبول',
        3: '⭐⭐⭐ جيد',
        4: '⭐⭐⭐⭐ ممتاز',
        5: '⭐⭐⭐⭐⭐ استثنائي',
    })[r] ?? '';
});

function starClass(i, avg) {
    const v = Number(avg) || 0;
    if (i <= Math.floor(v)) return 'is-full';
    if (i - v < 1 && v > 0) return 'is-half';
    return 'is-empty';
}

function csrf() { return document.querySelector('meta[name="csrf-token"]')?.content; }
const headers = () => ({
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': csrf(),
    'X-Requested-With': 'XMLHttpRequest',
});

async function openModal() {
    if (!authUser.value) {
        showLoginPrompt.value = true;
        setTimeout(() => (showLoginPrompt.value = false), 3500);
        return;
    }
    await loadMyReview();
    if (myReview.value) {
        draftRating.value  = myReview.value.rating;
        draftComment.value = myReview.value.comment ?? '';
    } else {
        draftRating.value = 0;
        draftComment.value = '';
    }
    isOpen.value = true;
}

function closeModal() {
    isOpen.value = false;
    hoverRating.value = 0;
}

async function loadMyReview() {
    if (!authUser.value) return;
    try {
        const res = await fetch(`/api/consultants/${props.consultantId}/my-review`, { headers: headers() });
        if (res.ok) {
            const data = await res.json();
            myReview.value = data.review;
        }
    } catch (e) { /* silent */ }
}

async function submitRating() {
    if (!draftRating.value || submitting.value) return;
    submitting.value = true;
    try {
        const res = await fetch(`/api/consultants/${props.consultantId}/reviews`, {
            method: 'POST',
            headers: headers(),
            body: JSON.stringify({
                rating:  draftRating.value,
                comment: draftComment.value.trim() || null,
            }),
        });
        if (!res.ok) throw new Error(await res.text());
        const data = await res.json();
        localAvg.value   = data.consultant.rating_avg;
        localCount.value = data.consultant.rating_count;
        myReview.value   = data.review;
        emit('updated', { avg: localAvg.value, count: localCount.value });
        closeModal();
    } catch (e) {
        console.error('[rating] failed', e);
    } finally {
        submitting.value = false;
    }
}

onMounted(() => {
    if (authUser.value && !isSelf.value) loadMyReview();
});
</script>

<style scoped>
.rw-rating { --brand-navy: #2D4B7E; --brand-teal: #3DAFB9; --brand-gold: #F59E0B; }

/* ─── Summary ─── */
.rw-rating-summary {
    display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
    padding: 14px 16px;
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.06), rgba(217, 119, 6, 0.03));
    border: 1px solid rgba(245, 158, 11, 0.2);
    border-radius: 14px;
}
.rw-rating-avg { display: flex; align-items: center; gap: 12px; }
.rw-rating-avg-num {
    font-size: 26px; font-weight: 900; color: #B45309;
    letter-spacing: -0.02em; font-variant-numeric: tabular-nums;
}
.rw-rating-stars-display { display: inline-flex; gap: 2px; }
.rw-rating-star-svg { width: 20px; height: 20px; transition: color 200ms; }
.rw-rating-star-svg.is-full  { color: var(--brand-gold); }
.rw-rating-star-svg.is-half  { color: var(--brand-gold); opacity: 0.6; }
.rw-rating-star-svg.is-empty { color: rgba(148, 163, 184, 0.3); }

.rw-rating-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; }
.rw-rating-count { font-size: 11.5px; color: #78350F; font-weight: 700; }
.rw-rating-cta {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 999px;
    background: linear-gradient(135deg, var(--brand-navy), var(--brand-teal));
    color: white; font-size: 11.5px; font-weight: 800; border: 0; cursor: pointer;
    font-family: inherit; transition: all 200ms;
    box-shadow: 0 4px 12px -3px rgba(61, 175, 185, 0.4);
}
.rw-rating-cta:hover { transform: translateY(-1px); box-shadow: 0 6px 16px -4px rgba(61, 175, 185, 0.55); }
.rw-rating-cta svg { width: 12px; height: 12px; }

/* ─── Modal ─── */
.rw-rating-modal-backdrop {
    position: fixed; inset: 0; z-index: 100;
    background: rgba(10, 23, 41, 0.5); backdrop-filter: blur(6px);
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
}
.rw-rating-modal {
    position: relative;
    width: min(460px, 100%);
    background: white;
    border-radius: 22px;
    padding: 28px 26px 22px;
    box-shadow: 0 32px 80px -16px rgba(45, 75, 126, 0.45);
    overflow: hidden;
}
.dark .rw-rating-modal { background: #122440; color: #F1F5F9; }
.rw-rating-modal-strip {
    position: absolute; top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--brand-navy), var(--brand-teal), #6BC8D2);
}
.rw-rating-modal-close {
    position: absolute; top: 14px; inset-inline-end: 14px;
    width: 32px; height: 32px; border-radius: 10px;
    background: rgba(15, 23, 42, 0.06); border: 0; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    color: #64748B; transition: all 200ms;
}
.rw-rating-modal-close:hover { background: rgba(239, 68, 68, 0.15); color: #DC2626; }
.rw-rating-modal-close svg { width: 16px; height: 16px; }

.rw-rating-modal-head { text-align: center; margin-bottom: 20px; }
.rw-rating-modal-eyebrow {
    display: inline-block;
    padding: 4px 12px; border-radius: 999px;
    background: rgba(245, 158, 11, 0.12); color: #B45309;
    font-size: 10.5px; font-weight: 800; letter-spacing: 0.15em;
    margin-bottom: 12px;
}
.rw-rating-modal-title {
    font-size: 18px; font-weight: 900; color: #1E293B;
    letter-spacing: -0.015em; line-height: 1.35;
    margin: 0 0 6px;
}
.dark .rw-rating-modal-title { color: #F1F5F9; }
.rw-rating-modal-sub { font-size: 12.5px; color: #64748B; line-height: 1.7; margin: 0; }

/* Stars */
.rw-rating-modal-stars {
    display: flex; justify-content: center; gap: 8px;
    margin: 24px 0 8px;
}
.rw-rating-star-btn {
    background: none; border: 0; padding: 4px; cursor: pointer;
    transition: transform 200ms cubic-bezier(0.34, 1.4, 0.5, 1);
    color: rgba(148, 163, 184, 0.35);
}
.rw-rating-star-btn:hover { transform: scale(1.15) translateY(-2px); }
.rw-rating-star-btn.is-filled { color: var(--brand-gold); filter: drop-shadow(0 4px 8px rgba(245, 158, 11, 0.35)); }
.rw-rating-star-btn svg { width: 36px; height: 36px; }

.rw-rating-modal-hint {
    text-align: center; font-size: 13px; font-weight: 700; color: #B45309;
    min-height: 20px; margin-bottom: 16px;
}
.dark .rw-rating-modal-hint { color: #FCD34D; }

.rw-rating-modal-textarea {
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid rgba(15, 23, 42, 0.1);
    background: #F8FAFC;
    font-size: 13px; font-family: inherit;
    color: #1E293B; resize: vertical; min-height: 72px;
    transition: all 200ms;
}
.dark .rw-rating-modal-textarea { background: rgba(10, 23, 41, 0.4); color: #F1F5F9; border-color: rgba(107, 200, 210, 0.15); }
.rw-rating-modal-textarea:focus {
    outline: none; background: white;
    border-color: var(--brand-teal);
    box-shadow: 0 0 0 3px rgba(61, 175, 185, 0.15);
}
.dark .rw-rating-modal-textarea:focus { background: rgba(18, 36, 64, 0.6); }

/* Actions */
.rw-rating-modal-actions {
    display: flex; gap: 10px; margin-top: 16px;
}
.rw-rating-btn-ghost, .rw-rating-btn-primary {
    flex: 1; height: 44px; border-radius: 12px; border: 0; cursor: pointer;
    font-size: 13px; font-weight: 800; font-family: inherit;
    transition: all 200ms;
    display: inline-flex; align-items: center; justify-content: center;
}
.rw-rating-btn-ghost {
    background: rgba(15, 23, 42, 0.06); color: #475569;
}
.dark .rw-rating-btn-ghost { background: rgba(107, 200, 210, 0.08); color: #94A3B8; }
.rw-rating-btn-ghost:hover { background: rgba(15, 23, 42, 0.1); }

.rw-rating-btn-primary {
    background: linear-gradient(135deg, var(--brand-navy), var(--brand-teal));
    color: white;
    box-shadow: 0 8px 20px -6px rgba(61, 175, 185, 0.45);
}
.rw-rating-btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 10px 22px -6px rgba(61, 175, 185, 0.6); }
.rw-rating-btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

.rw-rating-spinner {
    width: 16px; height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: rw-spin 700ms linear infinite;
}
@keyframes rw-spin { to { transform: rotate(360deg); } }

/* Login toast */
.rw-rating-toast {
    position: fixed; bottom: 20px; inset-inline-start: 50%;
    transform: translateX(-50%); z-index: 60;
    padding: 12px 20px; border-radius: 999px;
    background: #1E293B; color: white;
    display: inline-flex; align-items: center; gap: 10px;
    font-size: 12.5px; font-weight: 700;
    box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.35);
}
.rw-rating-toast-link { color: #6BC8D2; text-decoration: none; font-weight: 800; }

@media (max-width: 640px) {
    .rw-rating-summary { padding: 12px; }
    .rw-rating-avg-num { font-size: 22px; }
    .rw-rating-star-svg { width: 16px; height: 16px; }
    .rw-rating-modal { padding: 24px 20px 18px; }
    .rw-rating-star-btn svg { width: 32px; height: 32px; }
}
</style>
