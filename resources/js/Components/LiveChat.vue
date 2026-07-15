<template>
    <div class="rw-chat" dir="rtl">
        <!-- ═══════════ Floating Toggle Button ═══════════ -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 scale-90 translate-y-4"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-90"
        >
            <button
                v-if="!isOpen"
                @click="handleOpen"
                class="rw-chat-fab"
                :aria-label="hasUnread ? 'رسائل جديدة — تحدث مع مستشارك' : 'تحدث مع مستشارك'"
            >
                <span class="rw-chat-fab-pulse"></span>
                <span class="rw-chat-fab-pulse rw-chat-fab-pulse-2"></span>
                <svg class="rw-chat-fab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span v-if="hasUnread" class="rw-chat-fab-badge">{{ unreadCount }}</span>
                <span class="rw-chat-fab-tooltip" role="tooltip">
                    تحدث مع مستشارك
                </span>
            </button>
        </transition>

        <!-- ═══════════ Chat Panel ═══════════ -->
        <transition
            enter-active-class="transition duration-400 ease-out"
            enter-from-class="opacity-0 scale-95 translate-y-8"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition duration-250 ease-in"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-4"
        >
            <div v-if="isOpen" class="rw-chat-panel">
                <!-- Header -->
                <div class="rw-chat-header">
                    <div class="rw-chat-header-brand">
                        <div class="rw-chat-header-logo">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div class="rw-chat-header-text">
                            <div class="rw-chat-header-title">
                                {{ conversation?.consultant?.name || 'الدعم الفني · رواد' }}
                            </div>
                            <div class="rw-chat-header-sub">
                                <span class="rw-chat-status-dot" :class="statusDotClass"></span>
                                {{ statusText }}
                            </div>
                        </div>
                    </div>
                    <button @click="isOpen = false" class="rw-chat-close" aria-label="إغلاق">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Guest CTA (not logged in) -->
                <div v-if="!authUser" class="rw-chat-guest">
                    <div class="rw-chat-guest-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="rw-chat-guest-title">مرحباً بك في رواد</div>
                    <div class="rw-chat-guest-sub">
                        سجّل دخولك للتحدث مع أحد مستشارينا مباشرة والحصول على استشارتك بأسرع وقت.
                    </div>
                    <div class="rw-chat-guest-actions">
                        <a href="/login" class="rw-chat-btn rw-chat-btn-primary">تسجيل الدخول</a>
                        <a href="/register" class="rw-chat-btn rw-chat-btn-ghost">إنشاء حساب</a>
                    </div>
                </div>

                <!-- Chat body -->
                <template v-else>
                    <div class="rw-chat-body" ref="bodyEl">
                        <div v-if="loading && !conversation" class="rw-chat-loading">
                            <div class="rw-chat-spinner"></div>
                            <div class="rw-chat-loading-text">جاري تحضير المحادثة...</div>
                        </div>

                        <template v-else-if="conversation">
                            <div
                                v-for="msg in conversation.messages"
                                :key="msg.id"
                                class="rw-chat-msg"
                                :class="[
                                    'rw-chat-msg--' + msg.sender_type,
                                    msg.is_mine ? 'rw-chat-msg--mine' : 'rw-chat-msg--theirs',
                                ]"
                            >
                                <div v-if="msg.sender_type !== 'system'" class="rw-chat-msg-name">
                                    {{ msg.is_mine ? 'أنت' : (msg.sender_name || (msg.sender_type === 'consultant' ? 'المستشار' : 'العميل')) }}
                                </div>
                                <div class="rw-chat-msg-bubble">
                                    <div class="rw-chat-msg-body">{{ msg.body }}</div>
                                    <div class="rw-chat-msg-time">{{ msg.time }}</div>
                                </div>
                            </div>

                            <div v-if="!conversation.consultant && conversation.status === 'open'" class="rw-chat-hint">
                                <div class="rw-chat-typing-dots">
                                    <span></span><span></span><span></span>
                                </div>
                                جاري توصيلك بمستشار متاح…
                            </div>
                        </template>
                    </div>

                    <!-- Input -->
                    <form @submit.prevent="sendMessage" class="rw-chat-input-row">
                        <input
                            v-model="draft"
                            type="text"
                            placeholder="اكتب رسالتك..."
                            class="rw-chat-input"
                            :disabled="sending"
                            maxlength="2000"
                            ref="inputEl"
                        />
                        <button
                            type="submit"
                            :disabled="!draft.trim() || sending"
                            class="rw-chat-send"
                            aria-label="إرسال"
                        >
                            <svg v-if="!sending" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                            <div v-else class="rw-chat-send-spinner"></div>
                        </button>
                    </form>
                </template>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const authUser = computed(() => page.props.auth?.user);

