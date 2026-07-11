<template>
    <teleport to="body">
        <transition
            enter-active-class="pm-enter"
            leave-active-class="pm-leave"
        >
            <div v-if="modelValue"
                 @click.self="$emit('update:modelValue', false)"
                 class="pm-backdrop">
                <transition
                    enter-active-class="pm-card-enter"
                    leave-active-class="pm-card-leave"
                    appear
                >
                    <div v-if="modelValue" class="pm-card" :style="{ maxWidth }">
                        <!-- Decorative gradient bar -->
                        <div class="pm-bar"></div>

                        <!-- Close -->
                        <button type="button" @click="$emit('update:modelValue', false)" class="pm-close" aria-label="إغلاق">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <!-- Header -->
                        <div class="pm-header">
                            <div v-if="eyebrow" class="pm-eyebrow">{{ eyebrow }}</div>
                            <h3 v-if="title" class="pm-title">{{ title }}</h3>
                            <p v-if="subtitle" class="pm-subtitle">{{ subtitle }}</p>
                        </div>

                        <!-- Body -->
                        <div class="pm-body">
                            <slot />
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>

<script setup>
defineProps({
    modelValue: { type: Boolean, default: false },
    title:      { type: String, default: null },
    subtitle:   { type: String, default: null },
    eyebrow:    { type: String, default: null },
    maxWidth:   { type: String, default: '780px' }, // wide by default (landscape)
});
defineEmits(['update:modelValue']);
</script>

<style scoped>
.pm-backdrop {
    position: fixed;
    inset: 0;
    z-index: 90;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    background: rgba(10, 23, 41, 0.72);
    backdrop-filter: blur(10px) saturate(120%);
    -webkit-backdrop-filter: blur(10px) saturate(120%);
}
.pm-enter {
    animation: pmBackdropIn 260ms ease-out both;
}
.pm-leave {
    animation: pmBackdropOut 200ms ease-in both;
}

.pm-card {
    position: relative;
    width: 100%;
    max-height: 92vh;
    overflow-y: auto;
    background: var(--bg-elevated);
    border: 1px solid var(--border-soft);
    border-radius: 24px;
    box-shadow:
        0 30px 60px -20px rgba(10, 23, 41, 0.55),
        0 12px 32px -8px rgba(61, 175, 185, 0.18),
        0 0 0 1px rgba(255, 255, 255, 0.03) inset;
}
.pm-card-enter {
    animation: pmCardIn 380ms cubic-bezier(0.34, 1.4, 0.5, 1) both;
}
.pm-card-leave {
    animation: pmCardOut 220ms ease-in both;
}

/* Gradient top bar */
.pm-bar {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(
        90deg,
        #2D4B7E 0%,
        #3DAFB9 50%,
        #6BC8D2 100%
    );
    border-top-left-radius: 24px;
    border-top-right-radius: 24px;
}

/* Close */
.pm-close {
    position: absolute;
    top: 16px;
    inset-inline-end: 16px;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-canvas);
    border: 1px solid var(--border-soft);
    color: var(--ink-body);
    border-radius: 999px;
    cursor: pointer;
    transition: all 240ms cubic-bezier(0.22, 1, 0.36, 1);
    z-index: 2;
}
.pm-close:hover {
    background: rgba(239, 68, 68, 0.08);
    color: #DC2626;
    border-color: rgba(239, 68, 68, 0.3);
    transform: rotate(90deg);
}

/* Header */
.pm-header {
    padding: 34px 40px 20px;
    text-align: start;
    border-bottom: 1px solid var(--border-soft);
}
.pm-eyebrow {
    display: inline-block;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: #3DAFB9;
    padding: 4px 12px;
    background: rgba(61, 175, 185, 0.10);
    border: 1px solid rgba(61, 175, 185, 0.25);
    border-radius: 999px;
    margin-bottom: 12px;
}
.pm-title {
    font-size: 22px;
    font-weight: 900;
    color: var(--ink-strong, #2D4B7E);
    line-height: 1.2;
    margin: 0 0 6px;
    letter-spacing: -0.015em;
}
.pm-subtitle {
    font-size: 13.5px;
    color: var(--ink-body);
    margin: 0;
    line-height: 1.6;
}

/* Body */
.pm-body {
    padding: 28px 40px 34px;
}

@media (max-width: 640px) {
    .pm-header { padding: 28px 24px 18px; }
    .pm-body   { padding: 22px 24px 26px; }
    .pm-title  { font-size: 19px; }
}

/* ─────── Animations ─────── */
@keyframes pmBackdropIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes pmBackdropOut {
    to { opacity: 0; }
}
@keyframes pmCardIn {
    from {
        opacity: 0;
        transform: translateY(24px) scale(0.94);
        filter: blur(4px);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0);
    }
}
@keyframes pmCardOut {
    to {
        opacity: 0;
        transform: translateY(12px) scale(0.96);
    }
}
</style>
