<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="Reminders are paused" />

        <div class="form-shell">
            <StateCard
                title="Reminders are paused"
                description="You won't receive optional reminders until you turn them back on."
                primary-label="Manage preferences"
                :primary-url="route('settings.notifications.show')"
                secondary-label="Return home"
                :secondary-url="route('home')"
            />
        </div>
    </CoupleLayout>
</template>
