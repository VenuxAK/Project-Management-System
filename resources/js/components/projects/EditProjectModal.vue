<script setup>
import { useForm } from "@inertiajs/vue3";
import DatePicker from "@/components/FormElements/DatePicker.vue";
import InputText from "@/components/FormElements/InputText.vue";
import InputLabel from "@/components/FormElements/InputLabel.vue";
import InputError from "@/components/FormElements/InputError.vue";
import SelectBox from "@/components/FormElements/SelectBox.vue";
import Modal from "@/components/ui/Modal.vue";
import Button from "@/components/ui/Button.vue";
import CrossIcon from "@/icons/CrossIcon.vue";
import { useProjectManager } from "@/composables/useProjectManager";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    isEditProjectModalOpen: {
        type: Boolean,
        required: true,
    },
});
const emit = defineEmits(["update:isEditProjectModalOpen"]);
const { projectStatuses, updateProject } = useProjectManager();

const formData = useForm({
    name: props.project.name,
    status: props.project.status,
    start_date: props.project.start_date,
    deadline: props.project.deadline,
});

const closeEditProjectModal = () => {
    emit("update:isEditProjectModalOpen", false);
    formData.reset();
};

const onUpdateProject = () => {
    updateProject(props.project.id, formData, {
        onSuccess: () => {
            closeEditProjectModal();
        },
    });
};
// onMounted(() => {
// console.log(`Project ${props.project.id} found.`);
// });
</script>

<template>
    <Modal v-if="isEditProjectModalOpen" @close="closeEditProjectModal()">
        <template #body>
            <Button
                type="button"
                variant="outline"
                size="sm"
                class="absolute right-5 top-5 z-999 rounded-full p-0.5 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300"
                @click="closeEditProjectModal()"
            >
                <CrossIcon />
            </Button>
            <div class="px-2 pr-14">
                <h4
                    class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90"
                >
                    Edit project
                </h4>
            </div>
            <form @submit.prevent="onUpdateProject" class="flex flex-col">
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
                            <InputError :message="formData.errors.start_date" />
                        </div>
                        <div>
                            <DatePicker
                                v-model="formData.deadline"
                                label="Deadline"
                            />
                            <InputError :message="formData.errors.deadline" />
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                    <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        @click="closeEditProjectModal()"
                    >
                        Close
                    </Button>
                    <Button type="submit" variant="primary" size="sm">
                        Save Changes
                    </Button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<style lang="scss" scoped></style>
