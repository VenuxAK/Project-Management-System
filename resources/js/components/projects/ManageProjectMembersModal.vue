<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/components/ui/Modal.vue";
import Button from "@/components/ui/Button.vue";
import InputLabel from "@/components/FormElements/InputLabel.vue";
import InputError from "@/components/FormElements/InputError.vue";
import CrossIcon from "@/icons/CrossIcon.vue";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    users: {
        type: Array,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    },
    isModalOpen: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["update:isModalOpen"]);

const closeModal = () => emit("update:isModalOpen", false);

const existingMemberIds = (exceptIndex = -1) =>
    (props.project.members ?? []).map((m) => m.id);

const availableUsers = (index) => {
    const taken = [
        ...existingMemberIds(),
        ...attachForm.members
            .filter((_, i) => i !== index)
            .map((m) => Number(m.user_id))
            .filter(Boolean),
    ];

    return props.users.filter((user) => !taken.includes(user.id));
};

const ownerRoleId = () =>
    props.roles.find((role) => role.name === "owner")?.id ?? null;

const roleCanBeRemoved = (member) => {
    if (member.id === props.project.created_by) return false;
    return member.pivot?.role_id !== ownerRoleId();
};

const attachForm = useForm({
    members: [{ user_id: "", role_id: "" }],
});

const addMemberRow = () => {
    attachForm.members.push({ user_id: "", role_id: "" });
};

const removeMemberRow = (index) => {
    if (attachForm.members.length === 1) return;
    attachForm.members.splice(index, 1);
};

const submitNewMembers = () => {
    const payload = attachForm.members.filter(
        (m) => m.user_id && m.role_id
    );
    if (payload.length === 0) return;

    attachForm.members = payload;

    attachForm.post(`/projects/${props.project.id}/members`, {
        preserveScroll: true,
        onSuccess: () => {
            attachForm.reset();
            attachForm.members = [{ user_id: "", role_id: "" }];
        },
    });
};

const roleBeingUpdated = ref(null);

const changeRole = (member, event) => {
    roleBeingUpdated.value = member.id;

    useForm({ role_id: event.target.value }).patch(
        `/projects/${props.project.id}/members/${member.id}`,
        {
            preserveScroll: true,
            onFinish: () => (roleBeingUpdated.value = null),
        }
    );
};

const removeExistingMember = (member) => {
    if (
        !confirm(
            `Remove ${member.name} from "${props.project.name}"?`
        )
    ) {
        return;
    }

    useForm().delete(
        `/projects/${props.project.id}/members/${member.id}`,
        { preserveScroll: true }
    );
};
</script>

<template>
    <Modal v-if="isModalOpen" @close="closeModal()">
        <template #body>
            <Button
                type="button"
                variant="outline"
                size="sm"
                class="absolute right-5 top-5 z-999 rounded-full p-0.5 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300"
                @click="closeModal()"
            >
                <CrossIcon />
            </Button>
            <div class="px-2 pr-14">
                <h4
                    class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90"
                >
                    Manage members — {{ project.name }}
                </h4>
            </div>

            <form @submit.prevent="submitNewMembers" class="flex flex-col">
                <div class="custom-scrollbar max-h-[458px] overflow-y-auto p-2">
                    <InputLabel label="Current members" />
                    <p
                        v-if="!project.members?.length"
                        class="mb-4 text-sm text-gray-500 dark:text-gray-400"
                    >
                        No members yet.
                    </p>
                    <ul v-else class="space-y-3 mb-6 mt-2">
                        <li
                            v-for="member in project.members"
                            :key="member.id"
                            class="flex items-center gap-3"
                        >
                            <span
                                class="flex-1 text-sm font-medium text-gray-800 dark:text-white/90"
                            >
                                {{ member.name }}
                            </span>
                            <select
                                :value="member.pivot?.role_id ?? ''"
                                :disabled="
                                    roleBeingUpdated === member.id
                                "
                                @change="changeRole(member, $event)"
                                class="dark:bg-dark-900 h-9 w-44 rounded-lg border border-gray-300 bg-transparent px-3 py-1.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800"
                            >
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.id"
                                >
                                    {{ role.name }}
                                </option>
                            </select>
                            <button
                                v-if="roleCanBeRemoved(member)"
                                type="button"
                                class="text-red-600 hover:text-red-500"
                                @click="removeExistingMember(member)"
                            >
                                ✕
                            </button>
                        </li>
                    </ul>

                    <InputLabel label="Add members" />
                    <div
                        class="border border-gray-300 dark:border-gray-700 rounded p-4 mt-2"
                    >
                        <div
                            v-for="(member, index) in attachForm
                                .members"
                            :key="index"
                            class="flex gap-3 items-start mb-4"
                        >
                            <select
                                v-model="member.user_id"
                                class="dark:bg-dark-900 h-11 w-full flex-1 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                            >
                                <option value="" disabled>
                                    Select user
                                </option>
                                <option
                                    v-for="user in availableUsers(index)"
                                    :key="user.id"
                                    :value="user.id"
                                >
                                    {{ user.name }}
                                </option>
                            </select>
                            <select
                                v-model="member.role_id"
                                class="dark:bg-dark-900 h-11 w-full flex-1 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                            >
                                <option value="" disabled>Select role</option>
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.id"
                                >
                                    {{ role.name }}
                                </option>
                            </select>
                            <button
                                type="button"
                                class="text-red-600 mt-2"
                                @click="removeMemberRow(index)"
                            >
                                ✕
                            </button>
                        </div>
                        <InputError
                            :message="
                                attachForm.errors.members ||
                                attachForm.errors['members.0.user_id'] ||
                                attachForm.errors['members.0.role_id']
                            "
                        />
                        <button
                            type="button"
                            @click="addMemberRow"
                            class="text-sm text-blue-600"
                        >
                            + Add member
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                    <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        @click="closeModal()"
                    >
                        Close
                    </Button>
                    <Button
                        type="submit"
                        variant="primary"
                        size="sm"
                        :disabled="attachForm.processing"
                    >
                        {{
                            attachForm.processing ? "Adding..." : "Add members"
                        }}
                    </Button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<style lang="scss" scoped></style>
