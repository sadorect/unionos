<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import StatusPill from '@/Components/StatusPill.vue';
import WeekLabel from '@/Components/WeekLabel.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    week: {
        type: Object,
        required: true,
    },
    submittedAt: {
        type: String,
        default: '',
    },
    canEdit: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="Check-in saved" />

        <div class="form-shell space-y-6">
            <PageHeader
                title="Check-in saved"
                description="Thanks for taking a moment to reflect. Your responses are private."
            />

            <CardPanel>
                <div class="flex flex-wrap items-center gap-3">
                    <WeekLabel :value="week.label" />
                    <StatusPill value="saved" />
                </div>

                <p v-if="submittedAt" class="mt-5 text-sm text-slate-600">
                    Saved {{ new Date(submittedAt).toLocaleString() }}
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <Link :href="route('checkins.weekly.history')">
                        <PrimaryButton type="button">View my history</PrimaryButton>
                    </Link>
                    <Link :href="route('home')" class="inline-flex">
                        <PrimaryButton type="button">Return home</PrimaryButton>
                    </Link>
                    <Link v-if="canEdit" :href="route('checkins.weekly.show')" class="inline-flex">
                        <PrimaryButton type="button">Edit this check-in</PrimaryButton>
                    </Link>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
