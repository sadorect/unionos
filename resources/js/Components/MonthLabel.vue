<script setup>
defineProps({
    value: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="inline-flex items-center rounded-full border border-stone-300 bg-stone-50 px-3 py-1.5 text-sm text-slate-600">
        {{ value }}
    </div>
</template>
