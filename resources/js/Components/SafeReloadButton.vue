<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    label: {
        type: String,
        default: 'Reload',
    },
});
</script>

<template>
    <PrimaryButton type="button" @click="window.location.reload()">
        {{ label }}
    </PrimaryButton>
</template>
