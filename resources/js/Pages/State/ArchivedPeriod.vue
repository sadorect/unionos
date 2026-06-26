<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import StatusPill from '@/Components/StatusPill.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    label: {
        type: String,
        default: 'This period',
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="This period is archived" />
        <div class="form-shell space-y-6">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm text-slate-600">{{ label }}</span>
                <StatusPill value="locked" />
            </div>
            <StateCard
                title="This period is archived"
                description="You can view preserved records here, but no new changes can be made."
                primary-label="View history"
                :primary-url="route('dashboard.personal')"
                secondary-label="Return home"
                :secondary-url="route('home')"
            />
        </div>
    </CoupleLayout>
</template>
