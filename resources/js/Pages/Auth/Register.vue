<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
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
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    captcha_answer: '',
    invite_token: props.inviteToken,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Create your account" />

        <PageHeader
            title="Create your account"
            description="Start with your own secure account. You'll link with your partner next."
        />

        <CardPanel>
            <form class="space-y-5" @submit.prevent="submit">
                <input v-model="form.invite_token" type="hidden" />

                <div>
                    <InputLabel for="name" value="Name or display name" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        class="mt-2"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        class="mt-2"
                        type="email"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        class="mt-2"
                        type="password"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirm password" />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        class="mt-2"
                        type="password"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
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
                        :href="inviteToken ? route('login', { invite: inviteToken }) : route('login')"
                        class="text-sm text-slate-600 underline decoration-stone-300 underline-offset-4 hover:text-slate-900"
                    >
                        Already have an account? Log in
                    </Link>
                    <PrimaryButton :disabled="form.processing">
                        {{ form.processing ? 'Creating account...' : 'Create account' }}
                    </PrimaryButton>
                </div>
            </form>
        </CardPanel>
    </AuthLayout>
</template>
