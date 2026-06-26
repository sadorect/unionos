<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <CoupleLayout>
        <Head title="Conversation pack unavailable" />

        <div class="form-shell">
            <StateCard
                title="This conversation pack isn't available right now"
                description="It may no longer be active or supported in the current session."
                primary-label="Return to conversation packs"
                :primary-url="route('conversations.index')"
            />
        </div>
    </CoupleLayout>
</template>
