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
        <Head title="We couldn't complete that action" />

        <div class="form-shell">
            <FailureStateCard
                title="We couldn't complete that action"
                description="Please try again in a moment."
                primary-label="Try again"
                :primary-url="retryUrl"
                secondary-label="Return home"
                :secondary-url="route('home')"
            >
                <p class="mt-4 text-sm text-slate-500">Your existing saved information is still protected.</p>
            </FailureStateCard>
        </div>
    </CoupleLayout>
</template>
