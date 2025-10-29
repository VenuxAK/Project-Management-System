import { useResourceManager } from "@/composables/useResourceManager";
const defaultMemberFields = {
    name: "",
    email: "",
    role_id: null,
    profile_picture: null,
};

export const useMemberManager = () => {
    const { create, update, remove, makeForm } = useResourceManager(
        "/members",
        defaultMemberFields
    );

    const roles = [
        // { value: 1, label: "Admin" },
        { value: 2, label: "Project Manager" },
        { value: 3, label: "Developer" },
    ];

    return {
        // functions kept simple names compatible with existing code
        createMember: create,
        updateMember: update,
        deleteMember: remove,
        makeMemberForm: makeForm,
        roles,
    };
};
