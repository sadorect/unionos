<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import StatusPill from '@/Components/StatusPill.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    session: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="Conversation complete" />

        <div class="form-shell space-y-6">
            <PageHeader
                title="Conversation complete"
                description="Thank you for making space for this conversation."
            />

            <CardPanel>
                <div class="flex flex-wrap items-center gap-3">
                    <StatusPill value="complete" />
                    <span class="text-sm text-slate-600">{{ session.packTitle }}</span>
                </div>
                <p v-if="session.completedAt" class="mt-4 text-sm text-slate-500">
                    Completed {{ new Date(session.completedAt).toLocaleString() }}
                </p>
            </CardPanel>

            <div class="flex flex-col gap-3 sm:flex-row">
                <Link :href="route('home')">
                    <PrimaryButton type="button">Return home</PrimaryButton>
                </Link>
                <Link :href="route('conversations.index')">
                    <PrimaryButton type="button">Start another conversation</PrimaryButton>
                </Link>
            </div>
        </div>
    </CoupleLayout>
</template>
