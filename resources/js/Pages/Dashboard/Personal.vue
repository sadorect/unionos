<script setup>
import DashboardEmptyState from '@/Components/DashboardEmptyState.vue';
import OverviewStatCard from '@/Components/OverviewStatCard.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrivacyNote from '@/Components/PrivacyNote.vue';
import QuietModeNotice from '@/Components/QuietModeNotice.vue';
import TrendCard from '@/Components/TrendCard.vue';
import CardPanel from '@/Components/CardPanel.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    overviewCards: {
        type: Array,
        default: () => [],
    },
    trendCards: {
        type: Array,
        default: () => [],
    },
    historyPreview: {
        type: Array,
        default: () => [],
    },
    conversationActivity: {
        type: Array,
        default: () => [],
    },
    isEmpty: {
        type: Boolean,
        default: false,
    },
    nextStep: {
        type: Object,
        default: null,
    },
    notificationsPaused: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="My progress" />

        <div class="page-shell space-y-6">
            <PageHeader
                title="My progress"
                description="A private view of your reflection patterns over time."
            />

            <PrivacyNote message="Only you can see this page." />
            <QuietModeNotice v-if="notificationsPaused" />

            <DashboardEmptyState
                v-if="isEmpty"
                title="Nothing here yet"
                description="Your private reflection patterns will appear here as you begin using the app."
                action-label="Start weekly check-in"
                :action-url="route('checkins.weekly.show')"
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

                <section class="grid gap-4 lg:grid-cols-3">
                    <TrendCard
                        v-for="card in trendCards"
                        :key="card.label"
                        :label="card.label"
                        :trend="card.trend"
                        :series="card.series"
                    />
                </section>

                <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                    <CardPanel>
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-2xl text-slate-900">Recent weekly history</h2>
                            <Link :href="route('checkins.weekly.history')" class="text-sm text-slate-600 underline decoration-stone-300 underline-offset-4">
                                View full weekly history
                            </Link>
                        </div>

                        <div class="mt-5 space-y-3">
                            <div
                                v-for="item in historyPreview"
                                :key="item.id"
                                class="surface-muted p-4"
                            >
                                <p class="text-base text-slate-900">{{ item.weekLabel }}</p>
                                <p v-if="item.savedAt" class="mt-1 text-sm text-slate-500">
                                    Saved {{ new Date(item.savedAt).toLocaleDateString() }}
                                </p>
                            </div>
                        </div>
                    </CardPanel>

                    <CardPanel>
                        <h2 class="text-2xl text-slate-900">Conversation activity</h2>
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
            </template>
        </div>
    </CoupleLayout>
</template>
