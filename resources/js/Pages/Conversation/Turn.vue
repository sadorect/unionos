<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import ConversationTimer from '@/Components/ConversationTimer.vue';
import ListeningStateCard from '@/Components/ListeningStateCard.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ProgressHeader from '@/Components/ProgressHeader.vue';
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
    form.post(route('conversations.submit', props.session.id));
};
</script>

<template>
    <CoupleLayout>
        <Head :title="session.packTitle" />

        <div class="page-shell space-y-6">
            <PageHeader
                title="Guided conversation"
                description="Move through one prompt at a time."
            />

            <ProgressHeader
                :title="session.packTitle"
                :progress-label="session.progressLabel"
                status="active"
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
                            {{ form.processing ? 'Saving...' : 'Submit response' }}
                        </PrimaryButton>
                        <PrimaryButton type="button" @click="router.post(route('conversations.pause', session.id))">
                            Pause
                        </PrimaryButton>
                    </div>
                </form>
            </CardPanel>

            <CardPanel v-else>
                <ListeningStateCard />
                <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                    <PrimaryButton type="button" @click="router.post(route('conversations.pause', session.id))">
                        Pause
                    </PrimaryButton>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
