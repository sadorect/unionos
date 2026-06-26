<script setup>
import ConversationPackCard from '@/Components/ConversationPackCard.vue';
import PageHeader from '@/Components/PageHeader.vue';
import StatusPill from '@/Components/StatusPill.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    packs: {
        type: Array,
        default: () => [],
    },
    sessions: {
        type: Array,
        default: () => [],
    },
});

const startPack = (slug) => {
    router.post(route('conversations.store'), {
        pack_type: slug,
    });
};
</script>

<template>
    <CoupleLayout>
        <Head title="Conversation packs" />

        <div class="page-shell space-y-6">
            <PageHeader
                title="Conversation packs"
                description="Choose a structured conversation with a clear tone and purpose."
            />

            <section class="grid gap-4 lg:grid-cols-3">
                <ConversationPackCard
                    v-for="pack in packs"
                    :key="pack.slug"
                    :pack="pack"
                    @start="startPack"
                />
            </section>

            <section v-if="sessions.length" class="surface-card p-6 sm:p-8">
                <h2 class="text-2xl text-slate-900">Recent sessions</h2>
                <div class="mt-5 space-y-3">
                    <div
                        v-for="session in sessions"
                        :key="session.id"
                        class="surface-muted flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div>
                            <p class="text-base text-slate-900">{{ session.packTitle }}</p>
                            <p v-if="session.completedAt" class="mt-1 text-sm text-slate-500">
                                Completed {{ new Date(session.completedAt).toLocaleDateString() }}
                            </p>
                        </div>
                        <StatusPill :value="session.status" />
                    </div>
                </div>
            </section>
        </div>
    </CoupleLayout>
</template>
