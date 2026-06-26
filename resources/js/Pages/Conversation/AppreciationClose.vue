<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import ConversationTimer from '@/Components/ConversationTimer.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PromptCard from '@/Components/PromptCard.vue';
import SpeakerStatusBanner from '@/Components/SpeakerStatusBanner.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    session: {
        type: Object,
        required: true,
    },
    step: {
        type: Object,
        required: true,
    },
    isParticipantsTurn: {
        type: Boolean,
        default: false,
    },
});

const form = useForm({
    response_text: '',
});

const submit = () => {
    form.post(route('conversations.close.submit', props.session.id));
};
</script>

<template>
    <CoupleLayout>
        <Head title="Close with appreciation" />

        <div class="form-shell space-y-6">
            <PageHeader
                title="Close with appreciation"
                description="End this conversation by naming one thing you appreciate right now."
            />

            <div class="flex justify-end">
                <ConversationTimer :seconds="step.timerSeconds" />
            </div>

            <SpeakerStatusBanner :state="step.speakerState" />
            <PromptCard :prompt="step.promptText" :instruction="step.instruction" />

            <CardPanel v-if="isParticipantsTurn">
                <form class="space-y-5" @submit.prevent="submit">
                    <TextAreaInput v-model="form.response_text" />
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <PrimaryButton :disabled="form.processing">
                            {{ form.processing ? 'Saving...' : 'Complete conversation' }}
                        </PrimaryButton>
                        <PrimaryButton type="button" @click="router.post(route('conversations.pause', session.id))">
                            Pause
                        </PrimaryButton>
                    </div>
                </form>
            </CardPanel>

            <CardPanel v-else>
                <p class="text-sm leading-6 text-slate-700">
                    Your partner is completing the closing appreciation.
                </p>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
