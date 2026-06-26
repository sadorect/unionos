<script setup>
const model = defineModel({
    type: Number,
    default: null,
});

defineProps({
    name: {
        type: String,
        required: true,
    },
    minLabel: {
        type: String,
        default: 'Very low',
    },
    midLabel: {
        type: String,
        default: 'Moderate',
    },
    maxLabel: {
        type: String,
        default: 'Very high',
    },
});

const values = [1, 2, 3, 4, 5];
</script>

<template>
    <div class="space-y-3">
        <div class="grid grid-cols-5 gap-2">
            <button
                v-for="value in values"
                :key="value"
                :name="name"
                type="button"
                class="rounded-[20px] border px-3 py-3 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-emerald-200"
                :class="model === value
                    ? 'border-emerald-700 bg-emerald-800 text-white'
                    : 'border-stone-300 bg-stone-50 text-slate-700 hover:border-stone-400 hover:bg-stone-100'"
                @click="model = value"
            >
                {{ value }}
            </button>
        </div>

        <div class="grid grid-cols-3 gap-3 text-xs text-slate-500">
            <div>{{ minLabel }}</div>
            <div class="text-center">{{ midLabel }}</div>
            <div class="text-right">{{ maxLabel }}</div>
        </div>
    </div>
</template>
