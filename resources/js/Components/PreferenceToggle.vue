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
    checked: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:checked']);
</script>

<template>
    <label :for="id" class="flex items-center justify-between gap-4 rounded-3xl border border-stone-200 px-4 py-3">
        <span class="text-sm text-slate-700">{{ label }}</span>
        <input
            :id="id"
            :checked="checked"
            type="checkbox"
            class="h-5 w-5 rounded border-stone-300 text-emerald-700 focus:ring-emerald-600"
            @input="emit('update:checked', $event.target.checked)"
        >
    </label>
</template>
