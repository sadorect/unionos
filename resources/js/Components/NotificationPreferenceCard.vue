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
    <section class="surface-card space-y-5 p-6 sm:p-8">
        <div>
            <h2 class="text-2xl text-slate-900">{{ title }}</h2>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">{{ description }}</p>
        </div>
        <slot />
    </section>
</template>
