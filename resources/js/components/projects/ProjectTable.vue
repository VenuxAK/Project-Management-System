<script setup>
import DataTable from "@/components/ui/DataTable.vue";
import { useProjectManager } from "@/composables/useProjectManager";
import EditProjectModal from "@/components/projects/EditProjectModal.vue";
import { computed, ref } from "vue";

const { deleteProject } = useProjectManager();

const props = defineProps({
    projects: {
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
        };
    });
});

const onDeleteProject = (project) => {
    deleteProject(project.id);
};

const isEditProjectModalOpen = ref(false);
const project = ref({});
const onEditProject = (pj) => {
    // console.log(pj.id);
    isEditProjectModalOpen.value = true;
    project.value = pj;
};
</script>

<template>
    <EditProjectModal
        v-if="isEditProjectModalOpen"
        :isEditProjectModalOpen="isEditProjectModalOpen"
        @update:isEditProjectModalOpen="(e) => (isEditProjectModalOpen = e)"
        :project="project"
    />
    <DataTable
        :columns="cols"
        :rows="rows"
        :initialPageSize="5"
        :editAction="true"
        :deleteAction="true"
        @delete="onDeleteProject($event)"
        @edit="onEditProject($event)"
    >
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
