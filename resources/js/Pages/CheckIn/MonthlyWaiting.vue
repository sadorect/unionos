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
});
</script>

<template>
    <CoupleLayout>
        <Head title="Check-in saved" />

        <div class="form-shell space-y-6">
            <PageHeader
                title="Check-in saved"
                description="Shared insight will appear once both of you complete the check-in."
            />

            <CardPanel>
                <div class="flex flex-wrap items-center gap-3">
                    <MonthLabel :value="month.label" />
                    <StatusPill value="waiting" />
                </div>

                <p class="mt-5 text-sm leading-6 text-slate-700">
                    Nothing is shared until both submissions are complete.
                </p>

                <div class="mt-8">
                    <Link :href="route('home')">
                        <PrimaryButton type="button">Return home</PrimaryButton>
                    </Link>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
