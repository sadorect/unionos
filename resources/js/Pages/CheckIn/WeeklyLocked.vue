<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import StatusPill from '@/Components/StatusPill.vue';
import WeekLabel from '@/Components/WeekLabel.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    week: {
        type: Object,
        required: true,
    },
    state: {
        type: String,
        default: 'locked',
    },
});

const title = computed(() => props.state === 'complete'
    ? 'This week\'s check-in is already complete'
    : 'This check-in window has closed');

const description = computed(() => props.state === 'complete'
    ? 'You\'ve already submitted your check-in for this week.'
    : 'The current check-in period is no longer open.');
</script>

<template>
    <CoupleLayout>
        <Head title="Weekly check-in unavailable" />

        <div class="form-shell space-y-6">
            <PageHeader :title="title" :description="description" />

            <CardPanel>
                <div class="flex flex-wrap items-center gap-3">
                    <WeekLabel :value="week.label" />
                    <StatusPill :value="state" />
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <Link :href="route('checkins.weekly.history')">
                        <PrimaryButton type="button">View my history</PrimaryButton>
                    </Link>
                    <Link :href="route('home')">
                        <PrimaryButton type="button">Return home</PrimaryButton>
                    </Link>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
