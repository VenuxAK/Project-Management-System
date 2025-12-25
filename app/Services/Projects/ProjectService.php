<?php

namespace App\Services\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectService implements ProjectServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // Create project-related business logic
    public function create(array $data, User $actor): Project
    {
        return DB::transaction(function () use ($data, $actor) {
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
                'role_id' => 1                      // Fixed: Owner role, will update with helper method later
            ]);

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

            return $project;
        });
    }

    // Delete project
    public function delete(Project $project): void
    {
        $project->delete();
    }
}
