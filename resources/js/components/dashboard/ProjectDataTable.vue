<script setup>
import DataTable from "@/components/ui/DataTable.vue";
import { computed } from "vue";

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
});

const cols = [
    { label: "Name", key: "name", sortable: false },
    { label: "Status", key: "status", sortable: true },
    { label: "Start Date", key: "start_date", sortable: true },
    { label: "Deadline", key: "deadline", sortable: true },
];
const rows = computed(() => {
    return props.projects.map((project) => ({
        name: project.name,
        status: project.status,
        start_date: project.start_date,
        deadline: project.deadline,
    }));
});
</script>

<template>
    <DataTable :columns="cols" :rows="rows" :initialPageSize="5">
        <template #title>Projects</template>

        <!-- custom cell for name -->
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

<style lang="scss" scoped></style>
