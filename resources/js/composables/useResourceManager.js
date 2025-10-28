import { useForm } from "@inertiajs/vue3";

/**
 * Utility: detect Inertia form instance
 */
const isFormInstance = (f) =>
    f &&
    typeof f === "object" &&
    (typeof f.post === "function" ||
        typeof f.put === "function" ||
        typeof f.delete === "function");

/**
 * Factory to create resource managers (projects, tasks, members, ...)
 * baseUrl: '/projects' etc.
 * defaultFields: object used when creating a disposable form
 */
export const useResourceManager = (baseUrl, defaultFields = {}) => {
    const makeForm = (data = {}) => {
        return useForm(Object.assign({}, defaultFields, data));
    };

    const create = (payloadOrForm, options = {}) => {
        if (isFormInstance(payloadOrForm)) {
            return payloadOrForm.post(baseUrl, {
                onSuccess: options.onSuccess,
                onError: options.onError,
            });
        }

        const form = makeForm(payloadOrForm || {});
        return form.post(baseUrl, {
            onSuccess: () => {
                form.reset();
                if (options.onSuccess) options.onSuccess();
            },
            onError: (errors) => {
                if (options.onError) options.onError(errors);
            },
        });
    };

    const update = (id, payloadOrForm, options = {}) => {
        if (!id) throw new Error("update requires an id");

        if (isFormInstance(payloadOrForm)) {
            return payloadOrForm.put(`${baseUrl}/${id}`, {
                onSuccess: options.onSuccess,
                onError: options.onError,
            });
        }

        const form = makeForm(payloadOrForm || {});
        return form.put(`${baseUrl}/${id}`, {
            onSuccess: () => {
                form.reset();
                if (options.onSuccess) options.onSuccess();
            },
            onError: (errors) => {
                if (options.onError) options.onError(errors);
            },
        });
    };

    const remove = (id, options = {}) => {
        if (!id) return;
        if (options.confirm !== false) {
            if (
                !confirm(
                    options.confirmMessage || "Are you sure you want to delete?"
                )
            ) {
                if (options.onCancel) options.onCancel();
                return;
            }
        }
        const form = useForm();
        return form.delete(`${baseUrl}/${id}`, {
            onSuccess: options.onSuccess,
            onError: options.onError,
        });
    };

    return { create, update, remove, makeForm };
};
