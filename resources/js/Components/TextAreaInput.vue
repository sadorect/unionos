<script setup>
const model = defineModel({
    type: String,
    required: true,
});
</script>

<template>
    <textarea
        v-model="model"
        class="min-h-28 w-full rounded-[24px] border border-stone-300 bg-white px-4 py-3 text-sm leading-6 text-slate-900 shadow-sm outline-none transition placeholder:text-stone-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200"
    />
</template>
