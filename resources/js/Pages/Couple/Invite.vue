<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import TextInput from '@/Components/TextInput.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    currentInvite: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('couple.invite.store'));
};

const copyInvite = async () => {
    if (!props.currentInvite) {
        return;
    }

    await navigator.clipboard.writeText(new URL(props.currentInvite.link, window.location.origin).toString());
};
</script>

<template>
    <CoupleLayout>
        <Head title="Invite your partner" />

        <div class="space-y-6">
            <PageHeader
                title="Invite your partner"
                description="Send a secure invitation to join your private couple space."
            />

            <CardPanel v-if="!currentInvite">
                <form class="space-y-5" @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Partner email" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            class="mt-2"
                            type="email"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <PrimaryButton :disabled="form.processing">
                        {{ form.processing ? 'Sending...' : 'Send invite' }}
                    </PrimaryButton>
                </form>

                <div class="mt-6 space-y-2 text-sm text-slate-500">
                    <p>The invitation link is single-use and expires automatically.</p>
                    <p>Your partner will create or sign in to an account before joining.</p>
                </div>
            </CardPanel>

            <CardPanel v-else>
                <div class="flex flex-wrap items-center gap-3">
                    <StatusBadge status="sent" />
                    <span class="text-sm text-slate-600">{{ currentInvite.emailMasked }}</span>
                </div>

                <p class="mt-4 text-sm text-slate-600">
                    Expires {{ new Date(currentInvite.expiresAt).toLocaleString() }}
                </p>

                <div class="mt-5 surface-muted p-4 text-sm text-slate-600">
                    {{ new URL(currentInvite.link, window.location.origin).toString() }}
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <SecondaryButton type="button" @click="copyInvite">Copy link</SecondaryButton>
                    <SecondaryButton type="button" @click="router.post(route('couple.invite.regenerate'))">
                        Regenerate invite
                    </SecondaryButton>
                    <SecondaryButton type="button" @click="router.delete(route('couple.invite.destroy'))">
                        Cancel invite
                    </SecondaryButton>
                </div>

                <div class="mt-6 space-y-2 text-sm text-slate-500">
                    <p>The invitation link is single-use and expires automatically.</p>
                    <p>Your partner will create or sign in to an account before joining.</p>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
