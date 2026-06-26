<script setup>
import ConversationPackMeta from '@/Components/ConversationPackMeta.vue';
import PageHeader from '@/Components/PageHeader.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PromptPreviewList from '@/Components/PromptPreviewList.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    pack: {
        type: Object,
        required: true,
    },
});

const startConversation = () => {
    router.post(route('conversations.store'), {
        pack_type: props.pack.slug,
    });
};
</script>

<template>
    <CoupleLayout>
        <Head :title="pack.title" />

        <div class="form-shell space-y-6">
            <PageHeader
                :title="pack.title"
                :description="pack.description"
            />

            <section class="surface-card space-y-5 p-6 sm:p-8">
                <ConversationPackMeta :pack="pack" />
                <PromptPreviewList :prompts="pack.prompts" />
                <div class="flex flex-col gap-3 sm:flex-row">
                    <PrimaryButton type="button" @click="startConversation">
                        Start conversation
                    </PrimaryButton>
                    <Link :href="route('conversations.index')">
                        <SecondaryButton type="button">Back to packs</SecondaryButton>
                    </Link>
                </div>
            </section>
        </div>
    </CoupleLayout>
</template>
