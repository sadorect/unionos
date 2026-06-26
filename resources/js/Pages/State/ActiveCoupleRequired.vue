<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    state: {
        type: String,
        default: 'pending',
    },
});
</script>

<template>
    <CoupleLayout>
        <Head title="This feature isn't available yet" />
        <div class="form-shell">
            <StateCard
                title="This feature isn't available yet"
                description="Your couple space needs to be active before you can use this feature."
                primary-label="Go to couple setup"
                :primary-url="state === 'dissolved' ? route('couple.dissolved') : route('couple.pending')"
                secondary-label="Return home"
                :secondary-url="route('home')"
            >
                <p class="mt-4 text-sm text-slate-500">
                    Invite your partner to complete setup.
                </p>
            </StateCard>
        </div>
    </CoupleLayout>
</template>
