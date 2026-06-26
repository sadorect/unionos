<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import StatusPill from '@/Components/StatusPill.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    session: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="This conversation is complete" />
        <div class="form-shell space-y-6">
            <div class="flex flex-wrap items-center gap-3">
                <StatusPill value="complete" />
                <span class="text-sm text-slate-600">{{ session.packTitle }}</span>
            </div>
            <StateCard
                title="This conversation is complete"
                description="Completed sessions stay locked to preserve the conversation as it happened."
                primary-label="Return to conversations"
                :primary-url="route('conversations.index')"
                secondary-label="Start another conversation"
                :secondary-url="route('conversations.index')"
            />
        </div>
    </CoupleLayout>
</template>
