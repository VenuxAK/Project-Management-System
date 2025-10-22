<template>
    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    >
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-5 py-3 text-left w-1/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                #ID
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-3/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                User
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Role
                            </p>
                        </th>
                        <th class="px-5 py-3 text-left w-2/11 sm:px-6">
                            <p
                                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                Email
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr
                        v-for="(user, index) in users"
                        :key="index"
                        class="border-t border-gray-100 dark:border-gray-800"
                    >
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="block text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                {{ user.id }}
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
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="block text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                {{ user.role }}
                            </p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p
                                class="block text-gray-500 text-theme-xs dark:text-gray-400"
                            >
                                {{ user.email }}
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

const users = ref([
    {
        id: 1,
        name: "Lindsey Curtis",
        role: "Web Designer",
        avatar: "/images/user/user-17.jpg",
        email: "lindsey@gmail.com",
    },
    {
        id: 2,
        name: "Kaiya George",
        role: "Project Manager",
        avatar: "/images/user/user-18.jpg",
        email: "kaiya@gmail.com",
    },
    {
        id: 3,
        name: "Zain Geidt",
        role: "Content Writer",
        avatar: "/images/user/user-19.jpg",
        email: "zain@gmail.com",
    },
    {
        id: 4,
        name: "Abram Schleifer",
        role: "Digital Marketer",
        avatar: "/images/user/user-20.jpg",
        email: "abram@gmail.com",
    },
    {
        id: 5,
        name: "Carla George",
        role: "Front-end Developer",
        avatar: "/images/user/user-21.jpg",
        email: "carla@gmail.com",
    },
]);
</script>
