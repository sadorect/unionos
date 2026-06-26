<script setup>
import StatusPill from '@/Components/StatusPill.vue';

defineProps({
    monthLabel: {
        type: String,
        required: true,
    },
    status: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="surface-muted flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-base text-slate-900">{{ monthLabel }}</p>
        <StatusPill :value="status.toLowerCase()" />
    </div>
</template>
