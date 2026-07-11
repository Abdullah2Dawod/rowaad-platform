<template>
    <label :class="['group relative flex items-center gap-3 rounded-2xl border-2 border-dashed cursor-pointer transition-all',
                    compact ? 'px-4 py-3' : 'px-5 py-4',
                    hasFile ? 'border-[#3DAFB9] bg-[#3DAFB9]/5' : 'border-soft hover:border-[#3DAFB9]/40 bg-canvas']">
        <div :class="['flex items-center justify-center rounded-xl shrink-0 transition-colors',
                      compact ? 'w-9 h-9' : 'w-11 h-11',
                      hasFile ? 'bg-[#3DAFB9]/15 text-[#3DAFB9]' : 'bg-elevated text-ink-muted group-hover:text-[#3DAFB9] group-hover:bg-[#3DAFB9]/10']">
            <svg v-if="hasFile" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12"/>
            </svg>
        </div>

        <div class="flex-1 min-w-0">
            <div class="text-[13px] font-bold text-ink truncate">
                <template v-if="file">{{ file.name }}</template>
                <template v-else-if="currentName">{{ currentName }} <span class="text-[10px] text-ink-muted mr-1">(محفوظ)</span></template>
                <template v-else>{{ hintText || 'اختر ملفاً' }}</template>
            </div>
            <div class="text-[10.5px] text-ink-muted mt-0.5">
                <template v-if="file">{{ formatSize(file.size) }} — سيُرفع عند الحفظ</template>
                <template v-else-if="currentName">اضغط لاستبداله</template>
                <template v-else>{{ acceptLabel }}</template>
            </div>
        </div>

        <span v-if="hasFile" class="text-[10.5px] text-[#3DAFB9] font-bold">تغيير</span>

        <input type="file" :accept="accept" class="sr-only" @change="onPick" />
    </label>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    accept:      { type: String, default: '*/*' },
    currentName: { type: String, default: null },
    file:        { type: Object, default: null },
    hintText:    { type: String, default: null },
    compact:     { type: Boolean, default: false },
});
const emit = defineEmits(['change']);

const hasFile     = computed(() => !!props.file || !!props.currentName);
const acceptLabel = computed(() => props.accept.replace(/\./g, '').toUpperCase().replace(/,/g, ' · '));

const onPick = (e) => emit('change', e.target.files?.[0] || null);

const formatSize = (bytes) => {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
};
</script>
