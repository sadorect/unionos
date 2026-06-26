<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import EmptyState from '@/Components/EmptyState.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    invite: {
        type: Object,
        default: null,
    },
});

const copyInvite = async () => {
    if (!props.invite) {
        return;
    }

    await navigator.clipboard.writeText(new URL(props.invite.link, window.location.origin).toString());
};
</script>

<template>
    <CoupleLayout>
        <Head title="Your couple space is waiting" />

        <div class="space-y-6">
            <PageHeader
                title="Your couple space is waiting"
                description="Invite your partner to activate shared features."
            />

            <CardPanel>
                <div class="flex flex-wrap items-center gap-3">
                    <StatusBadge status="pending" />
                </div>
                <p class="mt-4 text-sm leading-6 text-slate-700">
                    Your couple space has been created, but it won't become active until your partner accepts the invite.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <Link :href="route('couple.invite.create')">
                        <PrimaryButton type="button">Invite partner</PrimaryButton>
                    </Link>
                </div>
            </CardPanel>

            <CardPanel>
                <h2 class="text-2xl text-slate-900">Available after linking</h2>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="surface-muted p-4 text-sm text-slate-500">Weekly private check-ins</div>
                    <div class="surface-muted p-4 text-sm text-slate-500">Shared monthly insights</div>
                    <div class="surface-muted p-4 text-sm text-slate-500">Guided conversations</div>
                    <div class="surface-muted p-4 text-sm text-slate-500">Progress dashboard</div>
                </div>
            </CardPanel>

            <CardPanel>
                <h2 class="text-2xl text-slate-900">Invite summary</h2>

                <div v-if="invite" class="mt-5 space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <StatusBadge status="sent" />
                        <span class="text-sm text-slate-600">{{ invite.emailMasked }}</span>
                    </div>
                    <p class="text-sm text-slate-600">
                        Expires {{ new Date(invite.expiresAt).toLocaleString() }}
                    </p>
                    <div class="surface-muted p-4 text-sm text-slate-600">
                        {{ new URL(invite.link, window.location.origin).toString() }}
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <SecondaryButton type="button" @click="copyInvite">Copy invite link</SecondaryButton>
                        <SecondaryButton type="button" @click="router.post(route('couple.invite.regenerate'))">
                            Regenerate invite
                        </SecondaryButton>
                        <SecondaryButton type="button" @click="router.delete(route('couple.invite.destroy'))">
                            Cancel invite
                        </SecondaryButton>
                    </div>
                </div>

                <div v-else class="mt-5">
                    <EmptyState
                        title="No active invite yet"
                        description="Send an invitation when you're ready."
                    >
                        <Link :href="route('couple.invite.create')">
                            <PrimaryButton type="button">Send invite</PrimaryButton>
                        </Link>
                    </EmptyState>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
