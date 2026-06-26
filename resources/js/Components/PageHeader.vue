<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="space-y-3">
        <h1 class="text-3xl text-slate-900 sm:text-[2.35rem]">{{ title }}</h1>
        <p v-if="description" class="max-w-2xl text-base leading-7 text-slate-600">
            {{ description }}
        </p>
    </div>
</template>
