<script setup>
defineProps({
    message: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="surface-card p-6 sm:p-8">
        <p class="text-base leading-7 text-slate-700">{{ message }}</p>
    </div>
</template>
