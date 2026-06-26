<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="This item can't be used right now" />
        <div class="form-shell">
            <StateCard
                title="This item can't be used right now"
                description="Its current state doesn't allow this action."
                primary-label="Return home"
                :primary-url="route('home')"
                secondary-label="Go back"
                :secondary-url="route('dashboard')"
            />
        </div>
    </CoupleLayout>
</template>
