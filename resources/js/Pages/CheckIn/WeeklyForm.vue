<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PrivacyNote from '@/Components/PrivacyNote.vue';
import ScaleSelector from '@/Components/ScaleSelector.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import StatusPill from '@/Components/StatusPill.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import WeekLabel from '@/Components/WeekLabel.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    week: {
        type: Object,
        required: true,
    },
    checkIn: {
        type: Object,
        default: null,
    },
    isEditing: {
        type: Boolean,
        default: false,
    },
    privacyNote: {
        type: String,
        required: true,
    },
});

const form = useForm({
    emotional_availability: props.checkIn?.emotionalAvailability ?? null,
    communication_effort: props.checkIn?.communicationEffort ?? null,
    stress_level: props.checkIn?.stressLevel ?? null,
    appreciation_text: props.checkIn?.appreciationText ?? '',
    struggle_text: props.checkIn?.struggleText ?? '',
    intention_text: props.checkIn?.intentionText ?? '',
});

const submit = () => {
    form.post(route('checkins.weekly.store'));
};
</script>

<template>
    <CoupleLayout>
        <Head title="Weekly check-in" />

        <div class="form-shell space-y-6">
            <PageHeader
                title="Weekly check-in"
                description="A short private reflection for this week."
            />

            <PrivacyNote :message="privacyNote" />

            <CardPanel>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-3">
                        <WeekLabel :value="week.label" />
                        <StatusPill :value="isEditing ? 'editing' : 'open'" />
                    </div>
                    <p class="text-sm text-slate-500">Takes about 3-5 minutes</p>
                </div>

                <form class="mt-8 space-y-8" @submit.prevent="submit">
                    <div class="space-y-6">
                        <section class="space-y-3">
                            <InputLabel value="Emotional availability" />
                            <p class="text-sm text-slate-600">This week, I felt emotionally present.</p>
                            <ScaleSelector v-model="form.emotional_availability" name="emotional_availability" />
                            <InputError :message="form.errors.emotional_availability" />
                        </section>

                        <section class="space-y-3">
                            <InputLabel value="Communication effort" />
                            <p class="text-sm text-slate-600">This week, I tried to communicate clearly and kindly.</p>
                            <ScaleSelector v-model="form.communication_effort" name="communication_effort" />
                            <InputError :message="form.errors.communication_effort" />
                        </section>

                        <section class="space-y-3">
                            <InputLabel value="Stress level" />
                            <p class="text-sm text-slate-600">This week felt stressful.</p>
                            <ScaleSelector
                                v-model="form.stress_level"
                                name="stress_level"
                                min-label="Very low"
                                mid-label="Moderate"
                                max-label="Very high"
                            />
                            <InputError :message="form.errors.stress_level" />
                        </section>
                    </div>

                    <div class="space-y-5 border-t border-stone-200 pt-8">
                        <div class="space-y-2">
                            <InputLabel value="One thing I appreciated this week" />
                            <p class="text-sm text-slate-500">Optional</p>
                            <TextAreaInput v-model="form.appreciation_text" />
                            <InputError :message="form.errors.appreciation_text" />
                        </div>

                        <div class="space-y-2">
                            <InputLabel value="One thing I struggled with" />
                            <p class="text-sm text-slate-500">Optional</p>
                            <TextAreaInput v-model="form.struggle_text" />
                            <InputError :message="form.errors.struggle_text" />
                        </div>

                        <div class="space-y-2">
                            <InputLabel value="One thing I want to do better next week" />
                            <p class="text-sm text-slate-500">Optional</p>
                            <TextAreaInput v-model="form.intention_text" />
                            <InputError :message="form.errors.intention_text" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <SecondaryButton type="button" @click="router.get(route('home'))">
                            Back
                        </SecondaryButton>
                        <PrimaryButton :disabled="form.processing">
                            {{ form.processing ? 'Saving...' : (isEditing ? 'Update check-in' : 'Save check-in') }}
                        </PrimaryButton>
                    </div>
                </form>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
