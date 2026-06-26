<script setup>
import { computed } from 'vue';

const props = defineProps({
    series: {
        type: Array,
        default: () => [],
    },
});

const width = 140;
const height = 44;
const padding = 4;

const points = computed(() => {
    if (props.series.length === 0) {
        return '';
    }

    if (props.series.length === 1) {
        return `${padding},${height / 2} ${width - padding},${height / 2}`;
    }

    const max = Math.max(...props.series);
    const min = Math.min(...props.series);
    const range = Math.max(max - min, 1);

    return props.series.map((value, index) => {
        const x = padding + ((width - padding * 2) / (props.series.length - 1)) * index;
        const y = height - padding - (((value - min) / range) * (height - padding * 2));

        return `${x},${y}`;
    }).join(' ');
});
</script>

<template>
    <svg :viewBox="`0 0 ${width} ${height}`" class="h-11 w-full">
        <polyline
            fill="none"
            stroke="currentColor"
            stroke-width="3"
            stroke-linecap="round"
            stroke-linejoin="round"
            :points="points"
        />
    </svg>
</template>
