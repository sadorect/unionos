<script setup>
const props = defineProps({
    state: {
        type: String,
        required: true,
    },
});

const labels = {
    your_turn: 'Your turn',
    partner_turn: 'Your partner is speaking',
};
</script>

<template>
    <div
        class="rounded-[24px] border px-4 py-4 text-sm font-semibold"
        :class="props.state === 'your_turn'
            ? 'border-emerald-200 bg-emerald-50 text-emerald-900'
            : 'border-stone-300 bg-stone-100 text-slate-700'"
    >
        {{ labels[props.state] ?? props.state }}
    </div>
</template>
