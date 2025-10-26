<script setup>
const props = defineProps({
    type: {
        type: String,
        default: "button",
    },
    class: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <button
        :type="type"
        class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
        :class="class"
    >
        <slot />
    </button>
</template>

<style lang="scss" scoped></style>
