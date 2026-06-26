<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="This history is protected" />
        <div class="form-shell">
            <StateCard
                title="This history is protected"
                description="Older records may remain visible in limited form, but new activity can't be created here."
                primary-label="Return home"
                :primary-url="route('home')"
            />
        </div>
    </CoupleLayout>
</template>
