<template>
    <AdminLayout>
        <PageBreadcrumb pageTitle="Members" class="mb-4" />

        <MemberTable :members="members" />
    </AdminLayout>
</template>

<script setup>
import { defineOptions } from "vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import SidebarProvider from "@/components/layout/SidebarProvider.vue";
import MemberTable from "@/components/members/MemberTable.vue";
import PageBreadcrumb from "@/components/common/PageBreadcrumb.vue";

defineOptions({
    layout: SidebarProvider,
});

defineProps({
    members: {
        type: Array,
        required: true,
    },
});
</script>

<style lang="scss" scoped></style>
