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
import { onMounted } from "vue";

const { createTask, taskPriorities, taskStatuses } = useTaskManager();

const props = defineProps({
    isTaskModalOpen: {
        type: Boolean,
        required: true,
    },
    projects: {
        type: Array,
        required: false,
        default: [],
    },
    users: {
        type: Array,
        required: false,
        default: [],
    },
});
const emit = defineEmits(["update:isTaskModalOpen"]);

const formData = useForm({
    name: "",
    status: "",
    priority: "",
    project_id: "",
    assigned_to: "",
    start_date: "",
    due_date: "",
});

const saveProject = () => {
    createTask(formData, {
        onSuccess: () => {
            // console.log("Task Created Successful");
            closeTaskModal();
        },
        // onError: (errors) => {
        //     console.log(errors);
        // },
    });
};

const closeTaskModal = () => {
    formData.reset();
    emit("update:isTaskModalOpen", false);
};

onMounted(() => {
    console.log("Create Task Modal Mounted!");
});
</script>

<template>
    <Modal v-if="isTaskModalOpen" @close="closeTaskModal()">
        <template #body>
            <Button
                type="button"
                variant="outline"
                size="sm"
                class="absolute right-5 top-5 z-999 rounded-full p-0.5 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300"
                @click="closeTaskModal()"
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
            <form @submit.prevent="saveProject" class="flex flex-col">
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
                                label="Users"
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
                        @click="closeTaskModal()"
                    >
                        Close
                    </Button>
                    <Button type="submit" variant="primary" size="sm">
                        Assign
                    </Button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<style lang="scss" scoped></style>
