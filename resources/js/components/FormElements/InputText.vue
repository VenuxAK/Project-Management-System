<script setup>
const props = defineProps({
    type: {
        type: String,
        default: "text",
    },
    id: {
        type: String,
        required: false,
    },
    placeholder: {
        type: String,
        required: false,
    },
    disabled: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const model = defineModel({
    type: String,
    required: true,
});
</script>

<template>
    <input
        v-model="model"
        :type="type"
        :id="id"
        :placeholder="placeholder"
        :disabled="disabled"
        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
    />
</template>

<style lang="scss" scoped></style>
