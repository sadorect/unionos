<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import SupportNote from '@/Components/SupportNote.vue';

defineProps({
    step: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <CardPanel class="space-y-4">
        <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Current prompt</span>
        <h2 v-if="step.label" class="text-2xl text-slate-900">{{ step.label }}</h2>
        <p class="text-xl leading-8 text-slate-900">{{ step.promptText }}</p>
        <SupportNote :text="step.instruction" />
    </CardPanel>
</template>
