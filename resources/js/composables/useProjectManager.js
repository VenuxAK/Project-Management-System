import { useResourceManager } from "@/composables/useResourceManager";
const defaultProjectFields = {
    name: "",
    status: "",
    start_date: null,
    deadline: null,
};

export const useProjectManager = () => {
    const { create, update, remove, makeForm } = useResourceManager(
        "/projects",
        defaultProjectFields
    );

    const projectStatuses = [
        { value: "planning", label: "Planning" },
        { value: "active", label: "Active" },
        { value: "completed", label: "Completed" },
        { value: "on-hold", label: "On Hold" },
    ];

    return {
        // functions kept simple names compatible with existing code
        createProject: create,
        updateProject: update,
        deleteProject: remove,
        makeProjectForm: makeForm,
        projectStatuses,
    };
};
