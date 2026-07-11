<template>
    <label class="block">
        <span class="flex items-baseline justify-between mb-1.5">
            <span class="text-[12.5px] font-bold text-ink">
                {{ label }}
                <span v-if="required" class="text-red-500 mr-0.5">*</span>
            </span>
            <span v-if="hint" class="text-[10.5px] text-ink-muted">{{ hint }}</span>
        </span>
        <slot />
        <span v-if="error" class="mt-1 block text-[11.5px] text-red-500 font-medium">{{ error }}</span>
    </label>
</template>

<script setup>
defineProps({
    label:    { type: String, required: true },
    required: { type: Boolean, default: false },
    hint:     { type: String, default: null },
    error:    { type: String, default: null },
});
</script>
