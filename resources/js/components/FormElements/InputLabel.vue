<script setup>
const props = defineProps({
    for: {
        type: String,
        required: false,
    },
    label: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <label
        :for="for"
        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
    >
        <span v-if="label">{{ label }}</span>
        <span v-else> <slot /> </span>
    </label>
</template>

<style lang="scss" scoped></style>
