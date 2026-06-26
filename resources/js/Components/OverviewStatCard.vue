<script setup>
defineProps({
    label: {
        type: String,
        required: true,
    },
    value: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="surface-muted p-5">
        <p class="text-sm text-slate-500">{{ label }}</p>
        <p class="mt-3 text-2xl text-slate-900">{{ value }}</p>
    </div>
</template>
