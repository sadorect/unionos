<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
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
    primaryLabel: {
        type: String,
        required: true,
    },
    primaryUrl: {
        type: String,
        required: true,
    },
    secondaryLabel: {
        type: String,
        default: '',
    },
    secondaryUrl: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="surface-card p-6 sm:p-8">
        <h1 class="text-3xl text-slate-900">{{ title }}</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">{{ description }}</p>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <Link :href="primaryUrl">
                <PrimaryButton type="button">{{ primaryLabel }}</PrimaryButton>
            </Link>
            <Link v-if="secondaryLabel && secondaryUrl" :href="secondaryUrl">
                <SecondaryButton type="button">{{ secondaryLabel }}</SecondaryButton>
            </Link>
        </div>
        <slot />
    </div>
</template>
