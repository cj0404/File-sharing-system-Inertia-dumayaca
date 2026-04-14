<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-base text-black placeholder-gray-400 shadow-sm transition duration-150 ease-in-out focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
        v-model="model"
        ref="input"
    />
</template>
