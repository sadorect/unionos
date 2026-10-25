<script setup>
import { computed, ref, useAttrs } from 'vue';

defineOptions({ inheritAttrs: false });

const model = defineModel({ type: String, required: true });
const visible = ref(false);
const input = ref(null);

const attrs = useAttrs();
const inputAttrs = computed(() => {
    const { class: _, ...rest } = attrs;
    return rest;
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <div class="relative" :class="attrs.class">
        <input
            class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 pr-12 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-stone-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200"
            v-model="model"
            ref="input"
            :type="visible ? 'text' : 'password'"
            v-bind="inputAttrs"
        />
        <button
            type="button"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 transition hover:text-slate-700"
            :aria-label="visible ? 'Hide password' : 'Show password'"
            @click="visible = !visible"
            tabindex="-1"
        >
            <svg v-if="!visible" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
        </button>
    </div>
</template>
