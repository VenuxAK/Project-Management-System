import { useForm } from "@inertiajs/vue3";

const form = useForm();

export const useProjectManager = () => {
    const createProject = () => {};

    const updateProject = (id) => {
        form.put(`/projects/${id}`, {
            onSuccess: () => {
                // console.log("UPDATED SUCCESSFUL!");
                return true;
            },
            onError: () => {
                return ;
            },
        });
    };

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

    const projectStatuses = [
        { value: "planning", label: "Planning" },
        { value: "active", label: "Active" },
        { value: "completed", label: "Completed" },
        { value: "on-hold", label: "On Hold" },
    ];

    return { updateProject, deleteProject, projectStatuses };
};
