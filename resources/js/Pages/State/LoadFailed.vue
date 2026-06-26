<script setup>
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import FailureStateCard from '@/Components/FailureStateCard.vue';
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
        <Head title="We couldn't load this page" />

        <div class="form-shell">
            <FailureStateCard
                title="We couldn't load this page"
                description="Please try again."
                primary-label="Reload"
                :primary-url="retryUrl"
                secondary-label="Return home"
                :secondary-url="route('home')"
            />
        </div>
    </CoupleLayout>
</template>
