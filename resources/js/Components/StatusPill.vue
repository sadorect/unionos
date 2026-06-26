<script setup>
const props = defineProps({
    value: {
        type: String,
        required: true,
    },
});

const classes = {
    open: 'border-emerald-200 bg-emerald-50 text-emerald-800',
    saved: 'border-emerald-200 bg-emerald-50 text-emerald-800',
    complete: 'border-stone-300 bg-stone-100 text-slate-700',
    locked: 'border-amber-200 bg-amber-50 text-amber-800',
    editing: 'border-sky-200 bg-sky-50 text-sky-800',
    rising: 'border-sky-200 bg-sky-50 text-sky-800',
    stable: 'border-stone-300 bg-stone-100 text-slate-700',
    mixed: 'border-stone-300 bg-stone-100 text-slate-700',
    waiting: 'border-sky-200 bg-sky-50 text-sky-800',
    paused: 'border-amber-200 bg-amber-50 text-amber-800',
    complete: 'border-stone-300 bg-stone-100 text-slate-700',
    'needs attention': 'border-stone-300 bg-stone-100 text-slate-700',
};
</script>

<template>
    <span
        class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em]"
        :class="classes[value] ?? classes.complete"
    >
        {{ value }}
    </span>
</template>
