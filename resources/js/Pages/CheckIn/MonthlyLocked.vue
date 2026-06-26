<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import MonthLabel from '@/Components/MonthLabel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import StatusPill from '@/Components/StatusPill.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

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
            <PageHeader
                title="This month's check-in is closed"
                description="New entries can't be added for this period."
            />

            <CardPanel>
                <div class="flex flex-wrap items-center gap-3">
                    <MonthLabel :value="month.label" />
                    <StatusPill value="locked" />
                </div>

                <p class="mt-5 text-sm leading-6 text-slate-700">
                    Check-in periods close automatically.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <Link v-if="hasInsight" :href="route('checkins.monthly.insight')">
                        <PrimaryButton type="button">View latest insight</PrimaryButton>
                    </Link>
                    <Link :href="route('home')">
                        <PrimaryButton type="button">Return home</PrimaryButton>
                    </Link>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
