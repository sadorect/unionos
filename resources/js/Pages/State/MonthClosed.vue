<script setup>
import StateCard from '@/Components/StateCard.vue';
import MonthLabel from '@/Components/MonthLabel.vue';
import StatusPill from '@/Components/StatusPill.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    month: {
        type: Object,
        required: true,
    },
    hasInsight: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="This month's check-in is closed" />
        <div class="form-shell space-y-6">
            <div class="flex flex-wrap items-center gap-3">
                <MonthLabel :value="month.label" />
                <StatusPill value="locked" />
            </div>
            <StateCard
                title="This month's check-in is closed"
                description="Monthly check-ins close automatically at the end of each period."
                :primary-label="hasInsight ? 'View latest insight' : 'Return home'"
                :primary-url="hasInsight ? route('checkins.monthly.insight') : route('home')"
                secondary-label="Return home"
                :secondary-url="route('home')"
            />
        </div>
    </CoupleLayout>
</template>
