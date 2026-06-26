<script setup>
const props = defineProps({
    state: {
        type: String,
        default: 'your_turn',
    },
    turnLabel: {
        type: String,
        default: '',
    },
});

const copy = {
    your_turn: 'Your turn',
    partner_turn: "Your partner's turn",
    both: 'Respond together',
};
</script>

<template>
    <div class="rounded-3xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-slate-700">
        <strong class="text-slate-900">{{ copy[props.state] ?? copy.your_turn }}</strong>
        <span v-if="props.turnLabel" class="ml-2 text-slate-500">{{ props.turnLabel }}</span>
    </div>
</template>
