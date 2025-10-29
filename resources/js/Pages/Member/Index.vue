<script setup>
import { defineOptions } from "vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import SidebarProvider from "@/components/layout/SidebarProvider.vue";
import MemberDataTable from "@/components/members/MemberDataTable.vue";
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

<template>
    <AdminLayout>
        <PageBreadcrumb pageTitle="Members" class="mb-4" />

        <MemberDataTable :members="members" />
    </AdminLayout>
</template>

<style lang="scss" scoped></style>
