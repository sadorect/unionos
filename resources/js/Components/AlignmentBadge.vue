<script setup>
const props = defineProps({
    state: {
        type: String,
        required: true,
    },
});

const classes = {
    aligned: 'border-emerald-200 bg-emerald-50 text-emerald-800',
    'needs conversation': 'border-stone-300 bg-stone-100 text-slate-700',
};
</script>

<template>
    <span
        class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em]"
        :class="classes[props.state] ?? classes.aligned"
    >
        {{ props.state }}
    </span>
</template>
