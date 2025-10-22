<template>
    <slot></slot>
</template>

<script setup>
import { useSidebarProvider } from "@/composables/useSidebar";
useSidebarProvider();
</script>

<style lang="scss" scoped></style>
