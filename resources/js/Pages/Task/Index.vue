<script setup>
import { defineOptions, ref } from "vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import SidebarProvider from "@/components/layout/SidebarProvider.vue";
import PageBreadcrumb from "@/components/common/PageBreadcrumb.vue";
import TaskTable from "@/components/tasks/TaskTable.vue";
import Button from "@/components/ui/Button.vue";
import PlusIcon from "@/icons/PlusIcon.vue";
import CreateTaskModal from "@/components/tasks/CreateTaskModal.vue";

defineOptions({
    layout: SidebarProvider,
});

const props = defineProps({
    tasks: {
        type: Array,
        required: true,
    },
    projects: {
        type: Array,
        required: false,
        default: [],
    },
    users: {
        type: Array,
        required: false,
        default: [],
    },
});

const isTaskModalOpen = ref(false);
</script>

<template>
    <AdminLayout>
        <PageBreadcrumb pageTitle="Tasks" class="mb-4" />

        <div class="my-6">
            <Button
                size="sm"
                variant="outline"
                :endIcon="PlusIcon"
                @click="isTaskModalOpen = true"
            >
                Assign New Task
            </Button>
        </div>
        <CreateTaskModal
            :isTaskModalOpen="isTaskModalOpen"
            @update:isTaskModalOpen="isTaskModalOpen = false"
            :projects="projects"
            :users="users"
        />
        <TaskTable :tasks="tasks" />
    </AdminLayout>
</template>

<style lang="scss" scoped></style>
