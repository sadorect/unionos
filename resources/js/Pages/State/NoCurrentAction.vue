<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="Nothing to do here right now" />
        <div class="form-shell">
            <StateCard
                title="Nothing to do here right now"
                description="This area will update when a new action becomes available."
                primary-label="Return home"
                :primary-url="route('home')"
            />
        </div>
    </CoupleLayout>
</template>
