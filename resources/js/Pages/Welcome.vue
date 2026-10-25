<script setup>
import { Head, Link } from '@inertiajs/vue3';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
</script>

<template>
    <CoupleLayout>
        <Head title="Welcome" />

        <div class="mx-auto max-w-3xl space-y-10 px-4 pb-16 sm:px-6">

            <!-- Hero -->
            <div class="surface-card p-8 text-center sm:p-12">
                <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-emerald-600">Welcome</p>
                <h1 class="text-3xl text-slate-900 sm:text-4xl">A private space for the two of you</h1>
                <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-slate-600">
                    UnionOS helps couples reflect individually, discover where they're aligned, and talk more
                    intentionally — with short weekly check-ins, monthly insights, and guided conversations.
                    No scores, streaks, or comparisons. Just the two of you.
                </p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <Link :href="route('register')" class="inline-flex items-center justify-center rounded-full bg-emerald-800 border border-emerald-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        Get started
                    </Link>
                    <Link :href="route('login')" class="inline-flex items-center justify-center rounded-full border border-stone-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-stone-50">
                        Log in
                    </Link>
                </div>
            </div>

            <!-- What you'll do -->
            <section class="surface-card divide-y divide-stone-100 overflow-hidden">
                <div class="px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">What's inside</h2>
                </div>

                <div class="grid gap-4 px-6 py-6 sm:grid-cols-3">
                    <div class="surface-muted p-5">
                        <p class="text-sm font-semibold text-slate-800">Weekly Check-In</p>
                        <p class="mt-2 text-xs leading-relaxed text-slate-600">
                            A short private reflection on your emotional availability, communication effort, and stress.
                        </p>
                    </div>
                    <div class="surface-muted p-5">
                        <p class="text-sm font-semibold text-slate-800">Monthly Insight</p>
                        <p class="mt-2 text-xs leading-relaxed text-slate-600">
                            See where you and your partner are aligned each month — without ever exposing raw scores.
                        </p>
                    </div>
                    <div class="surface-muted p-5">
                        <p class="text-sm font-semibold text-slate-800">Guided Conversations</p>
                        <p class="mt-2 text-xs leading-relaxed text-slate-600">
                            Structured, turn-based talks designed to be done together, in the same room.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Privacy note -->
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-5">
                <p class="text-sm font-medium text-emerald-800">Privacy is built in</p>
                <p class="mt-1 text-sm text-emerald-700">
                    Your partner never sees your raw entries, scores, or text. Only aggregated alignment signals
                    are shared — and only when both of you have submitted.
                </p>
            </div>

            <!-- CTA -->
            <div class="surface-card p-8 text-center">
                <p class="text-sm font-medium text-slate-800">Curious how it all works?</p>
                <p class="mt-1 text-sm text-slate-600">Read the full guide, or jump straight in and invite your partner.</p>
                <div class="mt-5 flex flex-col justify-center gap-3 sm:flex-row">
                    <Link :href="route('guide')" class="rounded-full border border-stone-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-stone-50">
                        Read the guide
                    </Link>
                    <Link :href="route('register')" class="rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700">
                        Create account
                    </Link>
                </div>
            </div>

        </div>
    </CoupleLayout>
</template>
