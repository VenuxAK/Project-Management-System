import { useForm } from "@inertiajs/vue3";

const form = useForm();

export const useTaskManager = () => {
    const deleteTask = (id) => {
        if (confirm("Are you sure you want to delete this task?")) {
            form.delete(`/tasks/${id}`, {
                onSuccess: () => {
                    console.log(`Task with ID ${id} deleted successfully.`);
                },
            });
        } else {
            console.log("Task deletion cancelled.");
        }
    };

    return { deleteTask };
};
