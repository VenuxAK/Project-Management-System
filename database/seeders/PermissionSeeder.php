<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Projects
            'view_project',
            'view_all_projects',
            'create_project',
            'update_project',
            'update_any_project',
            'delete_project',
            'manage_project_members',

            // Tasks
            'view_task',
            'view_all_tasks',
            'view_project_tasks',
            'create_task',
            'assign_task',
            'update_task',
            'update_any_task',
            'delete_task',

            // Users / System
            'manage_users',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }
    }
}