const isOpen = ref(false);
const loading = ref(false);
const sending = ref(false);
const conversation = ref(null);
const draft = ref('');
const bodyEl = ref(null);
const inputEl = ref(null);
const pollTimer = ref(null);
const lastSeenMessageId = ref(0);

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;
const headers = () => ({
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': csrf(),
    'X-Requested-With': 'XMLHttpRequest',
});

const statusText = computed(() => {
    if (!conversation.value) return 'متاح للتواصل';
    if (conversation.value.status === 'closed') return 'المحادثة مغلقة';
    if (conversation.value.consultant) return `${conversation.value.consultant.title || 'مستشار معتمد'} · متصل`;
    return 'جاري توصيلك...';
});

const statusDotClass = computed(() => {
    if (!conversation.value) return 'rw-chat-status--online';
    if (conversation.value.status === 'closed') return 'rw-chat-status--offline';
    if (conversation.value.consultant) return 'rw-chat-status--online';
    return 'rw-chat-status--pending';
});

// Unread badge — messages from the other side that came in while chat closed
const unreadCount = ref(0);
const hasUnread = computed(() => unreadCount.value > 0);

async function handleOpen() {
    isOpen.value = true;
    unreadCount.value = 0;
    if (!authUser.value) return;
    await startConversation();
    startPolling();
    await nextTick();
    inputEl.value?.focus();
    scrollToBottom();
}

async function startConversation() {
    if (conversation.value) return;
    loading.value = true;
    try {
        const res = await fetch('/api/chat/start', { method: 'POST', headers: headers() });
        if (!res.ok) throw new Error(await res.text());
        const data = await res.json();
        conversation.value = data.conversation;
        lastSeenMessageId.value = data.conversation.messages.at(-1)?.id || 0;
    } catch (e) {
        console.error('[chat] start failed', e);
    } finally {
        loading.value = false;
    }
}

async function refreshMessages() {
    if (!conversation.value) return;
    try {
        const res = await fetch(`/api/chat/${conversation.value.id}/messages`, { headers: headers() });
        if (!res.ok) return;
        const data = await res.json();
        const prevCount = conversation.value.messages.length;
        conversation.value = data.conversation;
        // Track unread if panel is closed and a new *incoming* message arrived
        const latest = data.conversation.messages.at(-1);
        if (latest && latest.id > lastSeenMessageId.value) {
            if (!isOpen.value && !latest.is_mine) unreadCount.value++;
            lastSeenMessageId.value = latest.id;
        }
        if (conversation.value.messages.length > prevCount) {
            await nextTick();
            scrollToBottom();
        }
    } catch (e) {
        // silent — network hiccup
    }
}

async function sendMessage() {
    if (!draft.value.trim() || sending.value) return;
    sending.value = true;
    const body = draft.value.trim();
    draft.value = '';
    try {
        const res = await fetch(`/api/chat/${conversation.value.id}/send`, {
            method: 'POST',
            headers: headers(),
            body: JSON.stringify({ body }),
        });
        if (!res.ok) throw new Error(await res.text());
        const data = await res.json();
        conversation.value = data.conversation;
        lastSeenMessageId.value = data.conversation.messages.at(-1)?.id || 0;
        await nextTick();
        scrollToBottom();
        inputEl.value?.focus();
    } catch (e) {
        console.error('[chat] send failed', e);
        draft.value = body; // restore on error
    } finally {
        sending.value = false;
    }
}

