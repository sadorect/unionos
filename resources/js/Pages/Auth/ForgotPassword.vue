<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
        default: '',
    },
    captchaQuestion: {
        type: String,
        required: true,
    },
});

const form = useForm({
    email: '',
    captcha_answer: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <AuthLayout>
        <Head title="Reset your password" />

        <PageHeader
            title="Reset your password"
            description="Enter your email and we'll send a reset link."
        />

        <CardPanel>
            <div
                v-if="status"
                class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            >
                {{ status }}
            </div>

            <form class="space-y-5" @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        class="mt-2"
                        type="email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="captcha_answer" value="Verification" />
                    <p class="mt-2 text-sm text-slate-600">{{ captchaQuestion }}</p>
                    <TextInput
                        id="captcha_answer"
                        v-model="form.captcha_answer"
                        class="mt-2"
                        type="text"
                        required
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.captcha_answer" />
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <Link
                        :href="route('login')"
                        class="text-sm text-slate-600 underline decoration-stone-300 underline-offset-4 hover:text-slate-900"
                    >
                        Back to login
                    </Link>
                    <PrimaryButton :disabled="form.processing">
                        {{ form.processing ? 'Sending...' : 'Send reset link' }}
                    </PrimaryButton>
                </div>
            </form>
        </CardPanel>
    </AuthLayout>
</template>
