<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
</script>

<template>
    <CoupleLayout>
        <Head title="User Guide" />

        <div class="mx-auto max-w-3xl space-y-10 px-4 pb-16 sm:px-6">

            <!-- Hero -->
            <div class="surface-card p-8 text-center">
                <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-emerald-600">User Guide</p>
                <h1 class="text-2xl font-semibold text-slate-900">How UnionOS works</h1>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-slate-600">
                    UnionOS is a private, calm space for two partners to reflect individually, discover alignment,
                    and talk more intentionally — without scores, streaks, or comparisons.
                </p>
                <div v-if="!user" class="mt-6 flex justify-center gap-4">
                    <Link :href="route('register')" class="rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition">
                        Create account
                    </Link>
                    <Link :href="route('login')" class="rounded-full border border-stone-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-stone-50 transition">
                        Log in
                    </Link>
                </div>
            </div>

            <!-- Privacy first note -->
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-5">
                <p class="text-sm font-medium text-emerald-800">Privacy is built in</p>
                <p class="mt-1 text-sm text-emerald-700">
                    Your partner never sees your raw entries, scores, or text. Only aggregated alignment signals
                    are shared — and only when both of you have submitted. There are no leaderboards, no AI analysis,
                    and no social features.
                </p>
            </div>

            <!-- Section 1: Getting started -->
            <section class="surface-card divide-y divide-stone-100 overflow-hidden">
                <div class="px-6 py-5">
                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-600">01</span>
                    <h2 class="mt-1 text-lg font-semibold text-slate-900">Getting started</h2>
                </div>

                <div class="space-y-5 px-6 py-6 text-sm text-slate-700">
                    <div>
                        <p class="font-medium text-slate-900">Step 1 — Create your account</p>
                        <p class="mt-1 leading-relaxed text-slate-600">
                            Register with your name, email, and a password. You'll also answer a simple arithmetic
                            question — UnionOS uses its own verification instead of third-party CAPTCHAs.
                        </p>
                    </div>

                    <div>
                        <p class="font-medium text-slate-900">Step 2 — Create your couple space</p>
                        <p class="mt-1 leading-relaxed text-slate-600">
                            After registering, you create a couple space. This gives you a private shared environment
                            that both you and your partner will use. Your couple space starts in <em>pending</em> state
                            until your partner joins.
                        </p>
                    </div>

                    <div>
                        <p class="font-medium text-slate-900">Step 3 — Invite your partner</p>
                        <p class="mt-1 leading-relaxed text-slate-600">
                            You'll get a private invite link valid for 72 hours. Share it directly with your partner
                            (by message, email, or in person). Your partner follows the link, creates their own account
                            or logs in, and accepts the invite. Your couple space becomes <em>active</em> and you can
                            both start using the features.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 2: Weekly Check-In -->
            <section class="surface-card divide-y divide-stone-100 overflow-hidden">
                <div class="px-6 py-5">
                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-600">02</span>
                    <h2 class="mt-1 text-lg font-semibold text-slate-900">Weekly Check-In</h2>
                    <p class="mt-1 text-sm text-slate-500">A short private reflection, once a week</p>
                </div>

                <div class="space-y-5 px-6 py-6 text-sm text-slate-700">
                    <p class="leading-relaxed text-slate-600">
                        Each week you rate three dimensions of your current emotional state using a simple 1–5 scale:
                    </p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Emotional availability</strong> — how open and present you feel</span></li>
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Communication effort</strong> — how much energy you've put into connecting</span></li>
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Stress level</strong> — how much outside pressure you're carrying</span></li>
                    </ul>
                    <p class="leading-relaxed text-slate-600">
                        You can also add three optional text responses: something you appreciated, something that felt
                        hard, and an intention for the coming week.
                    </p>
                    <div class="rounded-xl border border-stone-200 bg-stone-50 px-4 py-3">
                        <p class="text-xs font-medium text-slate-700">Privacy</p>
                        <p class="mt-0.5 text-xs text-slate-600">Your partner never sees your weekly entries — not the scores, not the text. This space is entirely your own.</p>
                    </div>
                    <p class="leading-relaxed text-slate-600">
                        You can edit your entry at any time within the same week. After the week closes, entries are locked
                        and you can review your history with trend charts on your personal dashboard.
                    </p>
                </div>
            </section>

            <!-- Section 3: Monthly Insight -->
            <section class="surface-card divide-y divide-stone-100 overflow-hidden">
                <div class="px-6 py-5">
                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-600">03</span>
                    <h2 class="mt-1 text-lg font-semibold text-slate-900">Monthly Insight</h2>
                    <p class="mt-1 text-sm text-slate-500">Shared alignment, without exposing raw scores</p>
                </div>

                <div class="space-y-5 px-6 py-6 text-sm text-slate-700">
                    <p class="leading-relaxed text-slate-600">
                        Once a month each partner privately rates four dimensions of your relationship:
                    </p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Felt heard</strong></span></li>
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Felt appreciated</strong></span></li>
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Conflict safety</strong> — how safe it felt to bring up disagreements</span></li>
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Emotional closeness</strong></span></li>
                    </ul>
                    <p class="leading-relaxed text-slate-600">
                        After you submit, you'll see a waiting page. Once your partner also submits,
                        an <strong>alignment card</strong> is revealed to both of you showing which dimensions
                        are <em>aligned</em> and which may need a conversation.
                    </p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                            <p class="text-xs font-semibold text-emerald-700">Aligned</p>
                            <p class="mt-0.5 text-xs text-slate-600">Both partners rated this dimension within 1 point of each other.</p>
                        </div>
                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                            <p class="text-xs font-semibold text-amber-700">Needs conversation</p>
                            <p class="mt-0.5 text-xs text-slate-600">Ratings differed by more than 1 point — worth talking about.</p>
                        </div>
                    </div>
                    <div class="rounded-xl border border-stone-200 bg-stone-50 px-4 py-3">
                        <p class="text-xs font-medium text-slate-700">Privacy</p>
                        <p class="mt-0.5 text-xs text-slate-600">Only the alignment outcome is shared — neither partner ever sees the other's actual scores.</p>
                    </div>
                </div>
            </section>

            <!-- Section 4: Guided Conversations -->
            <section class="surface-card divide-y divide-stone-100 overflow-hidden">
                <div class="px-6 py-5">
                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-600">04</span>
                    <h2 class="mt-1 text-lg font-semibold text-slate-900">Guided Conversations</h2>
                    <p class="mt-1 text-sm text-slate-500">Structured, turn-based talks for two — in person</p>
                </div>

                <div class="space-y-5 px-6 py-6 text-sm text-slate-700">
                    <p class="leading-relaxed text-slate-600">
                        Guided conversations are designed to be done together, in the same room. One partner holds the device
                        and both take turns responding to prompts. There are three conversation packs:
                    </p>
                    <div class="space-y-3">
                        <div class="rounded-xl border border-stone-200 bg-white px-4 py-3">
                            <p class="font-medium text-slate-800">Weekly Reset <span class="ml-2 text-xs text-stone-500">10–15 min · Reconnection</span></p>
                            <p class="mt-1 text-xs text-slate-600">A gentle check-in to end the week together and re-anchor to each other.</p>
                        </div>
                        <div class="rounded-xl border border-stone-200 bg-white px-4 py-3">
                            <p class="font-medium text-slate-800">Feeling Unheard <span class="ml-2 text-xs text-stone-500">12–15 min · Repair</span></p>
                            <p class="mt-1 text-xs text-slate-600">For moments when one or both partners feel disconnected or dismissed.</p>
                        </div>
                        <div class="rounded-xl border border-stone-200 bg-white px-4 py-3">
                            <p class="font-medium text-slate-800">Appreciation & Reconnection <span class="ml-2 text-xs text-stone-500">8–12 min · Appreciation</span></p>
                            <p class="mt-1 text-xs text-slate-600">A short, warm exchange to strengthen appreciation and closeness.</p>
                        </div>
                    </div>

                    <div>
                        <p class="font-medium text-slate-900">How a session works</p>
                        <ol class="mt-2 space-y-2 pl-4">
                            <li class="flex gap-2"><span class="font-medium text-emerald-600">1.</span><span>One partner picks a pack and starts a session.</span></li>
                            <li class="flex gap-2"><span class="font-medium text-emerald-600">2.</span><span>Both confirm the ground rules — listen without interrupting, respond honestly, stay present.</span></li>
                            <li class="flex gap-2"><span class="font-medium text-emerald-600">3.</span><span>Prompts alternate between partners. A timer helps keep each response focused (3 minutes).</span></li>
                            <li class="flex gap-2"><span class="font-medium text-emerald-600">4.</span><span>The session closes with a round where both partners share an appreciation.</span></li>
                        </ol>
                    </div>
                    <p class="leading-relaxed text-slate-600">
                        Sessions can be paused and resumed. A session left inactive for 24 hours is automatically marked abandoned.
                        Completed conversations appear on your couple dashboard.
                    </p>
                </div>
            </section>

            <!-- Section 5: Dashboards -->
            <section class="surface-card divide-y divide-stone-100 overflow-hidden">
                <div class="px-6 py-5">
                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-600">05</span>
                    <h2 class="mt-1 text-lg font-semibold text-slate-900">Your Progress Dashboards</h2>
                </div>

                <div class="space-y-5 px-6 py-6 text-sm text-slate-700">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-stone-200 bg-stone-50 px-4 py-4">
                            <p class="font-semibold text-slate-800">My Progress</p>
                            <p class="mt-1 text-xs text-slate-600">
                                Only visible to you. Shows your weekly check-in history with trend sparklines
                                for emotional availability, communication effort, and stress. Tracks your
                                consistency over time.
                            </p>
                        </div>
                        <div class="rounded-xl border border-stone-200 bg-stone-50 px-4 py-4">
                            <p class="font-semibold text-slate-800">Our Progress</p>
                            <p class="mt-1 text-xs text-slate-600">
                                Visible to both partners. Shows monthly alignment trends for the last 6 months,
                                the count of completed conversations, and your conversation history.
                            </p>
                        </div>
                    </div>
                    <p class="leading-relaxed text-slate-600">
                        No raw data from your partner is ever shown on either dashboard. The couple dashboard
                        shows only shared outcomes — alignment signals and conversation completions.
                    </p>
                </div>
            </section>

            <!-- Section 6: Notifications -->
            <section class="surface-card divide-y divide-stone-100 overflow-hidden">
                <div class="px-6 py-5">
                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-600">06</span>
                    <h2 class="mt-1 text-lg font-semibold text-slate-900">Notification Preferences</h2>
                </div>

                <div class="space-y-4 px-6 py-6 text-sm text-slate-700">
                    <p class="leading-relaxed text-slate-600">
                        UnionOS can send gentle email reminders so features are available when it suits you.
                        You control each channel independently:
                    </p>
                    <ul class="space-y-2 pl-4">
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Weekly reminder</strong> — choose the day of week and hour</span></li>
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Monthly reminder</strong> — choose the day of month and hour</span></li>
                        <li class="flex gap-2"><span class="mt-0.5 text-emerald-500">→</span><span><strong>Conversation suggestion</strong> — sent when a monthly alignment is ready and no active conversation exists</span></li>
                    </ul>
                    <p class="leading-relaxed text-slate-600">
                        All reminders respect your timezone setting and a 24-hour cooldown between notifications.
                        You can enable <strong>Quiet mode</strong> at any time to pause all reminders without
                        changing your preferences.
                    </p>
                </div>
            </section>

            <!-- CTA for guests -->
            <div v-if="!user" class="surface-card p-8 text-center">
                <p class="text-sm font-medium text-slate-800">Ready to get started?</p>
                <p class="mt-1 text-sm text-slate-600">Create an account, set up your space, and invite your partner.</p>
                <div class="mt-5 flex justify-center gap-4">
                    <Link :href="route('register')" class="rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition">
                        Create account
                    </Link>
                    <Link :href="route('login')" class="rounded-full border border-stone-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-stone-50 transition">
                        Log in
                    </Link>
                </div>
            </div>

        </div>
    </CoupleLayout>
</template>
