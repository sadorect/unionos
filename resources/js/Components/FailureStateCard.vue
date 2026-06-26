<script setup>
import StateCard from '@/Components/StateCard.vue';

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
    <StateCard
        :title="title"
        :description="description"
        :primary-label="primaryLabel"
        :primary-url="primaryUrl"
        :secondary-label="secondaryLabel"
        :secondary-url="secondaryUrl"
    >
        <slot />
    </StateCard>
</template>
