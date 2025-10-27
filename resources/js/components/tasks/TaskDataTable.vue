<script setup>
import { computed } from "vue";
import DataTable from "@/components/ui/DataTable.vue";
import { useTaskManager } from "@/composables/useTaskManager.js";

const props = defineProps({
    tasks: {
        type: Array,
        required: true,
    },
});
const { deleteTask } = useTaskManager();

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

const onDeleteTask = (id) => {
    deleteTask(id);
};
</script>
<template>
    <DataTable
        :columns="cols"
        :rows="rows"
        :initialPageSize="5"
        :useActions="true"
        @delete="onDeleteTask($event)"
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
