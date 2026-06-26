<script setup>
import StateCard from '@/Components/StateCard.vue';
import WeekLabel from '@/Components/WeekLabel.vue';
import StatusPill from '@/Components/StatusPill.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    week: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="This week's check-in is closed" />
        <div class="form-shell space-y-6">
            <div class="flex flex-wrap items-center gap-3">
                <WeekLabel :value="week.label" />
                <StatusPill value="locked" />
            </div>
            <StateCard
                title="This week's check-in is closed"
                description="Weekly check-ins are only available during the current check-in window."
                primary-label="View my history"
                :primary-url="route('checkins.weekly.history')"
                secondary-label="Return home"
                :secondary-url="route('home')"
            >
                <p class="mt-4 text-sm text-slate-500">
                    The next check-in will appear when the new week begins.
                </p>
            </StateCard>
        </div>
    </CoupleLayout>
</template>
