<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="surface-muted p-6 text-center">
        <h3 class="text-xl text-slate-900">{{ title }}</h3>
        <p class="mt-3 text-sm leading-6 text-slate-600">
            {{ description }}
        </p>
        <div class="mt-5">
            <slot />
        </div>
    </div>
</template>
