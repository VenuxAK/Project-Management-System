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
                        <th class="py-3 text-left">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Name
                            </p>
                        </th>
                        <th class="py-3 text-left">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Assignee
                            </p>
                        </th>
                        <th class="py-3 text-left">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Priority
                            </p>
                        </th>
                        <th class="py-3 text-left">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Status
                            </p>
                        </th>
                        <th class="py-3 text-left">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Start Date
                            </p>
                        </th>
                        <th class="py-3 text-left">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Due Date
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
                                        :src="task.user.avatar"
                                        :alt="task.user.name"
                                    />
                                </div>
                                <div>
                                    <span
                                        class="block font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                    >
                                        {{ task.user.name }}
                                    </span>
                                    <span
                                        class="block text-gray-500 text-theme-xs dark:text-gray-400"
                                    >
                                        {{ task.user.role }}
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
                                            task.priority === 'Low',
                                        'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400':
                                            task.priority === 'Medium',
                                        'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500':
                                            task.priority === 'High',
                                    }"
                                >
                                    {{ task.priority }}
                                </span>
                            </p>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <span
                                :class="{
                                    'rounded-full px-2 py-0.5 text-theme-xs font-medium': true,
                                    'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500':
                                        task.status === 'Delivered',
                                    'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400':
                                        task.status === 'Pending',
                                    'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500':
                                        task.status === 'Canceled',
                                }"
                            >
                                {{ task.status }}
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
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const tasks = ref([
    {
        name: "Design Home Page",
        image: "/public/images/product/product-01.jpg",
        assignee: "Jon Doe",
        start_date: "10-10-2025",
        due_date: "12-10-2025",
        priority: "High",
        status: "Delivered",
        user: {
            name: "Jon Doe",
            role: "UI Designer",
            avatar: "/images/user/user-01.jpg",
        },
    },
    {
        name: "Design About Us Page",
        image: "/public/images/product/product-02.jpg",
        assignee: "Smith",
        start_date: "11-10-2025",
        due_date: "13-10-2025",
        priority: "Low",
        status: "Pending",
        user: {
            name: "Smith",
            role: "UX Designer",
            avatar: "/images/user/user-02.jpg",
        },
    },
    {
        name: "Draw Class Diagram",
        image: "/public/images/product/product-03.jpg",
        assignee: "Alice",
        start_date: "4-10-2025",
        due_date: "5-10-2025",
        priority: "Medium",
        status: "Delivered",
        user: {
            name: "Alice",
            role: "System Analyst",
            avatar: "/images/user/user-03.jpg",
        },
    },
    {
        name: "Draw Use Case Diagram",
        image: "/public/images/product/product-04.jpg",
        assignee: "Jonny",
        start_date: "6-10-2025",
        due_date: "7-10-2025",
        priority: "High",
        status: "Canceled",
        user: {
            name: "Jonny",
            role: "Business Analyst",
            avatar: "/images/user/user-04.jpg",
        },
    },
    {
        name: "Draw Sequence Diagram",
        image: "/public/images/product/product-05.jpg",
        assignee: "Scarlet",
        start_date: "8-10-2025",
        due_date: "9-10-2025",
        priority: "Low",
        status: "Delivered",
        user: {
            name: "Scarlet",
            role: "Developer",
            avatar: "/images/user/user-05.jpg",
        },
    },
]);
</script>
