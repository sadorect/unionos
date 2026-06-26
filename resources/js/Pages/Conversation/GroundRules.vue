<script setup>
import CardPanel from '@/Components/CardPanel.vue';
import GroundRulesList from '@/Components/GroundRulesList.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    session: {
        type: Object,
        required: true,
    },
});

const agreed = ref(false);
</script>

<template>
    <CoupleLayout>
        <Head title="Before you begin" />

        <div class="form-shell space-y-6">
            <PageHeader
                title="Before you begin"
                description="A few ground rules help this conversation stay clear and respectful."
            />

            <CardPanel>
                <GroundRulesList />

                <label class="mt-8 flex items-center gap-3 text-sm text-slate-700">
                    <input v-model="agreed" type="checkbox" class="rounded border-stone-300 text-emerald-700 focus:ring-emerald-600" />
                    <span>I understand and agree</span>
                </label>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <PrimaryButton
                        type="button"
                        :disabled="!agreed"
                        @click="router.post(route('conversations.begin', session.id))"
                    >
                        Begin conversation
                    </PrimaryButton>
                    <Link :href="route('conversations.index')">
                        <SecondaryButton type="button">Cancel</SecondaryButton>
                    </Link>
                </div>
            </CardPanel>
        </div>
    </CoupleLayout>
</template>
