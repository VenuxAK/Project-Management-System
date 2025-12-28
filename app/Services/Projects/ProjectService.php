<?php

namespace App\Services\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectService implements ProjectServiceInterface
{
    public function __construct(private ProjectMemberService $memberService) {}

    // Create project-related business logic
    public function create(array $data, User $actor, array $members = []): Project
    {
        return DB::transaction(function () use ($data, $actor, $members) {
            $project = Project::create([
                "name" => $data["name"],
                "status" => $data["status"],
                "start_date" => $data["start_date"],
                "deadline" => $data["deadline"],
                "created_by" => $actor->id,
                "updated_by" => $actor->id,
            ]);

            // Creator becomes project owner
            $project->members()->attach($actor->id, [
                'role_id' => get_role_id('owner')              // Fixed: get owner role from custom helper function \App\Helpers\Helpers.php
            ]);

            // Attach additional members
            // if (!empty($members)) {
            app(ProjectMemberService::class)->addMembers($project, $members);
            // }

            return $project;
        });
    }

    // Update project-related business logic
    public function update(Project $project, array $data, User $actor): Project
    {
        return DB::transaction(function () use ($data, $project, $actor) {
            $project->update([
                "name" => $data['name'],
                "status" => $data['status'],
                "start_date" => $data['start_date'],
                "deadline" => $data['deadline'],
                "updated_by" => $actor->id
            ]);

            if (!empty($data["members"])) {
                $this->memberService->sync($project, $data["members"]);
            }

            return $project;
        });
    }

    // Delete project
    public function delete(Project $project): void
    {
        $project->delete();
    }
}
