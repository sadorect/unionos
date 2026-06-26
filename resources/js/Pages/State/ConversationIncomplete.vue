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
        <Head title="This conversation wasn't completed" />
        <div class="form-shell space-y-6">
            <div class="flex flex-wrap items-center gap-3">
                <StatusPill value="locked" />
                <span class="text-sm text-slate-600">{{ session.packTitle }}</span>
            </div>
            <StateCard
                title="This conversation wasn't completed"
                description="You can start a new guided conversation whenever you're ready."
                primary-label="Start a new conversation"
                :primary-url="route('conversations.index')"
                secondary-label="Return to conversations"
                :secondary-url="route('conversations.index')"
            />
        </div>
    </CoupleLayout>
</template>
