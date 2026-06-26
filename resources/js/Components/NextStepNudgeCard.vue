<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    actionLabel: {
        type: String,
        required: true,
    },
    actionUrl: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="surface-card border-l-4 border-l-emerald-700 p-6 sm:p-8">
        <h2 class="text-2xl text-slate-900">{{ title }}</h2>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">{{ description }}</p>
        <div class="mt-6">
            <Link :href="actionUrl">
                <PrimaryButton type="button">{{ actionLabel }}</PrimaryButton>
            </Link>
        </div>
    </div>
</template>
