<script setup>
import StatusPill from '@/Components/StatusPill.vue';

defineProps({
    title: {
        type: String,
        required: true,
    },
    progressLabel: {
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
    <div class="surface-card flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-slate-400">{{ progressLabel }}</p>
            <h2 class="mt-2 text-2xl text-slate-900">{{ title }}</h2>
        </div>
        <StatusPill :value="status" />
    </div>
</template>
