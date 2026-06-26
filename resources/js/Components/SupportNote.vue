<script setup>
defineProps({
    text: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <p v-if="text" class="text-sm leading-6 text-slate-500">
        {{ text }}
    </p>
</template>
