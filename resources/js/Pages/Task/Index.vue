<script setup>
import { computed, defineOptions, ref } from "vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import SidebarProvider from "@/components/layout/SidebarProvider.vue";
import PageBreadcrumb from "@/components/common/PageBreadcrumb.vue";
import Button from "@/components/ui/Button.vue";
import PlusIcon from "@/icons/PlusIcon.vue";
import CreateTaskModal from "@/components/tasks/CreateTaskModal.vue";
import TaskDataTable from "@/components/tasks/TaskDataTable.vue";

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

const computedProjects = computed(() => {
    return props.projects.map((project) => ({
        value: project.id,
        label: project.name,
    }));
});
const computedUsers = computed(() => {
    return props.users.map((user) => ({
        value: user.id,
        label: user.name,
    }));
});
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
            v-if="isTaskModalOpen"
            :isTaskModalOpen="isTaskModalOpen"
            @update:isTaskModalOpen="isTaskModalOpen = false"
            :projects="computedProjects"
            :users="computedUsers"
        />
        <TaskDataTable
            :tasks="tasks"
            :projects="computedProjects"
            :users="computedUsers"
        />
    </AdminLayout>
</template>

<style lang="scss" scoped></style>
