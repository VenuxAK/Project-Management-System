<script setup>
import Modal from "@/components/ui/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import CrossIcon from "@/icons/CrossIcon.vue";
import InputText from "@/components/FormElements/InputText.vue";
import InputLabel from "@/components/FormElements/InputLabel.vue";
import InputError from "@/components/FormElements/InputError.vue";
import SelectBox from "@/components/FormElements/SelectBox.vue";
import Button from "@/components/ui/Button.vue";
import { useMemberManager } from "@/composables/useMemberManager";
import { onMounted } from "vue";

const props = defineProps({
    isCreateMemberModalOpen: {
        type: Boolean,
        required: true,
    },
});
const emit = defineEmits(["update:isCreateMemberModalOpen"]);
const { createMember, roles } = useMemberManager();

const formData = useForm({
    name: "",
    email: "",
    role_id: "",
});

const saveProject = () => {
    createMember(formData, {
        onSuccess: () => {
            closeCreateMemberModal();
        },
    });
};

const closeCreateMemberModal = () => {
    formData.reset();
    formData.clearErrors();
    emit("update:isCreateMemberModalOpen", false);
};
// onMounted(() => {
//     console.log("Create Project Mounted");
// });
</script>

<template>
    <Modal v-if="isCreateMemberModalOpen" @close="closeCreateMemberModal()">
        <template #body>
            <Button
                type="button"
                variant="outline"
                size="sm"
                class="absolute right-5 top-5 z-999 rounded-full p-0.5 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300"
                @click="closeCreateMemberModal()"
            >
                <CrossIcon />
            </Button>
            <div class="px-2 pr-14">
                <h4
                    class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90"
                >
                    Create a new member
                </h4>
            </div>
            <form @submit.prevent="saveProject" class="flex flex-col">
                <div class="custom-scrollbar h-[458px] overflow-y-auto p-2">
                    <div class="space-y-6">
                        <div>
                            <InputLabel label="Name" for="member-name" />
                            <InputText
                                v-model="formData.name"
                                type="text"
                                id="member-name"
                                placeholder="Jon Doe"
                            />
                            <InputError :message="formData.errors.name" />
                        </div>

                        <div>
                            <InputLabel label="Email" for="member-email" />
                            <InputText
                                v-model="formData.email"
                                type="text"
                                id="member-email"
                                placeholder="jondoe@example.com"
                            />
                            <InputError :message="formData.errors.email" />
                        </div>

                        <div>
                            <SelectBox
                                v-model="formData.role_id"
                                :items="roles"
                                label="Assign Role"
                            />
                            <InputError :message="formData.errors.role_id" />
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                    <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        @click="closeCreateMemberModal()"
                    >
                        Close
                    </Button>
                    <Button type="submit" variant="primary" size="sm">
                        Save User
                    </Button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<style lang="scss" scoped></style>
