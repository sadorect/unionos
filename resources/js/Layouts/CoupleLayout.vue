<script setup>
import AppLogo from '@/Components/AppLogo.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import RetryActionBar from '@/Components/RetryActionBar.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
const success = computed(() => page.props.flash?.success ?? '');
const error = computed(() => page.props.flash?.error ?? '');
const retryableError = computed(() => page.props.flash?.retryableError ?? null);
const user = computed(() => page.props.auth?.user ?? null);
const couple = computed(() => page.props.couple ?? { state: 'none' });
const isAdmin = computed(() => user.value?.is_admin ?? false);

const navItems = computed(() => {
    if (!user.value) {
        return [{ label: 'Guide', href: route('guide'), pattern: 'guide' }];
    }

    const items = [
        { label: 'Home', href: route('home'), pattern: 'home' },
    ];

    if (couple.value.state === 'active') {
        items.push(
            { label: 'Weekly Check-In', href: route('checkins.weekly.show'), pattern: 'checkins.weekly.*' },
            { label: 'Monthly Insight', href: route('checkins.monthly.show'), pattern: 'checkins.monthly.*' },
            { label: 'Conversations', href: route('conversations.index'), pattern: 'conversations.*' },
        );
    }

    items.push(
        { label: 'Our Progress', href: route('dashboard.couple'), pattern: 'dashboard.couple' },
        { label: 'My Progress', href: route('dashboard.personal'), pattern: 'dashboard.personal*' },
        { label: 'Notifications', href: route('settings.notifications.show'), pattern: 'settings.notifications.*' },
        { label: 'Guide', href: route('guide'), pattern: 'guide' },
    );

    if (isAdmin.value) {
        items.push({ label: 'Admin', href: route('admin.dashboard'), pattern: 'admin.*', accent: true });
    }

    return items;
});

const isActive = (pattern) => route().current(pattern);

const mobileMenuOpen = ref(false);

watch(() => page.url, () => {
    mobileMenuOpen.value = false;
});
</script>

<template>
    <div class="min-h-screen">
        <div class="page-shell space-y-6">
            <header class="surface-card p-4 sm:p-5 sm:px-6">
                <div class="flex items-center justify-between gap-4">
                    <Link :href="user ? route('home') : route('welcome')" class="shrink-0">
                        <AppLogo />
                    </Link>

                    <div class="flex items-center gap-3">
                        <StatusBadge v-if="couple.state !== 'none'" :status="couple.state" class="hidden sm:inline-flex" />
                        <div class="hidden text-sm text-slate-600 sm:block" v-if="user">
                            {{ user.name }}
                        </div>
                        <Link v-if="user" :href="route('logout')" method="post" as="button" class="hidden sm:inline-flex">
                            <SecondaryButton type="button">Log out</SecondaryButton>
                        </Link>
                        <Link v-else :href="route('login')" class="hidden sm:inline-flex">
                            <SecondaryButton type="button">Log in</SecondaryButton>
                        </Link>

                        <button
                            type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-stone-200 bg-white text-slate-700 transition hover:bg-stone-50 sm:hidden"
                            :aria-expanded="mobileMenuOpen"
                            aria-label="Toggle menu"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                        >
                            <svg v-if="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="4" y1="12" x2="20" y2="12" />
                                <line x1="4" y1="17" x2="20" y2="17" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <line x1="6" y1="6" x2="18" y2="18" />
                                <line x1="18" y1="6" x2="6" y2="18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Desktop nav -->
                <nav class="mt-4 hidden flex-wrap items-center gap-1 border-t border-stone-100 pt-4 sm:flex">
                    <Link
                        v-for="item in navItems"
                        :key="item.label"
                        :href="item.href"
                        class="rounded-full px-3.5 py-2 text-sm font-medium transition"
                        :class="isActive(item.pattern)
                            ? 'bg-emerald-800 text-white shadow-sm'
                            : item.accent
                                ? 'text-emerald-700 hover:bg-emerald-50 hover:text-emerald-900'
                                : 'text-slate-600 hover:bg-stone-100 hover:text-slate-900'"
                    >
                        {{ item.label }}
                    </Link>
                </nav>

                <!-- Mobile nav panel -->
                <nav v-show="mobileMenuOpen" class="mt-4 flex flex-col gap-1 border-t border-stone-100 pt-4 sm:hidden">
                    <Link
                        v-for="item in navItems"
                        :key="item.label"
                        :href="item.href"
                        class="rounded-2xl px-4 py-3 text-base font-medium transition"
                        :class="isActive(item.pattern)
                            ? 'bg-emerald-800 text-white shadow-sm'
                            : item.accent
                                ? 'text-emerald-700 hover:bg-emerald-50'
                                : 'text-slate-700 hover:bg-stone-100'"
                    >
                        {{ item.label }}
                    </Link>

                    <div class="mt-2 flex items-center justify-between gap-3 border-t border-stone-100 pt-4">
                        <StatusBadge v-if="couple.state !== 'none'" :status="couple.state" />
                        <div class="text-sm text-slate-600" v-if="user">{{ user.name }}</div>
                        <Link v-if="user" :href="route('logout')" method="post" as="button" class="ml-auto">
                            <SecondaryButton type="button">Log out</SecondaryButton>
                        </Link>
                        <Link v-else :href="route('login')" class="ml-auto">
                            <SecondaryButton type="button">Log in</SecondaryButton>
                        </Link>
                    </div>
                </nav>
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
