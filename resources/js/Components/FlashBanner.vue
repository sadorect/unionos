<script setup>
defineProps({
    type: {
        type: String,
        default: 'success',
    },
    message: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div
        v-if="message"
        class="rounded-2xl border px-4 py-3 text-sm"
        :class="type === 'error'
            ? 'border-rose-200 bg-rose-50 text-rose-800'
            : 'border-emerald-200 bg-emerald-50 text-emerald-800'"
    >
        {{ message }}
    </div>
</template>
