<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
        default: false,
    },
    status: {
        type: String,
        default: '',
    },
    inviteToken: {
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
    password: '',
    remember: false,
    captcha_answer: '',
    invite_token: props.inviteToken,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Welcome back" />

        <PageHeader
            title="Welcome back"
            description="Sign in to your private couple space."
        />

        <CardPanel>
            <div
                v-if="status"
                class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            >
                {{ status }}
            </div>

            <div
                v-if="form.errors.email"
                class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"
            >
                {{ form.errors.email }}
            </div>

            <form class="space-y-5" @submit.prevent="submit">
                <input v-model="form.invite_token" type="hidden" />

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
                </div>

                <div>
                    <InputLabel for="password" value="Password" />
                    <PasswordInput
                        id="password"
                        v-model="form.password"
                        class="mt-2"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
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

                <label class="flex items-center gap-3 text-sm text-slate-600">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span>Remember me</span>
                </label>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-slate-600 underline decoration-stone-300 underline-offset-4 hover:text-slate-900"
                    >
                        Forgot password?
                    </Link>
                    <PrimaryButton :disabled="form.processing">
                        {{ form.processing ? 'Signing in...' : 'Log in' }}
                    </PrimaryButton>
                </div>
            </form>
        </CardPanel>

        <p class="text-sm text-slate-600">
            <Link
                :href="inviteToken ? route('register', { invite: inviteToken }) : route('register')"
                class="font-medium text-slate-900 underline decoration-stone-300 underline-offset-4"
            >
                Create an account
            </Link>
        </p>
    </AuthLayout>
</template>
