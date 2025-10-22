<template>
    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <!-- <th class="px-5 py-3 text-left w-3/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                #ID
                            </p>
                        </th> -->
                        <th class="px-5 py-3 text-left w-3/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Project Name
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Created By
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Status
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Start Date
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Due Date
                            </p>
                        </th>
                        <!-- <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Actions
                            </p>
                        </th> -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr
                        v-for="(user, index) in users"
                        :key="index"
                        class="border-t border-gray-100 dark:border-gray-800"
                    >
                        <!-- <td class="px-5 py-4 sm:px-6">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                {{ index + 1 }}
                            </p>
                        </td> -->
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                {{ user.project }}
                            </p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 overflow-hidden rounded-full"
                                >
                                    <img :src="user.avatar" :alt="user.name" />
                                </div>
                                <div>
                                    <span
                                        class="block font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                    >
                                        {{ user.name }}
                                    </span>
                                    <span
                                        class="block text-gray-500 text-theme-xs dark:text-gray-400"
                                    >
                                        {{ user.role }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <span
                                :class="[
                                    'rounded-full px-2 py-0.5 text-theme-xs font-medium',
                                    {
                                        'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500':
                                            user.status === 'Active',
                                        'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-400':
                                            user.status === 'Pending',
                                        'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500':
                                            user.status === 'Cancel',
                                    },
                                ]"
                            >
                                {{ user.status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                12-10-2025
                            </p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                            >
                                12-11-2025
                            </p>
                        </td>

                        <!-- <td
                            class="px-5 py-4 sm:px-6"
                            :id="'table-dropdown-' + index"
                        >
                            <Button
                                size="sm"
                                variant="outline"
                                :endIcon="TrashIcon"
                                @click="isProjectModalOpen = true"
                            >
                            </Button>
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import DropdownMenu from "@/components/common/DropdownMenu.vue";
import Button from "@/components/ui/Button.vue";
import TrashIcon from "@/icons/TrashIcon.vue";

const menuItems = [
    { label: "View More", onClick: () => console.log("View More clicked") },
    { label: "Delete", onClick: () => console.log("Delete clicked") },
];

const users = ref([
    {
        name: "Lindsey Curtis",
        role: "Web Designer",
        avatar: "/images/user/user-17.jpg",
        project: "Agency Website",
        team: [
            "/images/user/user-22.jpg",
            "/images/user/user-23.jpg",
            "/images/user/user-24.jpg",
        ],
        status: "Active",
        budget: "3.9K",
    },
    {
        name: "Kaiya George",
        role: "Project Manager",
        avatar: "/images/user/user-18.jpg",
        project: "Technology",
        team: ["/images/user/user-25.jpg", "/images/user/user-26.jpg"],
        status: "Pending",
        budget: "24.9K",
    },
    {
        name: "Zain Geidt",
        role: "Content Writer",
        avatar: "/images/user/user-19.jpg",
        project: "Blog Writing",
        team: ["/images/user/user-27.jpg"],
        status: "Active",
        budget: "12.7K",
    },
    {
        name: "Abram Schleifer",
        role: "Digital Marketer",
        avatar: "/images/user/user-20.jpg",
        project: "Social Media",
        team: [
            "/images/user/user-28.jpg",
            "/images/user/user-29.jpg",
            "/images/user/user-30.jpg",
        ],
        status: "Cancel",
        budget: "2.8K",
    },
    {
        name: "Carla George",
        role: "Front-end Developer",
        avatar: "/images/user/user-21.jpg",
        project: "Website",
        team: [
            "/images/user/user-31.jpg",
            "/images/user/user-32.jpg",
            "/images/user/user-33.jpg",
        ],
        status: "Active",
        budget: "4.5K",
    },
]);
</script>
