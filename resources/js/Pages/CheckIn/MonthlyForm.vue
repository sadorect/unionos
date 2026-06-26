<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import MonthLabel from '@/Components/MonthLabel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PrivacyNote from '@/Components/PrivacyNote.vue';
import ScaleSelector from '@/Components/ScaleSelector.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import StatusPill from '@/Components/StatusPill.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

defineProps({
    month: {
        type: Object,
        required: true,
    },
    privacyNote: {
        type: String,
        required: true,
    },
});

const form = useForm({
    felt_heard: null,
    felt_appreciated: null,
    conflict_safety: null,
    emotional_closeness: null,
});

const submit = () => {
    form.post(route('checkins.monthly.store'));
};
</script>

<template>
    <CoupleLayout>
        <Head title="Monthly check-in" />

        <div class="form-shell space-y-6">
            <PageHeader
                title="Monthly check-in"
                description="A shared reflection that helps you stay aligned."
            />

            <PrivacyNote :message="privacyNote" />

            <CardPanel>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-3">
                        <MonthLabel :value="month.label" />
                        <StatusPill value="open" />
                    </div>
                    <p class="text-sm text-slate-500">Takes about 2-3 minutes</p>
                </div>

                <div
                    v-if="form.errors.monthly"
                    class="mt-6 rounded-[24px] border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"
                >
                    {{ form.errors.monthly }}
                </div>

                <form class="mt-8 space-y-8" @submit.prevent="submit">
                    <section class="space-y-3">
                        <InputLabel value="Feeling heard" />
                        <p class="text-sm text-slate-600">I feel heard in our relationship.</p>
                        <ScaleSelector v-model="form.felt_heard" name="felt_heard" />
                        <InputError :message="form.errors.felt_heard" />
                    </section>

                    <section class="space-y-3">
                        <InputLabel value="Feeling appreciated" />
                        <p class="text-sm text-slate-600">I feel appreciated by my partner.</p>
                        <ScaleSelector v-model="form.felt_appreciated" name="felt_appreciated" />
                        <InputError :message="form.errors.felt_appreciated" />
                    </section>

                    <section class="space-y-3">
                        <InputLabel value="Conflict safety" />
                        <p class="text-sm text-slate-600">When we disagree, it feels emotionally safe.</p>
                        <ScaleSelector v-model="form.conflict_safety" name="conflict_safety" />
                        <InputError :message="form.errors.conflict_safety" />
                    </section>

                    <section class="space-y-3">
                        <InputLabel value="Emotional closeness" />
                        <p class="text-sm text-slate-600">I feel emotionally close to my partner.</p>
                        <ScaleSelector v-model="form.emotional_closeness" name="emotional_closeness" />
                        <InputError :message="form.errors.emotional_closeness" />
                    </section>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <SecondaryButton type="button" @click="router.get(route('home'))">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton :disabled="form.processing">
                            {{ form.processing ? 'Submitting...' : 'Submit check-in' }}
                        </PrimaryButton>
                    </div>
                </form>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
