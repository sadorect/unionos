<script setup>
import FailureStateCard from '@/Components/FailureStateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    retryUrl: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="Your changes may not have been saved" />

        <div class="form-shell">
            <FailureStateCard
                title="Your changes may not have been saved"
                description="Please review and try again."
                primary-label="Try again"
                :primary-url="retryUrl"
                secondary-label="Back"
                :secondary-url="route('home')"
            />
        </div>
    </CoupleLayout>
</template>
