<script setup>
import AppLogo from '@/Components/AppLogo.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import RetryActionBar from '@/Components/RetryActionBar.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const success = computed(() => page.props.flash?.success ?? '');
const error = computed(() => page.props.flash?.error ?? '');
const retryableError = computed(() => page.props.flash?.retryableError ?? null);
const user = computed(() => page.props.auth?.user ?? null);
const couple = computed(() => page.props.couple ?? { state: 'none' });
</script>

<template>
    <div class="min-h-screen">
        <div class="page-shell space-y-6">
            <header class="surface-card flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                    <Link :href="route('home')">
                        <AppLogo />
                    </Link>

                    <nav v-if="user" class="flex flex-wrap items-center gap-2 text-sm text-slate-600">
                        <Link
                            :href="route('home')"
                            class="rounded-full px-3 py-2 transition hover:bg-stone-100 hover:text-slate-900"
                        >
                            Home
                        </Link>
                        <Link
                            v-if="couple.state === 'active'"
                            :href="route('checkins.weekly.show')"
                            class="rounded-full px-3 py-2 transition hover:bg-stone-100 hover:text-slate-900"
                        >
                            Weekly Check-In
                        </Link>
                        <Link
                            v-if="couple.state === 'active'"
                            :href="route('checkins.monthly.show')"
                            class="rounded-full px-3 py-2 transition hover:bg-stone-100 hover:text-slate-900"
                        >
                            Monthly Insight
                        </Link>
                        <Link
                            v-if="couple.state === 'active'"
                            :href="route('conversations.index')"
                            class="rounded-full px-3 py-2 transition hover:bg-stone-100 hover:text-slate-900"
                        >
                            Conversations
                        </Link>
                        <Link
                            :href="route('dashboard.couple')"
                            class="rounded-full px-3 py-2 transition hover:bg-stone-100 hover:text-slate-900"
                        >
                            Our Progress
                        </Link>
                        <Link
                            :href="route('dashboard.personal')"
                            class="rounded-full px-3 py-2 transition hover:bg-stone-100 hover:text-slate-900"
                        >
                            My Progress
                        </Link>
                        <Link
                            :href="route('settings.notifications.show')"
                            class="rounded-full px-3 py-2 transition hover:bg-stone-100 hover:text-slate-900"
                        >
                            Notifications
                        </Link>
                    </nav>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <StatusBadge v-if="couple.state !== 'none'" :status="couple.state" />
                    <div class="text-sm text-slate-600" v-if="user">
                        {{ user.name }}
                    </div>
                    <Link :href="route('logout')" method="post" as="button">
                        <SecondaryButton type="button">Log out</SecondaryButton>
                    </Link>
                </div>
            </header>

            <FlashBanner :message="success" />
            <FlashBanner type="error" :message="error" />
            <RetryActionBar
                v-if="retryableError"
                :message="retryableError.message"
                :retry-url="retryableError.retryUrl"
            />

            <slot />
        </div>
    </div>
</template>
