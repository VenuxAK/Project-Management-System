<?php

namespace App\Services\Projects;

use App\Models\Project;
use App\Models\User;

/**
 * ProjectAccessService
 *
 * This service is the single source of truth for authorization decisions
 * that depend on project context.
 *
 * Responsibilities:
 * - Resolve whether a user can perform a given permission
 * - Enforce project membership rules
 * - Support both global and project-scoped permissions
 *
 * IMPORTANT:
 * - Controllers should NOT contain authorization logic
 * - Policies should delegate permission checks to this service
 * - Roles themselves are context-agnostic (they do not know about projects)
 */
class ProjectAccessService
{

    /**
     * Determine if a user can perform an action represented by a permission
     * within an optional project context.
     *
     * @param User         $user        The acting user
     * @param string       $permission  The permission name (e.g. 'update_task')
     * @param Project|null $project     The project context (if applicable)
     *
     * @return bool
     */
    public function can(User $user, string $permission, ?Project $project = null): bool
    {
        /**
         * STEP 1: Global permission check
         *
         * Some roles (e.g. Owner, Ops Manager) are not bound to projects.
         * If the user has the permission globally, we allow immediately.
         *
         * This avoids:
         * - Attaching global users to every project
         * - Special-casing logic elsewhere
         */
        if ($user->hasGlobalPermission($permission)) {
            return true;
        }

        /**
         * STEP 2: Project context requirement
         *
         * If the permission is not granted globally, it must be evaluated
         * in a project context. Without a project, access is denied.
         *
         * This prevents ambiguous permission usage like:
         *   hasPermission('update_task') without knowing *where*
         */
        if (! $project) {
            return false;
        }

        /**
         * STEP 3: Project membership enforcement
         *
         * A user must be an explicit member of the project to perform
         * any project-scoped action.
         *
         * This is a hard security boundary.
         */
        if (! $project->hasMember($user)) {
            return false;
        }

        /**
         * STEP 4: Resolve the user's role within the project
         *
         * Users can have different roles in different projects.
         * We retrieve the role assigned via the project_user pivot.
         */
        $role = $project->memberRole($user);

        /**
         * Safety check: membership without a role is invalid
         * and should not grant access.
         */
        if (! $role) {
            return false;
        }

        /**
         * STEP 5: Permission check against the project-scoped role
         *
         * The final decision is based on whether the resolved role
         * explicitly grants the requested permission.
         */
        return $role->hasPermission($permission);
    }
}
