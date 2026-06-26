<script setup>
defineProps({
    title: {
        type: String,
        default: 'Private by default',
    },
    message: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="rounded-[24px] border border-emerald-200/70 bg-emerald-50/70 px-4 py-4">
        <p class="text-sm font-semibold text-emerald-900">{{ title }}</p>
        <p class="mt-1 text-sm leading-6 text-emerald-900/80">{{ message }}</p>
    </div>
</template>
