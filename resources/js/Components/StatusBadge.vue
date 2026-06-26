<script setup>
const props = defineProps({
    status: {
        type: String,
        required: true,
    },
});

const classes = {
    pending: 'border-amber-200 bg-amber-50 text-amber-800',
    active: 'border-emerald-200 bg-emerald-50 text-emerald-800',
    dissolved: 'border-stone-300 bg-stone-100 text-stone-700',
    sent: 'border-sky-200 bg-sky-50 text-sky-800',
    expired: 'border-rose-200 bg-rose-50 text-rose-800',
};

const labels = {
    pending: 'Pending',
    active: 'Active',
    dissolved: 'Dissolved',
    sent: 'Sent',
    expired: 'Expired',
};
</script>

<template>
    <span
        class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]"
        :class="classes[props.status] ?? classes.pending"
    >
        {{ labels[props.status] ?? props.status }}
    </span>
</template>
