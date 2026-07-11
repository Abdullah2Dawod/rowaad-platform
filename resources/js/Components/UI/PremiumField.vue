<template>
    <div class="premium-field" :class="{ 'has-error': error, 'is-focused': focused, 'has-value': !!modelValue }">
        <!-- Label -->
        <label v-if="label" class="pf-label">
            {{ label }}
            <span v-if="required" class="pf-required">*</span>
            <span v-if="hint" class="pf-hint">{{ hint }}</span>
        </label>

        <!-- Input wrapper -->
        <div class="pf-wrap">
            <!-- Leading icon area (fixed slot on the reading-start side) -->
            <span v-if="icon" class="pf-icon-slot" aria-hidden="true">
                <img :src="iconUrl" alt="" draggable="false" />
            </span>

            <!-- Prefix text (e.g. currency) -->
            <span v-if="prefix" class="pf-prefix">{{ prefix }}</span>

            <!-- ==== TEXTAREA ==== -->
            <textarea
                v-if="type === 'textarea'"
                v-bind="$attrs"
                :value="modelValue"
                :placeholder="placeholder"
                :rows="rows"
                :maxlength="maxlength"
                :required="required"
                @input="$emit('update:modelValue', $event.target.value)"
                @focus="focused = true"
                @blur="focused = false"
                class="pf-input pf-textarea"
                :class="{ 'has-icon': icon }"
            />

            <!-- ==== SELECT ==== -->
            <select
                v-else-if="type === 'select'"
                v-bind="$attrs"
                :value="modelValue"
                :required="required"
                @change="$emit('update:modelValue', $event.target.value)"
                @focus="focused = true"
                @blur="focused = false"
                class="pf-input pf-select"
                :class="{ 'has-icon': icon }"
            >
                <slot />
            </select>

            <!-- ==== INPUT ==== -->
            <input
                v-else
                v-bind="$attrs"
                :type="type"
                :value="modelValue"
                :placeholder="placeholder"
                :dir="dir"
                :maxlength="maxlength"
                :required="required"
                @input="$emit('update:modelValue', $event.target.value)"
                @focus="focused = true"
                @blur="focused = false"
                class="pf-input"
                :class="{ 'has-icon': icon, 'has-prefix': prefix }"
            />

            <!-- Trailing slot -->
            <span v-if="$slots.trailing" class="pf-trailing">
                <slot name="trailing" />
            </span>

            <!-- Focus glow -->
            <span class="pf-glow"></span>
        </div>

        <!-- Counter -->
        <div v-if="showCounter" class="pf-counter">
            {{ (modelValue || '').length }} / {{ maxlength }}
        </div>

        <!-- Error message -->
        <transition
            enter-active-class="pf-error-enter"
            leave-active-class="pf-error-leave"
        >
            <div v-if="error" class="pf-error">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" d="M12 8v4m0 4h.01"/>
                </svg>
                <span>{{ error }}</span>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed, inject } from 'vue';
import { useTheme } from '@/composables/useTheme';

defineOptions({ inheritAttrs: false });

const props = defineProps({
    modelValue:  { type: [String, Number, null], default: '' },
    label:       { type: String, default: null },
    type:        { type: String, default: 'text' },
    placeholder: { type: String, default: null },
    icon:        { type: String, default: null }, // Solar icon slug (bold-duotone auto-applied)
    error:       { type: String, default: null },
    required:    { type: Boolean, default: false },
    hint:        { type: String, default: null },
    dir:         { type: String, default: null },
    maxlength:   { type: [Number, String], default: null },
    rows:        { type: Number, default: 4 },
    prefix:      { type: String, default: null },
    showCounter: { type: Boolean, default: false },
});
defineEmits(['update:modelValue']);

const focused = ref(false);
const { isDark } = useTheme();

/**
 * Icon URL — Solar bold-duotone icons via Iconify.
 * Recolored with brand teal → consistent monochrome look (no color chaos).
 * Accepts short slug like "user", "letter", "phone" — auto-appends "-bold-duotone".
 */
const iconUrl = computed(() => {
    if (!props.icon) return '';
    const slug = props.icon
        .replace(/_/g, '-')
        .toLowerCase()
        .replace(/-bold-duotone$/, ''); // strip if user passes it
    const color = isDark.value ? '6BC8D2' : '3DAFB9';
    return `https://api.iconify.design/solar:${slug}-bold-duotone.svg?color=%23${color}&width=48`;
});
</script>

<style scoped>
/* ═══════════════════════════════════════════════
   PREMIUM FIELD — v2 (icons on the inline-start,
   no overlap with placeholder, dark-mode aware)
   ═══════════════════════════════════════════════ */

.premium-field {
    display: block;
    position: relative;
}

/* Label */
.pf-label {
    display: flex;
    align-items: baseline;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--ink-strong, #1A2F50);
    margin-bottom: 8px;
    letter-spacing: -0.005em;
}
.pf-required {
    color: #EF4444;
    font-weight: 800;
}
.pf-hint {
    margin-inline-start: auto;
    color: var(--ink-muted);
    font-size: 10.5px;
    font-weight: 500;
}

