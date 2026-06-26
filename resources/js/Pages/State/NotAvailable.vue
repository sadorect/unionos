<script setup>
import StateCard from '@/Components/StateCard.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const hasUser = computed(() => !!page.props.auth?.user);
</script>

<template>
    <component :is="hasUser ? CoupleLayout : AuthLayout">
        <Head title="This page isn't available" />
        <div class="form-shell">
            <StateCard
                title="This page isn't available"
                description="It may no longer be accessible in your current context."
                primary-label="Return home"
                :primary-url="hasUser ? route('home') : route('login')"
                secondary-label="Go back"
                :secondary-url="hasUser ? route('dashboard') : route('register')"
            />
        </div>
    </component>
</template>
