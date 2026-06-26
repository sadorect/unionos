<script setup>
defineProps({
    id: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [Number, String],
        required: true,
    },
    options: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <label :for="id" class="space-y-2">
        <span class="block text-sm text-slate-700">{{ label }}</span>
        <select
            :id="id"
            class="w-full rounded-3xl border border-stone-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600"
            :value="modelValue"
            @change="emit('update:modelValue', $event.target.value)"
        >
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
    </label>
</template>
