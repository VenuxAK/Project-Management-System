<script setup>
import { ref } from "vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
import SidebarProvider from "@/components/layout/SidebarProvider.vue";
import ProjectTable from "@/components/projects/ProjectTable.vue";
import PageBreadcrumb from "@/components/common/PageBreadcrumb.vue";
import Button from "@/components/ui/Button.vue";
import PlusIcon from "@/icons/PlusIcon.vue";
import CreateProjectModal from "@/components/projects/CreateProjectModal.vue";
import { usePage } from "@inertiajs/vue3";

defineOptions({
    layout: SidebarProvider,
});

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
    project: {
        type: Object,
        required: false,
    },
    users: {
        type: Array,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
});

const isProjectModalOpen = ref(false);
</script>

<template>
    <AdminLayout>
        <PageBreadcrumb pageTitle="Projects" class="mb-4" />
        <div class="my-6">
            <Button
                v-if="!usePage().props.auth.user.roles?.some(r => r.id === 3)"
                size="sm"
                variant="outline"
                :endIcon="PlusIcon"
                @click="isProjectModalOpen = true"
            >
                Create a new project
            </Button>
        </div>
        <CreateProjectModal
            v-if="isProjectModalOpen"
            :users="users"
            :roles="roles"
            :isProjectModalOpen="isProjectModalOpen"
            @update:isProjectModalOpen="(e) => (isProjectModalOpen = e)"
        />
        <ProjectTable :projects="projects" />
    </AdminLayout>
</template>

<style lang="scss" scoped></style>
