<script setup>
import DashboardEmptyState from '@/Components/DashboardEmptyState.vue';
import MonthlyAlignmentRow from '@/Components/MonthlyAlignmentRow.vue';
import NextStepNudgeCard from '@/Components/NextStepNudgeCard.vue';
import OverviewStatCard from '@/Components/OverviewStatCard.vue';
import PageHeader from '@/Components/PageHeader.vue';
import CardPanel from '@/Components/CardPanel.vue';
import QuietModeNotice from '@/Components/QuietModeNotice.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    overviewCards: {
        type: Array,
        default: () => [],
    },
    alignmentRows: {
        type: Array,
        default: () => [],
    },
    conversationActivity: {
        type: Array,
        default: () => [],
    },
    nextStep: {
        type: Object,
        default: null,
    },
    isEmpty: {
        type: Boolean,
        default: false,
    },
    notificationsPaused: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="Our progress" />

        <div class="page-shell space-y-6">
            <PageHeader
                title="Our progress"
                description="A shared view of your recent patterns and connection points."
            />

            <DashboardEmptyState
                v-if="isEmpty"
                title="Your shared progress will appear here"
                description="Complete monthly check-ins or guided conversations to start building this view."
                action-label="Go to monthly check-in"
                :action-url="route('checkins.monthly.show')"
            />

            <template v-else>
                <section class="grid gap-4 lg:grid-cols-3">
                    <OverviewStatCard
                        v-for="card in overviewCards"
                        :key="card.label"
                        :label="card.label"
                        :value="card.value"
                    />
                </section>

                <section class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
                    <CardPanel>
                        <h2 class="text-2xl text-slate-900">Monthly alignment trend</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            Based on your shared monthly check-ins.
                        </p>
                        <div class="mt-5 space-y-3">
                            <MonthlyAlignmentRow
                                v-for="row in alignmentRows"
                                :key="row.monthLabel"
                                :month-label="row.monthLabel"
                                :status="row.status"
                            />
                        </div>
                    </CardPanel>

                    <CardPanel>
                        <h2 class="text-2xl text-slate-900">Guided conversation history</h2>
                        <div class="mt-5 space-y-3">
                            <div
                                v-for="item in conversationActivity"
                                :key="item.id"
                                class="surface-muted p-4"
                            >
                                <p class="text-base text-slate-900">{{ item.packTitle }}</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ item.status }}
                                </p>
                            </div>
                        </div>
                    </CardPanel>
                </section>

                <QuietModeNotice v-if="notificationsPaused" />

                <NextStepNudgeCard
                    v-else-if="nextStep"
                    :title="nextStep.title"
                    :description="nextStep.description"
                    :action-label="nextStep.actionLabel"
                    :action-url="nextStep.actionUrl"
                />
            </template>
        </div>
    </CoupleLayout>
</template>
