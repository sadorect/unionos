<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({
    seconds: {
        type: Number,
        default: null,
    },
});

const remaining = ref(props.seconds);
let timer = null;

const label = computed(() => {
    if (remaining.value === null) {
        return '';
    }

    const minutes = Math.floor(remaining.value / 60);
    const seconds = remaining.value % 60;

    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
});

onMounted(() => {
    if (remaining.value === null) {
        return;
    }

    timer = window.setInterval(() => {
        remaining.value = Math.max((remaining.value ?? 0) - 1, 0);
    }, 1000);
});

onBeforeUnmount(() => {
    if (timer !== null) {
        window.clearInterval(timer);
    }
});
</script>

<template>
    <div v-if="label" class="inline-flex rounded-full border border-stone-300 bg-stone-50 px-3 py-1.5 text-sm text-slate-600">
        {{ label }}
    </div>
</template>
