<script setup>
import StatusPill from '@/Components/StatusPill.vue';
import { ref } from 'vue';

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const expanded = ref(false);

const details = [
    ['One thing I appreciated this week', 'appreciationText'],
    ['One thing I struggled with', 'struggleText'],
    ['One thing I want to do better next week', 'intentionText'],
];
</script>

<template>
    <article class="surface-card p-5 sm:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="text-xl text-slate-900">{{ item.weekLabel }}</h3>
                <p v-if="item.savedAt" class="mt-1 text-sm text-slate-500">
                    Saved {{ new Date(item.savedAt).toLocaleString() }}
                </p>
            </div>
            <StatusPill value="saved" />
        </div>

        <div class="mt-5 flex flex-wrap gap-2">
            <span class="rounded-full border border-stone-300 bg-stone-50 px-3 py-1.5 text-xs text-slate-600">
                Emotional {{ item.emotionalAvailability }}/5
            </span>
            <span class="rounded-full border border-stone-300 bg-stone-50 px-3 py-1.5 text-xs text-slate-600">
                Communication {{ item.communicationEffort }}/5
            </span>
            <span class="rounded-full border border-stone-300 bg-stone-50 px-3 py-1.5 text-xs text-slate-600">
                Stress {{ item.stressLevel }}/5
            </span>
        </div>

        <button
            type="button"
            class="mt-5 text-sm font-medium text-slate-700 underline decoration-stone-300 underline-offset-4"
            @click="expanded = !expanded"
        >
            {{ expanded ? 'Hide details' : 'View details' }}
        </button>

        <div v-if="expanded" class="mt-5 space-y-4 border-t border-stone-200 pt-5">
            <div v-for="[label, key] in details" :key="key" class="surface-muted p-4">
                <p class="text-sm font-semibold text-slate-700">{{ label }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ item[key] || 'No written reflection for this prompt.' }}
                </p>
            </div>
        </div>
    </article>
</template>
