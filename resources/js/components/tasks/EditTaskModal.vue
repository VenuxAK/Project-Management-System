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
import { useTaskManager } from "@/composables/useTaskManager";

const { updateTask, taskPriorities, taskStatuses } = useTaskManager();

const props = defineProps({
    isEditTaskModalOpen: {
        type: Boolean,
        required: true,
    },
    task: {
        type: Object,
        required: true,
    },
    projects: {
        type: Array,
        required: true,
    },
    users: {
        type: Array,
        required: true,
    },
});
const emit = defineEmits(["update:isEditTaskModalOpen"]);

const formData = useForm({
    name: props.task.name,
    status: props.task.status,
    priority: props.task.priority,
    assigned_to: props.task.assigned_to,
    project_id: props.task.project_id,
    start_date: props.task.start_date,
    due_date: props.task.due_date,
});

const onUpdateTask = () => {
    updateTask(props.task.id, formData, {
        onSuccess: () => {
            closeEditTaskModal();
        },
    });
};

const closeEditTaskModal = () => {
    formData.reset();
    emit("update:isEditTaskModalOpen", false);
};
</script>

<template>
    <Modal v-if="isEditTaskModalOpen" @close="closeEditTaskModal()">
        <template #body>
            <Button
                type="button"
                variant="outline"
                size="sm"
                class="absolute right-5 top-5 z-999 rounded-full p-0.5 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300"
                @click="closeEditTaskModal()"
            >
                <CrossIcon />
            </Button>
            <div class="px-2 pr-14">
                <h4
                    class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90"
                >
                    Assign Task
                </h4>
            </div>
            <form @submit.prevent="onUpdateTask" class="flex flex-col">
                <div class="custom-scrollbar h-[458px] overflow-y-auto p-2">
                    <div class="space-y-6">
                        <div>
                            <InputLabel label="Task Title" for="task-title" />
                            <InputText
                                v-model="formData.name"
                                type="text"
                                id="task-title"
                                placeholder="Design Landing Page"
                            />
                            <InputError :message="formData.errors.name" />
                        </div>

                        <div>
                            <SelectBox
                                v-model="formData.priority"
                                :items="taskPriorities"
                                label="Priority"
                            />
                            <InputError :message="formData.errors.priority" />
                        </div>

                        <div>
                            <SelectBox
                                v-model="formData.status"
                                :items="taskStatuses"
                                label="Status"
                            />
                            <InputError :message="formData.errors.status" />
                        </div>

                        <div>
                            <SelectBox
                                v-model="formData.project_id"
                                :items="projects"
                                type="number"
                                label="Project"
                            />
                            <InputError :message="formData.errors.project_id" />
                        </div>

                        <div>
                            <SelectBox
                                v-model="formData.assigned_to"
                                :items="users"
                                label="Assign To"
                            />
                            <InputError
                                :message="formData.errors.assigned_to"
                            />
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
                                v-model="formData.due_date"
                                label="Due Date"
                            />
                            <InputError :message="formData.errors.due_date" />
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                    <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        @click="closeEditTaskModal()"
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