/* Wrap */
.pf-wrap {
    position: relative;
    display: flex;
    align-items: stretch;
    background: var(--bg-canvas);
    border: 1.5px solid var(--border-soft);
    border-radius: 14px;
    transition: border-color 260ms cubic-bezier(0.22, 1, 0.36, 1),
                background 260ms ease,
                box-shadow 260ms ease,
                transform 260ms ease;
}

/* ── Icon slot — a real column at the reading-start side ── */
.pf-icon-slot {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    flex-shrink: 0;
    position: relative;
    border-inline-end: 1px solid var(--border-soft);
    background: linear-gradient(180deg,
        rgba(61, 175, 185, 0.04),
        rgba(45, 75, 126, 0.02));
    transition: all 320ms cubic-bezier(0.22, 1, 0.36, 1);
    pointer-events: none;
}
.pf-icon-slot img {
    width: 22px;
    height: 22px;
    object-fit: contain;
    transition: transform 320ms cubic-bezier(0.34, 1.5, 0.5, 1);
    filter: drop-shadow(0 2px 4px rgba(61, 175, 185, 0.20));
}
.is-focused .pf-icon-slot {
    background: linear-gradient(180deg,
        rgba(61, 175, 185, 0.12),
        rgba(45, 75, 126, 0.08));
    border-inline-end-color: rgba(61, 175, 185, 0.35);
}
.is-focused .pf-icon-slot img {
    transform: scale(1.08) rotate(-3deg);
}

/* Prefix (currency) */
.pf-prefix {
    display: flex;
    align-items: center;
    padding-inline: 12px;
    color: var(--ink-muted);
    font-weight: 800;
    font-size: 12px;
    letter-spacing: 0.05em;
    border-inline-end: 1px solid var(--border-soft);
    background: linear-gradient(180deg,
        rgba(61, 175, 185, 0.03),
        rgba(45, 75, 126, 0.02));
}

/* Input */
.pf-input {
    flex: 1;
    min-width: 0;
    height: 46px;
    padding: 0 14px;
    background: transparent;
    border: none;
    color: var(--ink-primary);
    font-size: 13.5px;
    font-family: inherit;
    font-weight: 500;
    outline: none;
    width: 100%;
    letter-spacing: -0.005em;
}
.pf-input::placeholder {
    color: var(--ink-muted);
    font-weight: 400;
    opacity: 0.75;
}

/* Textarea */
.pf-textarea {
    height: auto;
    padding: 12px 14px;
    resize: vertical;
    min-height: 90px;
    line-height: 1.75;
}
.pf-wrap:has(.pf-textarea) {
    align-items: flex-start;
}
.pf-wrap:has(.pf-textarea) .pf-icon-slot {
    align-self: stretch;
    align-items: flex-start;
    padding-top: 12px;
}

/* Select */
.pf-select {
    padding-inline-end: 40px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left 14px center;
    background-size: 14px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
}
[dir="rtl"] .pf-select {
    background-position: left 14px center;
}
/* Native dropdown options — respect dark mode */
.pf-select option {
    background: #FFFFFF;
    color: #1A2F50;
    font-weight: 500;
    padding: 8px 12px;
}

/* Trailing slot */
.pf-trailing {
    display: flex;
    align-items: center;
    padding-inline-end: 12px;
}

/* Focus glow */
.pf-glow {
    position: absolute;
    inset: 0;
    border-radius: 14px;
    pointer-events: none;
    opacity: 0;
    transition: opacity 320ms ease;
    background: radial-gradient(
        140% 100% at 50% 100%,
        rgba(61, 175, 185, 0.10),
        transparent 70%
    );
}
.is-focused .pf-glow {
    opacity: 1;
}

/* States */
.pf-wrap:hover {
    border-color: rgba(61, 175, 185, 0.35);
}
.is-focused .pf-wrap,
.premium-field.is-focused .pf-wrap {
    border-color: #3DAFB9;
    background: var(--bg-elevated);
    box-shadow: 0 0 0 4px rgba(61, 175, 185, 0.13),
                0 8px 24px -8px rgba(61, 175, 185, 0.25);
    transform: translateY(-1px);
}

/* Error state */
.has-error .pf-wrap {
    border-color: rgba(239, 68, 68, 0.5);
    background: rgba(239, 68, 68, 0.03);
}
.has-error.is-focused .pf-wrap {
    border-color: #EF4444;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.13);
}

/* Counter */
.pf-counter {
    margin-top: 5px;
    text-align: end;
    font-size: 10.5px;
    color: var(--ink-muted);
    font-weight: 600;
}

/* Error message */
.pf-error {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 6px;
    padding: 6px 10px;
    background: rgba(239, 68, 68, 0.08);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 8px;
    color: #DC2626;
    font-size: 11.5px;
    font-weight: 600;
    line-height: 1.5;
}
.pf-error-enter {
    animation: pfErrorIn 260ms cubic-bezier(0.34, 1.5, 0.5, 1) both;
}
.pf-error-leave {
    animation: pfErrorOut 180ms ease-in both;
}
@keyframes pfErrorIn {
    from { opacity: 0; transform: translateY(-4px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes pfErrorOut {
    to   { opacity: 0; transform: translateY(-2px); }
}
</style>
