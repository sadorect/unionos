<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { router } from '@inertiajs/vue3';

defineProps({
    message: {
        type: String,
        required: true,
    },
    retryUrl: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="flex flex-col gap-3 rounded-3xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 sm:flex-row sm:items-center sm:justify-between">
        <span>{{ message }}</span>
        <PrimaryButton type="button" @click="retryUrl ? router.visit(retryUrl) : window.location.reload()">
            Try again
        </PrimaryButton>
    </div>
</template>