function scrollToBottom() {
    if (bodyEl.value) bodyEl.value.scrollTop = bodyEl.value.scrollHeight;
}

function startPolling() {
    stopPolling();
    // Faster polling when panel is open
    pollTimer.value = setInterval(() => refreshMessages(), isOpen.value ? 3500 : 15000);
}
function stopPolling() {
    if (pollTimer.value) {
        clearInterval(pollTimer.value);
        pollTimer.value = null;
    }
}

// Background polling only if user is authenticated — surface unread count
onMounted(() => {
    if (authUser.value) {
        // Start conversation lazily (only when user opens), but poll for incoming messages
        // if a conversation already exists.
        pollTimer.value = setInterval(() => {
            if (conversation.value) refreshMessages();
        }, 15000);
    }
});
onUnmounted(() => stopPolling());

// When panel opens/closes, adjust poll speed
watch(isOpen, (open) => {
    if (open) startPolling();
    else if (conversation.value) startPolling(); // slower interval
});
</script>

<style scoped>
.rw-chat {
    --brand-navy: #2D4B7E;
    --brand-teal: #3DAFB9;
    --brand-teal-soft: #6BC8D2;
    --brand-ink-1: #0A1729;
    --brand-ink-2: #122440;
    position: fixed;
    bottom: 20px;
    inset-inline-end: 20px;
    z-index: 60;
    font-family: 'Alexandria', 'Almarai', sans-serif;
}

/* ═══════════ Floating Action Button ═══════════ */
.rw-chat-fab {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    border: none;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--brand-navy), var(--brand-teal));
    color: white;
    cursor: pointer;
    box-shadow: 0 10px 26px -8px rgba(45, 75, 126, 0.55),
                0 4px 12px -4px rgba(61, 175, 185, 0.45);
    transition: transform 300ms cubic-bezier(0.16, 1, 0.3, 1),
                box-shadow 300ms cubic-bezier(0.16, 1, 0.3, 1);
    font-family: inherit;
    animation: rw-chat-float 3.6s ease-in-out infinite;
}
.rw-chat-fab:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 16px 34px -8px rgba(45, 75, 126, 0.65),
                0 8px 18px -4px rgba(61, 175, 185, 0.55);
    animation-play-state: paused;
}
.rw-chat-fab:hover .rw-chat-fab-tooltip {
    opacity: 1;
    transform: translateY(-4px);
    pointer-events: auto;
}
.rw-chat-fab-icon { width: 22px; height: 22px; position: relative; z-index: 2; }
.rw-chat-fab-tooltip {
    position: absolute;
    bottom: calc(100% + 8px);
    inset-inline-end: 0;
    padding: 8px 14px;
    background: rgba(15, 23, 42, 0.94);
    color: #fff;
    font-size: 12.5px;
    font-weight: 700;
    white-space: nowrap;
    border-radius: 10px;
    box-shadow: 0 10px 24px -6px rgba(0, 0, 0, 0.3);
    opacity: 0;
    pointer-events: none;
    transform: translateY(4px);
    transition: opacity 200ms ease, transform 200ms ease;
    backdrop-filter: blur(6px);
}
.rw-chat-fab-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    inset-inline-end: 18px;
    border: 6px solid transparent;
    border-top-color: rgba(15, 23, 42, 0.94);
}
.rw-chat-fab-pulse {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 2px solid var(--brand-teal-soft);
    opacity: 0.6;
    animation: rw-chat-pulse 2.4s cubic-bezier(0, 0, 0.2, 1) infinite;
    pointer-events: none;
}
.rw-chat-fab-pulse-2 { animation-delay: 1.2s; }
@keyframes rw-chat-pulse {
    0%   { transform: scale(1);    opacity: 0.6; }
    100% { transform: scale(1.55); opacity: 0;   }
}
@keyframes rw-chat-float {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-4px); }
}
.rw-chat-fab-badge {
    position: absolute;
    top: -4px;
    inset-inline-end: -4px;
    min-width: 22px;
    height: 22px;
    padding: 0 6px;
    border-radius: 999px;
    background: #EF4444;
    color: white;
    font-size: 11px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25);
}

