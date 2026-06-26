<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    state: {
        type: String,
        required: true,
    },
    invite: {
        type: Object,
        default: null,
    },
    loginUrl: {
        type: String,
        default: '',
    },
    registerUrl: {
        type: String,
        default: '',
    },
    acceptUrl: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <AuthLayout>
        <Head title="Accept invitation" />

        <PageHeader
            v-if="state === 'guest'"
            title="You've been invited"
            description="Join a private couple space on UnionOS."
        />
        <PageHeader
            v-else-if="state === 'ready'"
            title="Accept invitation"
            description="You're about to join a private couple space."
        />
        <PageHeader
            v-else-if="state === 'already_linked'"
            title="You can't join this invitation"
            description="Your account is already linked to another couple space."
        />
        <PageHeader
            v-else
            title="This invitation is no longer available"
            description="It may have expired or already been used."
        />

        <CardPanel v-if="state === 'guest' && invite">
            <div class="flex items-center gap-3">
                <StatusBadge status="sent" />
                <span class="text-sm text-slate-600">{{ invite.emailMasked }}</span>
            </div>
            <p class="mt-4 text-sm leading-6 text-slate-700">
                You'll need an account to accept this invite.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <Link :href="registerUrl">
                    <PrimaryButton type="button">Create account and continue</PrimaryButton>
                </Link>
                <Link :href="loginUrl">
                    <SecondaryButton type="button">Log in to continue</SecondaryButton>
                </Link>
            </div>
        </CardPanel>

        <CardPanel v-else-if="state === 'ready' && invite">
            <div class="flex items-center gap-3">
                <StatusBadge status="sent" />
                <span class="text-sm text-slate-600">{{ invite.emailMasked }}</span>
            </div>
            <p class="mt-4 text-sm leading-6 text-slate-700">
                A couple space can only have two people.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <PrimaryButton type="button" @click="router.post(acceptUrl)">Accept invite</PrimaryButton>
                <Link :href="route('home')">
                    <SecondaryButton type="button">Cancel</SecondaryButton>
                </Link>
            </div>
        </CardPanel>

        <CardPanel v-else-if="state === 'already_linked'">
            <p class="text-sm leading-6 text-slate-700">
                Return to your current space to continue.
            </p>
            <div class="mt-6">
                <Link :href="route('home')">
                    <SecondaryButton type="button">Return to your dashboard</SecondaryButton>
                </Link>
            </div>
        </CardPanel>

        <CardPanel v-else>
            <p class="text-sm leading-6 text-slate-700">
                Ask your partner to send a new invitation if you still need access.
            </p>
            <div class="mt-6">
                <Link :href="route('home')">
                    <SecondaryButton type="button">Return home</SecondaryButton>
                </Link>
            </div>
        </CardPanel>
    </AuthLayout>
</template>
