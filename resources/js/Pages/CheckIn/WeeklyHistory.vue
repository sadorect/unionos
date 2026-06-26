<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import HistoryItemCard from '@/Components/HistoryItemCard.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PrivacyNote from '@/Components/PrivacyNote.vue';
import TrendCard from '@/Components/TrendCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    trendCards: {
        type: Array,
        default: () => [],
    },
    history: {
        type: Object,
        required: true,
    },
    hasCurrentWeek: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="My weekly history" />

        <div class="page-shell space-y-6">
            <PageHeader
                title="My weekly history"
                description="A private record of your weekly reflections."
            />

            <PrivacyNote message="Only you can view this page." />

            <section class="grid gap-4 lg:grid-cols-3">
                <TrendCard
                    v-for="card in trendCards"
                    :key="card.label"
                    :label="card.label"
                    :trend="card.trend"
                    :series="card.series"
                />
            </section>

            <section v-if="history.data.length" class="space-y-4">
                <HistoryItemCard
                    v-for="item in history.data"
                    :key="item.id"
                    :item="item"
                />

                <div v-if="history.links?.length" class="surface-card flex flex-wrap gap-2 p-4">
                    <template v-for="link in history.links" :key="`${link.label}-${link.url}`">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="rounded-full border px-3 py-2 text-sm transition"
                            :class="link.active
                                ? 'border-emerald-700 bg-emerald-800 text-white'
                                : 'border-stone-300 bg-white text-slate-600'"
                            :preserve-scroll="true"
                            :preserve-state="true"
                        >
                            <span v-html="link.label" />
                        </Link>
                        <span
                            v-else
                            class="rounded-full border border-stone-200 bg-stone-50 px-3 py-2 text-sm text-stone-400"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </section>

            <CardPanel v-else>
                <h2 class="text-2xl text-slate-900">No check-ins yet</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Your weekly reflections will appear here after you complete your first check-in.
                </p>
                <div class="mt-6">
                    <Link :href="route('checkins.weekly.show')">
                        <PrimaryButton type="button">Start this week's check-in</PrimaryButton>
                    </Link>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
