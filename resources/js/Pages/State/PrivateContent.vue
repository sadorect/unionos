<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="This content is private" />
        <div class="form-shell">
            <StateCard
                title="This content is private"
                description="Some parts of the app are only visible to the person who created them."
                primary-label="Return to my progress"
                :primary-url="route('dashboard.personal')"
                secondary-label="Go home"
                :secondary-url="route('home')"
            />
        </div>
    </CoupleLayout>
</template>
