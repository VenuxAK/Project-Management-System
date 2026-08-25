<script setup>
import DataTable from "@/components/ui/DataTable.vue";
import { useProjectManager } from "@/composables/useProjectManager";
import EditProjectModal from "@/components/projects/EditProjectModal.vue";
import ManageProjectMembersModal from "@/components/projects/ManageProjectMembersModal.vue";
import { computed, ref } from "vue";
import PenIcon from "@/icons/PenIcon.vue";
import TrashIcon from "@/icons/TrashIcon.vue";
import UserGroupIcon from "@/icons/UserGroupIcon.vue";

const { deleteProject } = useProjectManager();

const props = defineProps({
    projects: {
        type: Array,
        required: true,
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

const cols = [
    { label: "ID", key: "id", sortable: true },
    { label: "Name", key: "name", sortable: false },
    { label: "Status", key: "status", sortable: false },
    { label: "Start Date", key: "start_date", sortable: true },
    { label: "Deadline", key: "deadline", sortable: true },
];

const rows = computed(() => {
    return props.projects.map((project) => {
        return {
            id: project.id,
            name: project.name,
            status: project.status,
            start_date: project.start_date,
            deadline: project.deadline,
            can_manage_members: project.can_manage_members ?? false,
        };
    });
});

const onDeleteProject = (project) => {
    deleteProject(project.id);
};

const isEditProjectModalOpen = ref(false);
const selectedProject = ref({});
const onEditProject = (pj) => {
    selectedProject.value = findOriginal(pj.id);
    isEditProjectModalOpen.value = true;
};

const isMembersModalOpen = ref(false);
const onManageMembers = (pj) => {
    selectedProject.value = findOriginal(pj.id);
    isMembersModalOpen.value = true;
};

const findOriginal = (id) =>
    props.projects.find((project) => project.id === id) ?? {};
</script>

<template>
    <EditProjectModal
        v-if="isEditProjectModalOpen"
        :isEditProjectModalOpen="isEditProjectModalOpen"
        @update:isEditProjectModalOpen="(e) => (isEditProjectModalOpen = e)"
        :project="selectedProject"
    />
    <ManageProjectMembersModal
        v-if="isMembersModalOpen"
        :isModalOpen="isMembersModalOpen"
        @update:isModalOpen="(e) => (isMembersModalOpen = e)"
        :project="selectedProject"
        :users="users"
        :roles="roles"
    />
    <DataTable
        :columns="cols"
        :rows="rows"
        :initialPageSize="5"
        :editAction="true"
        :deleteAction="true"
        @delete="onDeleteProject($event)"
    >
        <template #actions="{ row }">
            <button
                v-if="row.can_manage_members"
                aria-label="Manage members"
                title="Manage members"
                class="text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-white/90"
                @click="onManageMembers(row)"
            >
                <UserGroupIcon />
            </button>
            <button
                aria-label="Edit project"
                title="Edit project"
                class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                @click="onEditProject(row)"
            >
                <PenIcon />
            </button>
            <button
                aria-label="Delete project"
                title="Delete project"
                class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500"
                @click="onDeleteProject(row)"
            >
                <TrashIcon />
            </button>
        </template>
        <template #cell-status="{ row }">
            <div class="font-medium">
                <span
                    class="text-xs font-medium me-2 px-2.5 py-1.5 rounded-full dark:text-white"
                    :class="{
                        'bg-green-100 text-green-800 dark:bg-green-900':
                            row.status === 'active',
                        'bg-gray-100 text-yellow-800 dark:bg-gray-700':
                            row.status === 'on-hold',
                        'bg-red-100 text-red-800 dark:bg-red-900':
                            row.status === 'planning',
                        'bg-brand-100 text-brand-800 dark:bg-brand-900 ':
                            row.status === 'completed',
                    }"
                >
                    {{ row.status }}
                </span>
            </div>
        </template>
    </DataTable>
</template>
