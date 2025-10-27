<template>
    <AdminLayout>
        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 space-y-6">
                <DashboardHeader />
            </div>
            <div class="col-span-12 xl:col-span-5"></div>

            <div class="col-span-12">
                <!-- <TaskTable /> -->
                <ProjectDataTable :projects="projects" />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/components/layout/AdminLayout.vue";
import SidebarProvider from "@/components/layout/SidebarProvider.vue";
import DashboardHeader from "@/components/dashboard/DashboardHeader.vue";
import ProjectDataTable from "@/components/dashboard/ProjectDataTable.vue";
// import TaskTable from "@/components/tasks/TaskTable.vue";

defineOptions({
    layout: SidebarProvider,
});

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
    tasks: {
        type: Array,
        required: true,
    },
});
</script>

<style lang="scss" scoped></style>
