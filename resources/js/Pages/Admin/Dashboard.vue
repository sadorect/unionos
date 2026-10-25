<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';

const props = defineProps({
    stats: { type: Object, required: true },
    users: { type: Array, required: true },
    recentLogs: { type: Array, required: true },
    health: { type: Object, required: true },
    metrics: { type: Object, required: true },
});

const tab = ref('users');

const statusClass = (status) => ({
    sent: 'text-emerald-700 bg-emerald-50 border-emerald-200',
    suppressed: 'text-amber-700 bg-amber-50 border-amber-200',
    failed: 'text-rose-700 bg-rose-50 border-rose-200',
})[status] ?? 'text-stone-600 bg-stone-50 border-stone-200';

const coupleStatusClass = (status) => ({
    active: 'text-emerald-700 bg-emerald-50 border-emerald-200',
    pending: 'text-amber-700 bg-amber-50 border-amber-200',
    dissolved: 'text-rose-700 bg-rose-50 border-rose-200',
})[status] ?? 'text-stone-500 bg-stone-50 border-stone-200';
</script>

<template>
    <CoupleLayout>
        <Head title="Admin Dashboard" />

        <div class="mx-auto max-w-5xl space-y-6 px-4 pb-16 sm:px-6">

            <!-- Header -->
            <div class="surface-card px-6 py-5">
                <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600">Administration</p>
                <h1 class="mt-1 text-xl font-semibold text-slate-900">Dashboard</h1>
            </div>

            <!-- Overview stats -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                <div v-for="item in [
                    { label: 'Users', value: stats.total_users },
                    { label: 'Active couples', value: stats.active_couples },
                    { label: 'Total couples', value: stats.total_couples },
                    { label: 'Conversations', value: stats.completed_conversations },
                    { label: 'Emails sent', value: stats.notifications_sent },
                ]" :key="item.label" class="surface-card px-5 py-4 text-center">
                    <p class="text-2xl font-semibold text-slate-900">{{ item.value }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ item.label }}</p>
                </div>
            </div>

            <!-- System health -->
            <div class="surface-card px-6 py-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-800">System health</p>
                    <span
                        class="rounded-full border px-2.5 py-0.5 text-xs font-semibold"
                        :class="health.status === 'ok' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700'"
                    >{{ health.status }}</span>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl bg-stone-50 px-4 py-3 text-center">
                        <p class="text-lg font-semibold" :class="health.checks.database === 'ok' ? 'text-emerald-700' : 'text-rose-700'">
                            {{ health.checks.database }}
                        </p>
                        <p class="text-xs text-slate-500">Database</p>
                    </div>
                    <div class="rounded-xl bg-stone-50 px-4 py-3 text-center">
                        <p class="text-lg font-semibold text-slate-800">{{ health.checks.pending_jobs }}</p>
                        <p class="text-xs text-slate-500">Pending jobs</p>
                    </div>
                    <div class="rounded-xl bg-stone-50 px-4 py-3 text-center">
                        <p class="text-lg font-semibold" :class="health.checks.failed_jobs > 0 ? 'text-rose-700' : 'text-slate-800'">
                            {{ health.checks.failed_jobs }}
                        </p>
                        <p class="text-xs text-slate-500">Failed jobs</p>
                    </div>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div v-for="(value, key) in metrics.completion_rates" :key="key" class="rounded-xl bg-stone-50 px-4 py-3 text-center">
                        <p class="text-lg font-semibold text-slate-800">{{ value }}%</p>
                        <p class="text-xs text-slate-500 capitalize">{{ key.replace(/_/g, ' ') }}</p>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="surface-card overflow-hidden">
                <div class="flex border-b border-stone-200">
                    <button
                        class="px-5 py-3.5 text-sm font-medium transition"
                        :class="tab === 'users' ? 'border-b-2 border-emerald-600 text-emerald-700' : 'text-slate-500 hover:text-slate-800'"
                        @click="tab = 'users'"
                    >
                        Users
                        <span class="ml-1.5 rounded-full bg-stone-100 px-2 py-0.5 text-xs text-slate-600">{{ users.length }}</span>
                    </button>
                    <button
                        class="px-5 py-3.5 text-sm font-medium transition"
                        :class="tab === 'logs' ? 'border-b-2 border-emerald-600 text-emerald-700' : 'text-slate-500 hover:text-slate-800'"
                        @click="tab = 'logs'"
                    >
                        Notification Log
                        <span class="ml-1.5 rounded-full bg-stone-100 px-2 py-0.5 text-xs text-slate-600">{{ recentLogs.length }}</span>
                    </button>
                </div>

                <!-- Users table (sm and up) -->
                <div v-show="tab === 'users'" class="hidden overflow-x-auto sm:block">
                    <table class="min-w-full divide-y divide-stone-100 text-sm">
                        <thead>
                            <tr class="bg-stone-50 text-left text-xs font-medium uppercase tracking-wider text-stone-500">
                                <th class="px-5 py-3">Name</th>
                                <th class="px-5 py-3">Email</th>
                                <th class="px-5 py-3">Couple</th>
                                <th class="px-5 py-3">Joined</th>
                                <th class="px-5 py-3">Role</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="hover:bg-stone-50"
                                :class="{ 'opacity-50': user.deleted_at }"
                            >
                                <td class="px-5 py-3.5 font-medium text-slate-800">
                                    {{ user.name }}
                                    <span v-if="user.deleted_at" class="ml-1.5 text-xs text-rose-500">(deleted)</span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ user.email }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="rounded-full border px-2.5 py-0.5 text-xs font-medium" :class="coupleStatusClass(user.couple_status)">
                                        {{ user.couple_status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500">{{ user.created_at }}</td>
                                <td class="px-5 py-3.5">
                                    <span v-if="user.is_admin" class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Admin</span>
                                    <span v-else class="text-xs text-stone-400">User</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <Link
                                        :href="route('admin.toggle-admin', user.id)"
                                        method="post"
                                        as="button"
                                        preserve-scroll
                                        class="inline-flex items-center rounded-full px-2.5 py-1.5 text-xs text-slate-500 underline decoration-stone-300 underline-offset-2 hover:bg-stone-100 hover:text-slate-900"
                                    >
                                        {{ user.is_admin ? 'Revoke admin' : 'Make admin' }}
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="!users.length">
                                <td colspan="6" class="px-5 py-6 text-center text-sm text-slate-400">No users yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Users cards (mobile) -->
                <div v-show="tab === 'users'" class="divide-y divide-stone-100 sm:hidden">
                    <div
                        v-for="user in users"
                        :key="user.id"
                        class="p-4"
                        :class="{ 'opacity-50': user.deleted_at }"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate font-medium text-slate-800">
                                    {{ user.name }}
                                    <span v-if="user.deleted_at" class="ml-1 text-xs text-rose-500">(deleted)</span>
                                </p>
                                <p class="truncate text-xs text-slate-500">{{ user.email }}</p>
                            </div>
                            <span v-if="user.is_admin" class="shrink-0 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Admin</span>
                            <span v-else class="shrink-0 text-xs text-stone-400">User</span>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                            <span class="rounded-full border px-2.5 py-0.5 font-medium" :class="coupleStatusClass(user.couple_status)">
                                {{ user.couple_status }}
                            </span>
                            <span>Joined {{ user.created_at }}</span>
                        </div>
                        <Link
                            :href="route('admin.toggle-admin', user.id)"
                            method="post"
                            as="button"
                            preserve-scroll
                            class="mt-3 inline-flex items-center rounded-full border border-stone-200 px-3.5 py-2 text-xs font-medium text-slate-600 hover:bg-stone-50"
                        >
                            {{ user.is_admin ? 'Revoke admin' : 'Make admin' }}
                        </Link>
                    </div>
                    <div v-if="!users.length" class="p-6 text-center text-sm text-slate-400">No users yet.</div>
                </div>

                <!-- Notification log table (sm and up) -->
                <div v-show="tab === 'logs'" class="hidden overflow-x-auto sm:block">
                    <table class="min-w-full divide-y divide-stone-100 text-sm">
                        <thead>
                            <tr class="bg-stone-50 text-left text-xs font-medium uppercase tracking-wider text-stone-500">
                                <th class="px-5 py-3">User</th>
                                <th class="px-5 py-3">Kind</th>
                                <th class="px-5 py-3">Channel</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Reason</th>
                                <th class="px-5 py-3">Sent</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <tr v-for="log in recentLogs" :key="log.id" class="hover:bg-stone-50">
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-slate-800">{{ log.user_name }}</p>
                                    <p class="text-xs text-slate-400">{{ log.user_email }}</p>
                                </td>
                                <td class="px-5 py-3.5 capitalize text-slate-600">{{ log.kind }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ log.channel }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="rounded-full border px-2.5 py-0.5 text-xs font-medium" :class="statusClass(log.status)">
                                        {{ log.status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">{{ log.reason ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">{{ log.created_at }}</td>
                            </tr>
                            <tr v-if="!recentLogs.length">
                                <td colspan="6" class="px-5 py-6 text-center text-sm text-slate-400">No notifications dispatched yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Notification log cards (mobile) -->
                <div v-show="tab === 'logs'" class="divide-y divide-stone-100 sm:hidden">
                    <div v-for="log in recentLogs" :key="log.id" class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate font-medium text-slate-800">{{ log.user_name }}</p>
                                <p class="truncate text-xs text-slate-400">{{ log.user_email }}</p>
                            </div>
                            <span class="shrink-0 rounded-full border px-2.5 py-0.5 text-xs font-medium" :class="statusClass(log.status)">
                                {{ log.status }}
                            </span>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                            <span class="capitalize">{{ log.kind }}</span>
                            <span>{{ log.channel }}</span>
                            <span>{{ log.created_at }}</span>
                        </div>
                        <p v-if="log.reason" class="mt-2 text-xs text-slate-500">{{ log.reason }}</p>
                    </div>
                    <div v-if="!recentLogs.length" class="p-6 text-center text-sm text-slate-400">No notifications dispatched yet.</div>
                </div>
            </div>

        </div>
    </CoupleLayout>
</template>
