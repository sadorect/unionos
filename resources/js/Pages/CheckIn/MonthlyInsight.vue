<script setup>
import AlignmentCard from '@/Components/AlignmentCard.vue';
import CardPanel from '@/Components/CardPanel.vue';
import InsightIntroCard from '@/Components/InsightIntroCard.vue';
import MonthLabel from '@/Components/MonthLabel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    month: {
        type: Object,
        required: true,
    },
    cards: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="Monthly alignment" />

        <div class="page-shell space-y-6">
            <PageHeader
                title="Monthly alignment"
                description="A shared view of how you're feeling this month."
            />

            <div class="flex flex-wrap items-center gap-3">
                <MonthLabel :value="month.label" />
            </div>

            <InsightIntroCard
                message="These insights highlight where you feel aligned and where a conversation may help."
            />

            <section class="grid gap-4 md:grid-cols-2">
                <AlignmentCard
                    v-for="card in cards"
                    :key="card.label"
                    :label="card.label"
                    :state="card.state"
                    :description="card.description"
                />
            </section>

            <CardPanel>
                <h2 class="text-2xl text-slate-900">Want to talk about this together?</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    A guided conversation can help you explore these areas calmly.
                </p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <Link :href="route('conversations.index')">
                        <PrimaryButton type="button">Start guided conversation</PrimaryButton>
                    </Link>
                    <Link :href="route('home')">
                        <PrimaryButton type="button">Maybe later</PrimaryButton>
                    </Link>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
