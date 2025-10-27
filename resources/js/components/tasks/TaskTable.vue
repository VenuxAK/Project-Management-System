<script setup>
const props = defineProps({
    tasks: {
        type: Array,
        required: true,
    },
});
import Button from "@/components/ui/Button.vue";
import TrashIcon from "@/icons/TrashIcon.vue";
import { useForm } from "@inertiajs/vue3";
const form = useForm();
const deleteTask = (taskId) => {
    if (confirm("Are you sure you want to delete this task?")) {
        form.delete(`/tasks/${taskId}`, {
            onSuccess: () => {
                console.log(`Task with ID ${taskId} deleted successfully.`);
            },
        });
    } else {
        console.log("Task deletion cancelled.");
    }
};
</script>

<template>
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6"
    >
        <div
            class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-white/90"
                >
                    Tasks
                </h3>
            </div>

            <div class="flex items-center gap-3">
                <button
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
                >
                    <svg
                        class="stroke-current fill-white dark:fill-gray-800"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M2.29004 5.90393H17.7067"
                            stroke=""
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M17.7075 14.0961H2.29085"
                            stroke=""
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
                            fill=""
                            stroke=""
                            stroke-width="1.5"
                        />
                        <path
                            d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
                            fill=""
                            stroke=""
                            stroke-width="1.5"
                        />
                    </svg>

                    Filter
                </button>

                <button
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
                >
                    See all
                </button>
            </div>
        </div>

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-t border-gray-100 dark:border-gray-800">
                        <th class="py-3 text-left w-1/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                #ID
                            </p>
                        </th>
                        <th class="py-3 text-left w-3/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Name
                            </p>
                        </th>
                        <th class="py-3 text-left w-3/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Assignee
                            </p>
                        </th>
                        <th class="py-3 text-left w-1/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Priority
                            </p>
                        </th>
                        <th class="py-3 text-left w-1/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Status
                            </p>
                        </th>
                        <th class="py-3 text-left w-2/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Start Date
                            </p>
                        </th>
                        <th class="py-3 text-left w-2/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Due Date
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-1/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Actions
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(task, index) in tasks"
                        :key="index"
                        class="border-t border-gray-100 dark:border-gray-800"
                    >
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div>
                                    <p
                                        class="font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                    >
                                        {{ task.id }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div>
                                    <p
                                        class="font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                    >
                                        {{ task.name }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 overflow-hidden rounded-full"
                                >
                                    <img
                                        v-if="task.assignee.profile_picture"
                                        :src="task.assignee.profile_picture"
                                        :alt="task.assignee.name"
                                    />
                                    <img
                                        v-else
                                        :src="'/images/user/profile.jpg'"
                                        alt="Profile"
                                    />
                                </div>
                                <div>
                                    <span
                                        class="block font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                    >
                                        {{ task.assignee.name }}
                                    </span>
                                    <span
                                        class="block text-gray-500 text-theme-xs dark:text-gray-400"
                                    >
                                        {{ task.assignee.role.name }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                <span
                                    :class="{
                                        'rounded-full px-2 py-0.5 text-theme-xs font-medium': true,
                                        'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500':
                                            task.priority === 'low',
                                        'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400':
                                            task.priority === 'medium',
                                        'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500':
                                            task.priority === 'high',
                                    }"
                                >
                                    {{
                                        String(task.priority)
                                            .charAt(0)
                                            .toUpperCase() +
                                        String(task.priority).slice(1)
                                    }}
                                </span>
                            </p>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <span
                                :class="{
                                    'rounded-full px-2 py-0.5 text-theme-xs font-medium': true,
                                    'bg-brand-100 text-brand-700 dark:bg-brand-500/15 dark:text-brand-500':
                                        task.status === 'completed',
                                    'bg-warning-100 text-warning-700 dark:bg-warning-500/15 dark:text-warning-400':
                                        task.status === 'pending',
                                    'bg-success-100 text-success-700 dark:bg-success-500/15 dark:text-success-500':
                                        task.status === 'in_progress',
                                }"
                            >
                                {{
                                    String(task.status)
                                        .charAt(0)
                                        .toUpperCase() +
                                    String(task.status).slice(1)
                                }}
                            </span>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                {{ task.start_date }}
                            </p>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                {{ task.due_date }}
                            </p>
                        </td>
                        <td
                            class="px-5 py-4 sm:px-6"
                            :id="'table-dropdown-' + task.id"
                        >
                            <Button
                                size="sm"
                                variant="outline"
                                :endIcon="TrashIcon"
                                @click="deleteTask(task.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
