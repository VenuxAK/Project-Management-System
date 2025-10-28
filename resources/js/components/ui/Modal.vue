<template>
    <div
        class="fixed inset-0 flex items-center justify-center overflow-y-auto modal z-99999"
    >
        <div
            class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
            aria-hidden="true"
            @click="$emit('close')"
        ></div>
        <div
            class="no-scrollbar relative w-full max-w-[680px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
        >
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script setup>
// No additional setup needed
</script>
