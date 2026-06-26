<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="You can't join this invitation" />
        <div class="form-shell">
            <StateCard
                title="You can't join this invitation"
                description="Your account is already linked to another couple space."
                primary-label="Return to my dashboard"
                :primary-url="route('dashboard.couple')"
            />
        </div>
    </CoupleLayout>
</template>
