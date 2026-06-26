<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="This couple space is no longer active" />

        <div class="space-y-6">
            <PageHeader
                title="This couple space is no longer active"
                description="Shared features have been locked."
            />

            <CardPanel>
                <p class="text-sm leading-6 text-slate-700">
                    No new check-ins, insights, or conversations can be created in this space.
                </p>
                <p class="mt-3 text-sm leading-6 text-slate-700">
                    Historical data remains protected according to system rules.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <Link :href="route('couple.create')">
                        <PrimaryButton type="button">Create a new couple space</PrimaryButton>
                    </Link>
                    <Link :href="route('home')">
                        <SecondaryButton type="button">Return to home</SecondaryButton>
                    </Link>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
