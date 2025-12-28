<script setup>
import { computed } from "vue";
import InputLabel from "@/components/FormElements/InputLabel.vue";

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: Array,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["update:modelValue"]);

/**
 * Computed proxy for v-model
 */
const members = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

/**
 * Add an empty member row
 */
function addMember() {
    members.value = [...members.value, { user_id: null, role_id: null }];
}

/**
 * Remove a member row
 */
function removeMember(index) {
    members.value = members.value.filter((_, i) => i !== index);
}

/**
 * Prevent selecting the same user twice
 */
const availableUsers = (currentIndex) => {
    const selectedUserIds = members.value
        .filter((_, i) => i !== currentIndex)
        .map((m) => m.user_id);

    return props.users.filter((user) => !selectedUserIds.includes(user.id));
};

/**
 * Error
 */
const fieldError = (index, field) => {
    return props.errors[`members.${index}.${field}`];
};
</script>

<template>
    <!-- <h3 class="font-semibold">Add Team Members (Optional)</h3> -->
    <InputLabel for="members" label="Add Team Members" />
    <div class="border border-gray-300 dark:border-gray-700 rounded p-4">
        <div
            v-for="(member, index) in members"
            :key="index"
            class="flex gap-3 items-start mb-4"
        >
            <!-- User Column -->
            <div class="flex-1">
                <select
                    v-model="member.user_id"
                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                >
                    <!-- :class="{
                        'border-red-600 dark:border-red-600': fieldError(
                            index,
                            'user_id'
                        ),
                    }" -->
                    <option value="" disabled>Select user</option>
                    <option
                        v-for="user in availableUsers(index)"
                        :key="user.id"
                        :value="user.id"
                    >
                        {{ user.name }}
                    </option>
                </select>

                <p
                    v-if="fieldError(index, 'user_id')"
                    class="text-sm text-red-600 mt-1"
                >
                    {{ fieldError(index, "user_id") }}
                </p>
            </div>

            <!-- Role Column -->
            <div class="flex-1">
                <select
                    v-model="member.role_id"
                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                >
                    <!-- :class="{
                        'border-red-600 dark:border-red-600': fieldError(
                            index,
                            'role_id'
                        ),
                    }" -->
                    <option value="" disabled selected hidden>
                        Select role
                    </option>
                    <option
                        v-for="role in roles"
                        :key="role.id"
                        :value="role.id"
                    >
                        {{ role.name }}
                    </option>
                </select>

                <p
                    v-if="fieldError(index, 'role_id')"
                    class="text-sm text-red-600 mt-1"
                >
                    {{ fieldError(index, "role_id") }}
                </p>
            </div>

            <!-- Remove -->
            <button
                type="button"
                @click="removeMember(index)"
                class="text-red-600 mt-2"
            >
                ✕
            </button>
        </div>

        <button type="button" @click="addMember" class="text-sm text-blue-600">
            + Add member
        </button>
    </div>
</template>
