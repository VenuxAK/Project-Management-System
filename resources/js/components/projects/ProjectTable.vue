<script setup>
import Button from "@/components/ui/Button.vue";
import TrashIcon from "@/icons/TrashIcon.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm();
const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
});

const deleteProject = (projectId) => {
    if (confirm("Are you sure you want to delete this project?")) {
        form.delete(`/projects/${projectId}`, {
            onSuccess: () => {
                console.log(
                    `Project with ID ${projectId} deleted successfully.`
                );
            },
        });
    } else {
        console.log("Project deletion cancelled.");
    }
};
</script>

<template>
    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-5 py-3 text-left w-1/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                #ID
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-3/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Project Name
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Created By
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Status
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Start Date
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Deadline
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/12 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Actions
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr
                        v-if="projects.length > 0"
                        v-for="project in projects"
                        :key="project.id"
                        class="border-t border-gray-100 dark:border-gray-800"
                    >
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                {{ project.id }}
                            </p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                {{ project.name }}
                            </p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 overflow-hidden rounded-full"
                                >
                                    <img
                                        v-if="project.creator.profile_picture"
                                        :src="project.creator.profile_picture"
                                        :alt="project.creator.name"
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
                                        {{ project.creator.name }}
                                    </span>
                                    <span
                                        class="block text-gray-500 text-theme-xs dark:text-gray-400"
                                    >
                                        {{ project.creator.role.name }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <span
                                :class="[
                                    'rounded-full px-2 py-1 text-theme-xs font-medium',
                                    {
                                        'bg-success-100 text-success-700 dark:bg-success-500/15 dark:text-success-500':
                                            project.status === 'active',
                                        'bg-warning-100 text-warning-700 dark:bg-warning-500/15 dark:text-warning-400':
                                            project.status === 'planning',
                                        'bg-brand-100 text-brand-700 dark:bg-brand-500/15 dark:text-brand-500':
                                            project.status === 'completed',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-700/15 dark:text-gray-400':
                                            project.status === 'on-hold',
                                    },
                                ]"
                            >
                                {{
                                    project.status.charAt(0).toUpperCase() +
                                    project.status.slice(1)
                                }}
                            </span>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                {{ project.start_date }}
                            </p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                {{ project.deadline }}
                            </p>
                        </td>

                        <td
                            class="px-5 py-4 sm:px-6"
                            :id="'table-dropdown-' + project.id"
                        >
                            <Button
                                size="sm"
                                variant="outline"
                                :endIcon="TrashIcon"
                                @click="deleteProject(project.id)"
                            />
                        </td>
                    </tr>
                    <tr v-else>
                        <td
                            colspan="7"
                            class="px-5 py-4 text-center text-gray-500 text-theme-sm dark:text-gray-400"
                        >
                            No projects found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
