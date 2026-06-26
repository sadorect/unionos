<script setup>
import NotificationPreferenceCard from '@/Components/NotificationPreferenceCard.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PreferenceSelect from '@/Components/PreferenceSelect.vue';
import PreferenceToggle from '@/Components/PreferenceToggle.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import QuietModeNotice from '@/Components/QuietModeNotice.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    preferences: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    weekly_email_enabled: props.preferences.weeklyEmailEnabled,
    weekly_day_of_week: props.preferences.weeklyDayOfWeek,
    weekly_hour: props.preferences.weeklyHour,
    monthly_email_enabled: props.preferences.monthlyEmailEnabled,
    monthly_day_of_month: props.preferences.monthlyDayOfMonth,
    monthly_hour: props.preferences.monthlyHour,
    conversation_email_enabled: props.preferences.conversationEmailEnabled,
    conversation_in_app_enabled: props.preferences.conversationInAppEnabled,
    conversation_hour: props.preferences.conversationHour,
    timezone: props.preferences.timezone,
    quiet_mode_enabled: props.preferences.quietModeEnabled,
});

const submit = () => {
    form.put(route('settings.notifications.update'));
};
</script>

<template>
    <CoupleLayout>
        <Head title="Notification preferences" />

        <div class="form-shell space-y-6">
            <PageHeader
                title="Notification preferences"
                description="Choose which reminders feel helpful for you."
            />

            <p class="text-sm text-slate-500">These settings apply only to your account.</p>

            <QuietModeNotice v-if="form.quiet_mode_enabled" />

            <form class="space-y-6" @submit.prevent="submit">
                <NotificationPreferenceCard
                    title="Weekly check-in reminders"
                    description="Receive a gentle reminder when your weekly check-in is available."
                >
                    <div class="space-y-4">
                        <PreferenceToggle
                            id="weekly_email_enabled"
                            label="Email reminders"
                            :checked="form.weekly_email_enabled"
                            @update:checked="form.weekly_email_enabled = $event"
                        />
                        <div class="grid gap-4 sm:grid-cols-2">
                            <PreferenceSelect
                                id="weekly_day_of_week"
                                label="Preferred day"
                                :model-value="form.weekly_day_of_week"
                                :options="options.weekdays"
                                @update:model-value="form.weekly_day_of_week = Number($event)"
                            />
                            <PreferenceSelect
                                id="weekly_hour"
                                label="Preferred time"
                                :model-value="form.weekly_hour"
                                :options="options.hours"
                                @update:model-value="form.weekly_hour = Number($event)"
                            />
                        </div>
                    </div>
                </NotificationPreferenceCard>

                <NotificationPreferenceCard
                    title="Monthly check-in reminders"
                    description="Receive a reminder when the monthly check-in window opens."
                >
                    <div class="space-y-4">
                        <PreferenceToggle
                            id="monthly_email_enabled"
                            label="Email reminders"
                            :checked="form.monthly_email_enabled"
                            @update:checked="form.monthly_email_enabled = $event"
                        />
                        <div class="grid gap-4 sm:grid-cols-2">
                            <PreferenceSelect
                                id="monthly_day_of_month"
                                label="Preferred day"
                                :model-value="form.monthly_day_of_month"
                                :options="Array.from({ length: 28 }, (_, index) => ({ value: index + 1, label: `Day ${index + 1}` }))"
                                @update:model-value="form.monthly_day_of_month = Number($event)"
                            />
                            <PreferenceSelect
                                id="monthly_hour"
                                label="Preferred time"
                                :model-value="form.monthly_hour"
                                :options="options.hours"
                                @update:model-value="form.monthly_hour = Number($event)"
                            />
                        </div>
                    </div>
                </NotificationPreferenceCard>

                <NotificationPreferenceCard
                    title="Conversation suggestions"
                    description="Occasionally receive a calm suggestion when a guided conversation may be useful."
                >
                    <div class="space-y-4">
                        <PreferenceToggle
                            id="conversation_email_enabled"
                            label="Show suggestion emails"
                            :checked="form.conversation_email_enabled"
                            @update:checked="form.conversation_email_enabled = $event"
                        />
                        <PreferenceToggle
                            id="conversation_in_app_enabled"
                            label="In-app suggestions"
                            :checked="form.conversation_in_app_enabled"
                            @update:checked="form.conversation_in_app_enabled = $event"
                        />
                        <PreferenceSelect
                            id="conversation_hour"
                            label="Preferred time"
                            :model-value="form.conversation_hour"
                            :options="options.hours"
                            @update:model-value="form.conversation_hour = Number($event)"
                        />
                    </div>
                </NotificationPreferenceCard>

                <NotificationPreferenceCard
                    title="Quiet mode"
                    description="Turn off reminders for now. You can change this any time."
                >
                    <div class="space-y-4">
                        <PreferenceToggle
                            id="quiet_mode_enabled"
                            label="Pause all reminders"
                            :checked="form.quiet_mode_enabled"
                            @update:checked="form.quiet_mode_enabled = $event"
                        />
                        <PreferenceSelect
                            id="timezone"
                            label="Timezone"
                            :model-value="form.timezone"
                            :options="options.timezones"
                            @update:model-value="form.timezone = $event"
                        />
                    </div>
                </NotificationPreferenceCard>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <SecondaryButton type="button" @click="router.get(route('home'))">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save preferences' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </CoupleLayout>
</template>
