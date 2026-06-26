<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    pausedAt: {
        type: String,
        default: '',
    },
    lastCompletedStep: {
        type: Number,
        default: 0,
    },
});
</script>

<template>
    <div class="surface-card p-6 sm:p-8">
        <h2 class="text-2xl text-slate-900">{{ title }}</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">
            Last completed step: {{ lastCompletedStep }}
        </p>
        <p v-if="pausedAt" class="mt-2 text-sm text-slate-500">
            Paused {{ new Date(pausedAt).toLocaleString() }}
        </p>
    </div>
</template>
