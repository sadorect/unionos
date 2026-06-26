<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="Create your couple space first" />
        <div class="form-shell">
            <StateCard
                title="Create your couple space first"
                description="This feature becomes available after you set up a couple space."
                primary-label="Create couple space"
                :primary-url="route('couple.create')"
                secondary-label="Return home"
                :secondary-url="route('home')"
            />
        </div>
    </CoupleLayout>
</template>
