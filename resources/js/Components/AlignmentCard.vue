<script setup>
import AlignmentBadge from '@/Components/AlignmentBadge.vue';

defineProps({
    label: {
        type: String,
        required: true,
    },
    state: {
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
    <div class="surface-muted p-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="text-xl text-slate-900">{{ label }}</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ description }}</p>
            </div>
            <AlignmentBadge :state="state" />
        </div>
    </div>
</template>
