import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export function useAuth() {
    const page = usePage();

    const user = computed(() => page.props.auth?.user ?? null);

    const roles = computed(() => page.props.auth?.roles ?? []);

    const permissions = computed(() => new Set(page.props.auth?.permissions ?? []));

    const can = (permission) => permissions.value.has(permission);

    const hasRole = (roleName) =>
        roles.value.some((role) => role.name === roleName);

    return { user, roles, can, hasRole };
}
