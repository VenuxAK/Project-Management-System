<?php

namespace App\Services\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class ProjectMemberService
{
    /**
     * Attach members to a project with role
     */
    public function addMembers(Project $project, array $members): Project|array
    {
        return DB::transaction(function () use ($project, $members) {
            $payload = [];

            foreach ($members as $member) {
                $payload[$member['user_id']] = [
                    'role_id' => $member['role_id'],
                ];
            }

            return $project->members()->syncWithoutDetaching($payload);
        });
    }


    /**
     * Change member role in project
     */
    public function updateMemberRole(Project $project, User $user, int $role_id): void
    {
        $project->members()->updateExistingPivot($user->id, [
            'role_id' => $role_id
        ]);
    }

    /**
     * Update members
     */
    public function sync(Project $project, array $members): void
    {
        $payload = [];

        foreach ($members as $member) {
            $payload[$member['user_id']] = [
                'role_id' => $member['role_id']
            ];
        }

        $project->members()->sync($payload);
    }

    /**
     * Remove member from project
     */
    public function removeMember(Project $project, User $user): void
    {
        if ($user->hasRole('owner', $project))
            throw new AuthorizationException('Project owner cannot be removed.');

        $project->members()->detach($user->id);
    }


}
