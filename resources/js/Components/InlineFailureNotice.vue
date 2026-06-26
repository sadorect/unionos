<script setup>
defineProps({
    message: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="rounded-3xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        {{ message }}
    </div>
</template>
