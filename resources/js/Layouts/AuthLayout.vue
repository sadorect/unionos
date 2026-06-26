<script setup>
import AppLogo from '@/Components/AppLogo.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const success = computed(() => page.props.flash?.success ?? '');
const error = computed(() => page.props.flash?.error ?? '');
</script>

<template>
    <div class="min-h-screen">
        <div class="form-shell">
            <div class="mb-10 flex justify-center sm:justify-start">
                <Link :href="route('login')">
                    <AppLogo />
                </Link>
            </div>

            <div class="mx-auto max-w-2xl space-y-4">
                <FlashBanner :message="success" />
                <FlashBanner type="error" :message="error" />
                <slot />
            </div>
        </div>
    </div>
</template>
