<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner_role_id = get_role_id('owner');

        /**
         * Operation Manager's projects
         */
        $ops_manager = User::query()->whereHas('roles', function ($q) {
            return $q->where('name', 'like', 'operation_manager');
        })->first(['id']);
        $oms_project = Project::factory()->create();
        $oms_project->members()->attach($ops_manager->id, [
            'role_id' => $owner_role_id
        ]);

        /**
         * Project Lead's projects
         */
        $project_lead = User::query()->whereHas('roles', function ($q) {
            return $q->where('name', 'like', 'project_lead');
        })->first(['id']);

        $project_leads_project_1 = Project::factory()->create([
            "created_by" => $project_lead->id,
            "updated_by" => $project_lead->id,
        ]);
        $project_leads_project_1->members()->attach($project_lead->id, [
            "role_id" => $owner_role_id
        ]);

        $project_leads_project_2 = Project::factory()->create([
            "created_by" => $project_lead->id,
            "updated_by" => $project_lead->id,
        ]);
        $project_leads_project_2->members()->attach($project_lead->id, [
            "role_id" => $owner_role_id
        ]);
    }
}
