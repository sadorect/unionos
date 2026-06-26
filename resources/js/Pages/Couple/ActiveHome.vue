<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import DangerButton from '@/Components/DangerButton.vue';
import NextStepNudgeCard from '@/Components/NextStepNudgeCard.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import QuietModeNotice from '@/Components/QuietModeNotice.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    linkedAt: {
        type: String,
        default: '',
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
        <Head title="Your couple space is active" />

        <div class="space-y-6">
            <PageHeader
                title="Your couple space is active"
                description="You're linked and ready to begin."
            />

            <CardPanel>
                <div class="flex flex-wrap items-center gap-3">
                    <StatusBadge status="active" />
                </div>
                <p class="mt-4 text-sm leading-6 text-slate-700">
                    Your partner has been linked to this private space.
                </p>
                <p v-if="linkedAt" class="mt-2 text-sm text-slate-500">
                    Linked on {{ linkedAt }}
                </p>
            </CardPanel>

            <QuietModeNotice v-if="notificationsPaused" />
            <NextStepNudgeCard
                v-else-if="nextStep"
                :title="nextStep.title"
                :description="nextStep.description"
                :action-label="nextStep.actionLabel"
                :action-url="nextStep.actionUrl"
            />

            <CardPanel>
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="text-2xl text-slate-900">Weekly reflection</h2>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-700">
                            Start your private weekly check-in or review your own reflection history.
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <Link :href="route('checkins.weekly.show')">
                            <PrimaryButton type="button">Start weekly check-in</PrimaryButton>
                        </Link>
                        <Link :href="route('checkins.weekly.history')">
                            <PrimaryButton type="button">View my history</PrimaryButton>
                        </Link>
                    </div>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="surface-muted p-4 text-sm text-slate-700">Weekly check-in</div>
                    <div class="surface-muted p-4 text-sm text-slate-700">Monthly insight</div>
                    <div class="surface-muted p-4 text-sm text-slate-700">Guided conversations</div>
                    <div class="surface-muted p-4 text-sm text-slate-500">Dashboard</div>
                </div>
            </CardPanel>

            <CardPanel>
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="text-2xl text-slate-900">Monthly alignment</h2>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-700">
                            Complete the shared monthly reflection to unlock a neutral alignment view.
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <Link :href="route('checkins.monthly.show')">
                            <PrimaryButton type="button">Open monthly check-in</PrimaryButton>
                        </Link>
                    </div>
                </div>
            </CardPanel>

            <CardPanel>
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="text-2xl text-slate-900">Guided conversations</h2>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-700">
                            Start a structured, turn-based conversation when you want a calmer container for difficult topics.
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <Link :href="route('conversations.index')">
                            <PrimaryButton type="button">Open conversations</PrimaryButton>
                        </Link>
                    </div>
                </div>
            </CardPanel>

            <CardPanel>
                <h2 class="text-2xl text-slate-900">Relationship management</h2>
                <p class="mt-4 text-sm leading-6 text-slate-700">
                    If needed, either partner can dissolve this couple space. Shared features will be locked.
                </p>
                <div class="mt-6">
                    <DangerButton type="button" @click="router.post(route('couple.dissolve'))">
                        Dissolve couple space
                    </DangerButton>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
