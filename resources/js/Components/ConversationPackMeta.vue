<script setup>
defineProps({
    pack: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.18em] text-slate-400">
        <span>{{ pack.duration }}</span>
        <span>&bull;</span>
        <span>{{ pack.promptCount }} prompts</span>
        <template v-if="pack.purposeTag">
            <span>&bull;</span>
            <span>{{ pack.purposeTag }}</span>
        </template>
    </div>
</template>
