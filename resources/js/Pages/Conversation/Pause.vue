<script setup>
import PageHeader from '@/Components/PageHeader.vue';
import PauseStateCard from '@/Components/PauseStateCard.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    session: {
        type: Object,
        required: true,
    },
    lastCompletedStep: {
        type: Number,
        default: 0,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="Conversation paused" />

        <div class="form-shell space-y-6">
            <PageHeader
                title="Conversation paused"
                description="You can return when you're ready."
            />

            <PauseStateCard
                :title="session.packTitle"
                :paused-at="session.pausedAt"
                :last-completed-step="lastCompletedStep"
            />

            <div class="flex flex-col gap-3 sm:flex-row">
                <PrimaryButton type="button" @click="router.post(route('conversations.resume', session.id))">
                    Resume conversation
                </PrimaryButton>
                <Link :href="route('home')">
                    <PrimaryButton type="button">Return home</PrimaryButton>
                </Link>
            </div>
        </div>
    </CoupleLayout>
</template>
