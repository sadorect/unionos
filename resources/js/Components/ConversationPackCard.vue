<script setup>
import ConversationPackMeta from '@/Components/ConversationPackMeta.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    pack: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['start']);
</script>

<template>
    <div class="surface-card p-6 sm:p-7">
        <h2 class="text-2xl text-slate-900">{{ pack.title }}</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">{{ pack.description }}</p>
        <div class="mt-4">
            <ConversationPackMeta :pack="pack" />
        </div>
        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <Link :href="route('conversations.packs.show', pack.slug)">
                <SecondaryButton type="button">Preview</SecondaryButton>
            </Link>
            <PrimaryButton type="button" @click="emit('start', pack.slug)">Start</PrimaryButton>
        </div>
    </div>
</template>
