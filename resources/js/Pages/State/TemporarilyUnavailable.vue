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
        <Head title="This feature isn't available right now" />

        <div class="form-shell">
            <FailureStateCard
                title="This feature isn't available right now"
                description="Please try again a little later."
                primary-label="Return home"
                :primary-url="route('home')"
                secondary-label="Try again"
                :secondary-url="retryUrl"
            />
        </div>
    </CoupleLayout>
</template>
