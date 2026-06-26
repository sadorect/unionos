<script setup>
import SparklineChart from '@/Components/SparklineChart.vue';
import StatusPill from '@/Components/StatusPill.vue';

defineProps({
    label: {
        type: String,
        required: true,
    },
    trend: {
        type: String,
        required: true,
    },
    series: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="surface-muted p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h3 class="text-lg text-slate-900">{{ label }}</h3>
                <p class="mt-1 text-sm text-slate-500">Recent pattern for your own check-ins.</p>
            </div>
            <StatusPill :value="trend.toLowerCase()" />
        </div>

        <div class="mt-5 text-emerald-800/80">
            <SparklineChart :series="series" />
        </div>
    </div>
</template>
