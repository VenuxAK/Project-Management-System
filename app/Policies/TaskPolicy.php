<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_task');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->can('viewAny', $task) && $task->isVisibleTo($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_task');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // if($task->project()->)

        // Can update any task in the project
        if ($user->hasPermission('update_any_task', $task->project)) return true;

        // Can update own task in the project
        return $user->hasPermission('update_own_task', $task->project) &&
            $task->assigned_to === $user->id;
    }

    /**
     * Determine wheather the user can update status the model.
     */
    public function updateStatus(User $user, Task $task): bool
    {
        if ($user->hasPermission('update_any_task')) return true;   // Global scope permission
        return $user->hasPermission('update_own_task', $task->project) && $task->assigned_to === $user->id; // Project scope permission
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermission('delete_task', $task->project);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->hasRole('owner');
    }
}
