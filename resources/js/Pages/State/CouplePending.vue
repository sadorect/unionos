<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="Your couple space is still being set up" />
        <div class="form-shell">
            <StateCard
                title="Your couple space is still being set up"
                description="Shared features will become available once your partner joins."
                primary-label="Go to couple setup"
                :primary-url="route('couple.pending')"
                secondary-label="Return home"
                :secondary-url="route('home')"
            >
                <p class="mt-4 text-sm text-slate-500">
                    You can send or manage an invitation from your setup page.
                </p>
            </StateCard>
        </div>
    </CoupleLayout>
</template>
