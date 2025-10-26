<script setup>
import Modal from "@/components/ui/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import CrossIcon from "@/icons/CrossIcon.vue";
import DatePicker from "@/components/FormElements/DatePicker.vue";
import InputText from "@/components/FormElements/InputText.vue";
import InputLabel from "@/components/FormElements/InputLabel.vue";
import InputError from "@/components/FormElements/InputError.vue";
import SelectBox from "@/components/FormElements/SelectBox.vue";
import Button from "@/components/ui/Button.vue";

const props = defineProps({
    isProjectModalOpen: {
        type: Boolean,
        required: true,
    },
});
const emit = defineEmits(["update:isProjectModalOpen"]);

const formData = useForm({
    name: "",
    status: "",
    start_date: null,
    deadline: null,
});
const projectStatuses = [
    { value: "planning", label: "Planning" },
    { value: "active", label: "Active" },
    { value: "completed", label: "Completed" },
    { value: "on-hold", label: "On Hold" },
];

const saveProject = () => {
    formData.post("/projects", {
        onSuccess: () => {
            closeProjectModal();
        },
    });
};

const closeProjectModal = () => {
    formData.reset();
    emit("update:isProjectModalOpen", false);
};
</script>

<template>
    <Modal v-if="isProjectModalOpen" @close="closeProjectModal()">
        <template #body>
            <div
                class="no-scrollbar relative w-full max-w-[680px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
            >
                <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    class="absolute right-5 top-5 z-999 rounded-full p-0.5 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300"
                    @click="closeProjectModal()"
                >
                    <CrossIcon />
                </Button>
                <div class="px-2 pr-14">
                    <h4
                        class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90"
                    >
                        Create a new project
                    </h4>
                </div>
                <form @submit.prevent="saveProject" class="flex flex-col">
                    <div class="custom-scrollbar h-[458px] overflow-y-auto p-2">
                        <div class="space-y-6">
                            <div>
                                <InputLabel
                                    label="Project Name"
                                    for="project-name"
                                />
                                <InputText
                                    v-model="formData.name"
                                    type="text"
                                    id="project-name"
                                    placeholder="Design Landing Page"
                                />
                                <InputError :message="formData.errors.name" />
                            </div>

                            <div>
                                <SelectBox
                                    v-model="formData.status"
                                    :items="projectStatuses"
                                    label="Project Status"
                                />
                                <InputError :message="formData.errors.status" />
                            </div>

                            <div>
                                <DatePicker
                                    v-model="formData.start_date"
                                    label="Start Date"
                                />
                                <InputError
                                    :message="formData.errors.start_date"
                                />
                            </div>
                            <div>
                                <DatePicker
                                    v-model="formData.deadline"
                                    label="Deadline"
                                />
                                <InputError
                                    :message="formData.errors.deadline"
                                />
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex items-center gap-3 px-2 mt-6 lg:justify-end"
                    >
                        <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            @click="closeProjectModal()"
                        >
                            Close
                        </Button>
                        <Button type="submit" variant="primary" size="sm">
                            Save Changes
                        </Button>
                    </div>
                </form>
            </div>
        </template>
    </Modal>
</template>

<style lang="scss" scoped></style>
