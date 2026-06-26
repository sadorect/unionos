<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({});

const submit = () => {
    form.post(route('couple.store'));
};
</script>

<template>
    <CoupleLayout>
        <Head title="Create your couple space" />

        <div class="space-y-6">
            <PageHeader
                title="Create your couple space"
                description="This is your shared private space. You can invite your partner after setup."
            />

            <CardPanel>
                <ol class="space-y-4 text-sm leading-6 text-slate-700">
                    <li>1. Create your private couple space.</li>
                    <li>2. Invite your partner.</li>
                    <li>3. Unlock shared features once both of you are linked.</li>
                </ol>

                <div class="mt-8">
                    <PrimaryButton :disabled="form.processing" @click="submit">
                        {{ form.processing ? 'Creating...' : 'Create couple space' }}
                    </PrimaryButton>
                </div>

                <div class="mt-6 space-y-2 text-sm text-slate-500">
                    <p>Only two people can belong to a couple space.</p>
                    <p>Nothing is shared until your partner joins.</p>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
