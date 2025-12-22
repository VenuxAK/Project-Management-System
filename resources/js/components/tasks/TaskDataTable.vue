<script setup>
import { computed, ref } from "vue";
import DataTable from "@/components/ui/DataTable.vue";
import { useTaskManager } from "@/composables/useTaskManager.js";
import EditTaskModal from "@/components/tasks/EditTaskModal.vue";

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
const { deleteTask, updateStatus } = useTaskManager();

const cols = [
    { label: "#ID", key: "id", sortable: true },
    { label: "Name", key: "name", sortable: false },
    { label: "Priority", key: "priority", sortable: true },
    { label: "Status", key: "status", sortable: true },
    { label: "Assignee", key: "assignee", sortable: false },
    { label: "Start Date", key: "start_date", sortable: true },
    { label: "Due Date", key: "due_date", sortable: true },
];

const rows = computed(() => {
    return props.tasks.map((task) => {
        return {
            id: task.id,
            name: task.name,
            priority: task.priority,
            status: task.status,
            assignee: task.assignee.name,
            start_date: task.start_date,
            due_date: task.due_date,
        };
    });
});

const onDeleteTask = (task) => {
    deleteTask(task.id);
};

const isEditTaskModalOpen = ref(false);
const selectedTask = ref({});
const onEditTask = (tk) => {
    isEditTaskModalOpen.value = true;
    const tmp_tk = props.tasks.filter((task) => {
        return task.id === tk.id;
    });
    selectedTask.value = tmp_tk.map((tk) => {`  `
        return {
            id: tk.id,
            name: tk.name,
            status: tk.status,
            priority: tk.priority,
            assigned_to: tk.assigned_to,
            project_id: tk.project_id,
            start_date: tk.start_date,
            due_date: tk.due_date,
        };
    })[0];
};

const onUpdateStatus = (task) => {
    updateStatus(task.id);
};
</script>
<template>
    <EditTaskModal
        v-if="isEditTaskModalOpen"
        :isEditTaskModalOpen="isEditTaskModalOpen"
        @update:isEditTaskModalOpen="(e) => (isEditTaskModalOpen = e)"
        :task="selectedTask"
        :users="users"
        :projects="projects"
    />
    <DataTable
        :columns="cols"
        :rows="rows"
        :initialPageSize="5"
        :deleteAction="true"
        :editAction="true"
        :updateStatusAction="true"
        @delete="onDeleteTask($event)"
        @edit="onEditTask($event)"
        @updateStatus="onUpdateStatus($event)"
    >
        <template #title>Projects</template>

        <template #cell-priority="{ row }">
            <div class="font-medium">
                <span
                    class="text-xs font-medium me-2 px-2.5 py-1.5 rounded-full dark:text-white"
                    :class="{
                        'bg-green-100 text-green-800 dark:bg-green-900':
                            row.priority === 'low',
                        'bg-gray-100 text-yellow-800 dark:bg-gray-700':
                            row.priority === 'medium',
                        'bg-red-100 text-red-800 dark:bg-red-900':
                            row.priority === 'high',
                    }"
                >
                    {{ row.priority }}
                </span>
            </div>
        </template>

        <template #cell-status="{ row }">
            <div class="font-medium">
                <span
                    class="text-xs font-medium me-2 px-2.5 py-1.5 rounded-full dark:text-white"
                    :class="{
                        'bg-green-100 text-green-800 dark:bg-green-900':
                            row.status === 'in_progress',
                        'bg-red-100 text-red-800 dark:bg-red-900':
                            row.status === 'pending',
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

<style lang="scss" scoped></style>
