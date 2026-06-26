<script setup>
import StateCard from '@/Components/StateCard.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthLayout>
        <Head title="This invitation is no longer available" />
        <StateCard
            title="This invitation is no longer available"
            description="It may have expired or already been used."
            primary-label="Return home"
            :primary-url="route('login')"
            secondary-label="Ask for a new invitation"
            :secondary-url="route('register')"
        />
    </AuthLayout>
</template>
