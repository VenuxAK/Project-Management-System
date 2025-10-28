import { useResourceManager } from "./useResourceManager";

const defaultTaskFields = {
    name: "",
    priority: "",
    status: "",
    start_date: "",
    due_date: "",
    assigned_to: "",
    project_id: "",
};

export const useTaskManager = () => {
    const { create, update, remove, makeForm } = useResourceManager(
        "/tasks",
        defaultTaskFields
    );
    const taskStatuses = [
        { value: "pending", label: "Pending" },
        { value: "in_progress", label: "In Progress" },
        { value: "completed", label: "Completed" },
    ];
    const taskPriorities = [
        { value: "low", label: "Low" },
        { value: "medium", label: "Medium" },
        { value: "high", label: "High" },
    ];

    const updateStatus = (taskID) => {
        makeForm().patch(`/tasks/${taskID}/update-status`, {
            onSuccess: () => {},
            onError: (errors) => {
                console.log(errors);
            },
        });
    };

    return {
        createTask: create,
        updateTask: update,
        deleteTask: remove,
        makeTaskForm: makeForm,
        updateStatus,
        taskPriorities,
        taskStatuses,
    };
};
