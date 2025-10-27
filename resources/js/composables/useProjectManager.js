import { useForm } from "@inertiajs/vue3";

const form = useForm();

export const useProjectManager = () => {
    const deleteProject = (id) => {
        if (confirm("Are you sure you want to delete this project?")) {
            form.delete(`/projects/${id}`, {
                onSuccess: () => {
                    console.log(`Project with ID ${id} deleted successfully.`);
                },
            });
        } else {
            console.log("Project deletion cancelled.");
        }
    };

    return { deleteProject };
};
