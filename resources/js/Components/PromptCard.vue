<script setup>
defineProps({
    prompt: {
        type: String,
        required: true,
    },
    instruction: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="surface-card p-6 sm:p-8">
        <h3 class="text-2xl leading-9 text-slate-900">{{ prompt }}</h3>
        <p v-if="instruction" class="mt-3 text-sm leading-6 text-slate-600">{{ instruction }}</p>
    </div>
</template>
