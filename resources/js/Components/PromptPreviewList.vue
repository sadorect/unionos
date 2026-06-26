<script setup>
defineProps({
    prompts: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="space-y-3">
        <article
            v-for="(prompt, index) in prompts"
            :key="prompt.key"
            class="surface-muted space-y-3 p-4"
        >
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Prompt {{ index + 1 }}</span>
                <span class="rounded-full border border-stone-200 px-3 py-1 text-xs text-slate-600">{{ prompt.turnLabel }}</span>
            </div>
            <h3 class="text-lg text-slate-900">{{ prompt.label }}</h3>
            <p class="text-sm leading-6 text-slate-700">{{ prompt.text }}</p>
            <p v-if="prompt.instruction" class="text-sm text-slate-500">{{ prompt.instruction }}</p>
        </article>
    </div>
</template>
