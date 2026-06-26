<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import StatusPill from '@/Components/StatusPill.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    session: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="Conversation paused" />
        <div class="form-shell space-y-6">
            <div class="flex flex-wrap items-center gap-3">
                <StatusPill value="paused" />
                <span class="text-sm text-slate-600">{{ session.packTitle }}</span>
            </div>
            <StateCard
                title="Conversation paused"
                description="You can return and continue when you're ready."
                primary-label="Resume conversation"
                :primary-url="route('conversations.pause.show', session.id)"
                secondary-label="Return home"
                :secondary-url="route('home')"
            >
                <div class="mt-6">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-full border border-emerald-900 bg-emerald-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
                        @click="router.post(route('conversations.resume', session.id))"
                    >
                        Resume conversation
                    </button>
                </div>
                <p class="mt-4 text-sm text-slate-500">There's no need to rush.</p>
            </StateCard>
        </div>
    </CoupleLayout>
</template>
