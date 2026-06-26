<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="This couple space is no longer active" />
        <div class="form-shell">
            <StateCard
                title="This couple space is no longer active"
                description="Shared features have been locked for this space."
                primary-label="Create new couple space"
                :primary-url="route('couple.create')"
                secondary-label="Return home"
                :secondary-url="route('home')"
            >
                <p class="mt-4 text-sm text-slate-500">
                    You can create a new couple space if needed.
                </p>
            </StateCard>
        </div>
    </CoupleLayout>
</template>