/* ═══════════ Chat Panel ═══════════ */
.rw-chat-panel {
    width: min(380px, calc(100vw - 32px));
    height: min(600px, calc(100vh - 120px));
    display: flex;
    flex-direction: column;
    border-radius: 24px;
    overflow: hidden;
    background: #FFFFFF;
    box-shadow: 0 32px 80px -16px rgba(45, 75, 126, 0.35),
                0 16px 40px -8px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(61, 175, 185, 0.18);
    transform-origin: bottom right;
}
.dark .rw-chat-panel {
    background: var(--brand-ink-2);
    border-color: rgba(107, 200, 210, 0.15);
}

/* Header */
.rw-chat-header {
    position: relative;
    padding: 18px 18px 16px;
    background: linear-gradient(135deg, var(--brand-ink-1) 0%, var(--brand-navy) 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    overflow: hidden;
}
.rw-chat-header::before {
    content: '';
    position: absolute;
    top: -50%;
    inset-inline-end: -20%;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(107, 200, 210, 0.2), transparent 65%);
    pointer-events: none;
}
.rw-chat-header-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    min-width: 0;
    flex: 1;
}
.rw-chat-header-logo {
    flex-shrink: 0;
    width: 42px; height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--brand-teal-soft), var(--brand-teal));
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 6px 16px -4px rgba(61, 175, 185, 0.5);
}
.rw-chat-header-logo svg { width: 20px; height: 20px; color: white; }
.rw-chat-header-text { min-width: 0; }
.rw-chat-header-title {
    font-size: 14px; font-weight: 800; color: white;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.rw-chat-header-sub {
    font-size: 11px; color: rgba(255, 255, 255, 0.65);
    margin-top: 2px; display: flex; align-items: center; gap: 6px;
}
.rw-chat-status-dot {
    width: 7px; height: 7px; border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}
.rw-chat-status--online  { background: #10B981; }
.rw-chat-status--pending { background: #F59E0B; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2); animation: rw-chat-blink 1.6s infinite; }
.rw-chat-status--offline { background: #94A3B8; box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.2); }
@keyframes rw-chat-blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

.rw-chat-close {
    flex-shrink: 0;
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.08);
    border: 0; cursor: pointer; color: white;
    display: inline-flex; align-items: center; justify-content: center;
    transition: background 200ms;
    position: relative;
}
.rw-chat-close:hover { background: rgba(255, 255, 255, 0.15); }
.rw-chat-close svg { width: 18px; height: 18px; }

/* Body */
.rw-chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 18px 16px;
    background: linear-gradient(180deg, #FAFCFD 0%, #F0F4FA 100%);
    display: flex; flex-direction: column; gap: 10px;
}
.dark .rw-chat-body {
    background: linear-gradient(180deg, var(--brand-ink-1) 0%, var(--brand-ink-2) 100%);
}
.rw-chat-body::-webkit-scrollbar { width: 6px; }
.rw-chat-body::-webkit-scrollbar-thumb { background: rgba(61, 175, 185, 0.3); border-radius: 3px; }

/* ═══════ Messages — differentiated by SENDER TYPE (works for any viewer) ═══════ */
.rw-chat-msg { display: flex; flex-direction: column; }
/* Alignment: consultant on RIGHT, user on LEFT, system centered */
.rw-chat-msg--consultant { align-items: flex-end; }
.rw-chat-msg--user       { align-items: flex-start; }
.rw-chat-msg--system     { align-items: center; }
.rw-chat-msg-name {
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: 0.02em;
    margin: 0 6px 4px;
}
.rw-chat-msg--consultant .rw-chat-msg-name { color: #3DAFB9; }
.rw-chat-msg--user       .rw-chat-msg-name { color: #2D4B7E; }
:root.dark .rw-chat-msg--user .rw-chat-msg-name { color: #6BC8D2; }
.rw-chat-msg-bubble {
    max-width: 78%;
    padding: 10px 14px;
    border-radius: 18px;
    font-size: 13px;
    line-height: 1.65;
    box-shadow: 0 2px 6px -2px rgba(15, 23, 42, 0.06);
    word-wrap: break-word;
}
/* Consultant bubble — teal/navy brand gradient, right-side pointer */
.rw-chat-msg--consultant .rw-chat-msg-bubble {
    background: linear-gradient(135deg, #2D4B7E, #3DAFB9);
    color: white;
    border-bottom-inline-end-radius: 4px;
    box-shadow: 0 4px 10px -3px rgba(61, 175, 185, 0.35);
}
/* User bubble — soft slate, left-side pointer */
.rw-chat-msg--user .rw-chat-msg-bubble {
    background: #F1F5F9;
    color: #1E293B;
    border: 1px solid rgba(148, 163, 184, 0.25);
    border-bottom-inline-start-radius: 4px;
}
:root.dark .rw-chat-msg--user .rw-chat-msg-bubble {
    background: #1E3A5F;
    color: #F1F5F9;
    border-color: rgba(107, 200, 210, 0.2);
}
/* Optional subtle ring on the current viewer's own message */
.rw-chat-msg--mine .rw-chat-msg-bubble { outline: 2px solid rgba(61,175,185,0.45); outline-offset: 1px; }
.rw-chat-msg--system { justify-content: center; }
.rw-chat-msg--system .rw-chat-msg-bubble {
    max-width: 90%;
    background: linear-gradient(135deg, rgba(61, 175, 185, 0.08), rgba(45, 75, 126, 0.04));
    border: 1px solid rgba(61, 175, 185, 0.2);
    color: #475569;
    text-align: center;
    font-size: 12.5px;
    border-radius: 14px;
}
.rw-chat-msg-time {
    font-size: 10px;
    opacity: 0.65;
    margin-top: 4px;
    font-variant-numeric: tabular-nums;
}
.rw-chat-msg--mine .rw-chat-msg-time { text-align: end; color: rgba(255, 255, 255, 0.8); }
.rw-chat-msg--system .rw-chat-msg-time { display: none; }

/* Hint (waiting) */
.rw-chat-hint {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 16px;
    background: rgba(245, 158, 11, 0.06);
    border: 1px dashed rgba(245, 158, 11, 0.3);
    border-radius: 12px;
    font-size: 11.5px;
    color: #B45309;
    font-weight: 700;
}
.rw-chat-typing-dots { display: inline-flex; gap: 4px; }
.rw-chat-typing-dots span {
    width: 6px; height: 6px; border-radius: 50%;
    background: #D97706;
    animation: rw-chat-typing 1.4s infinite;
}
.rw-chat-typing-dots span:nth-child(2) { animation-delay: 0.2s; }
.rw-chat-typing-dots span:nth-child(3) { animation-delay: 0.4s; }
@keyframes rw-chat-typing {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
    30%           { transform: translateY(-4px); opacity: 1; }
}

/* Input */
.rw-chat-input-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px;
    background: white;
    border-top: 1px solid rgba(15, 23, 42, 0.06);
}
.dark .rw-chat-input-row { background: var(--brand-ink-2); border-top-color: rgba(107, 200, 210, 0.12); }
.rw-chat-input {
    flex: 1;
    height: 42px;
    padding: 0 16px;
    border-radius: 999px;
    border: 1px solid rgba(15, 23, 42, 0.1);
    background: #F8FAFC;
    font-size: 13px;
    font-family: inherit;
    color: #1E293B;
    outline: none;
    transition: all 200ms;
}
.dark .rw-chat-input { background: #0F2340; color: #F1F5F9; border-color: rgba(107, 200, 210, 0.15); }
.rw-chat-input:focus {
    border-color: var(--brand-teal);
    background: white;
    box-shadow: 0 0 0 3px rgba(61, 175, 185, 0.15);
}
.dark .rw-chat-input:focus { background: #15294D; }
.rw-chat-send {
    flex-shrink: 0;
    width: 42px; height: 42px;
    border-radius: 50%;
    border: 0;
    background: linear-gradient(135deg, var(--brand-navy), var(--brand-teal));
    color: white;
    cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all 200ms;
    box-shadow: 0 4px 12px -3px rgba(61, 175, 185, 0.4);
}
.rw-chat-send:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 16px -4px rgba(61, 175, 185, 0.55); }
.rw-chat-send:disabled { opacity: 0.5; cursor: not-allowed; }
.rw-chat-send svg { width: 18px; height: 18px; transform: scaleX(-1); }
[dir="rtl"] .rw-chat-send svg { transform: none; }
.rw-chat-send-spinner {
    width: 16px; height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: rw-chat-spin 700ms linear infinite;
}
@keyframes rw-chat-spin { to { transform: rotate(360deg); } }

/* Loading */
.rw-chat-loading {
    flex: 1;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 12px;
    color: #64748B;
}
.rw-chat-spinner {
    width: 32px; height: 32px;
    border: 3px solid rgba(61, 175, 185, 0.15);
    border-top-color: var(--brand-teal);
    border-radius: 50%;
    animation: rw-chat-spin 700ms linear infinite;
}
.rw-chat-loading-text { font-size: 12.5px; }

/* Guest */
.rw-chat-guest {
    flex: 1;
    padding: 28px 24px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 14px;
    text-align: center;
}
.rw-chat-guest-icon {
    width: 64px; height: 64px;
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(61, 175, 185, 0.12), rgba(45, 75, 126, 0.06));
    color: var(--brand-teal);
    display: inline-flex; align-items: center; justify-content: center;
}
.rw-chat-guest-icon svg { width: 32px; height: 32px; }
.rw-chat-guest-title { font-size: 17px; font-weight: 900; color: #1E293B; }
.dark .rw-chat-guest-title { color: #F1F5F9; }
.rw-chat-guest-sub { font-size: 12.5px; color: #64748B; line-height: 1.75; max-width: 260px; }
.rw-chat-guest-actions { display: flex; gap: 8px; margin-top: 6px; }
.rw-chat-btn {
    padding: 10px 20px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 800;
    text-decoration: none;
    transition: all 200ms;
}
.rw-chat-btn-primary {
    background: linear-gradient(135deg, var(--brand-navy), var(--brand-teal));
    color: white;
    box-shadow: 0 6px 16px -4px rgba(61, 175, 185, 0.45);
}
.rw-chat-btn-primary:hover { transform: translateY(-2px); }
.rw-chat-btn-ghost {
    background: transparent;
    color: var(--brand-teal);
    border: 1px solid rgba(61, 175, 185, 0.3);
}
.rw-chat-btn-ghost:hover { border-color: var(--brand-teal); background: rgba(61, 175, 185, 0.05); }

/* Mobile refinements */
@media (max-width: 640px) {
    .rw-chat { bottom: 12px; inset-inline-end: 12px; }
    .rw-chat-fab-tooltip { display: none; }
    .rw-chat-panel {
        width: calc(100vw - 24px);
        height: calc(100vh - 80px);
        border-radius: 20px;
    }
}
</style>
