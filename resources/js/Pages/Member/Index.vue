<template>
    <AdminLayout>
        <PageBreadcrumb pageTitle="Members" class="mb-4" />

        <MemberTable />
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
</script>

<style lang="scss" scoped></style>
