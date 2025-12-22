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
            'create_project',
            'update_project',
            'view_project',
            'view_all_projects',
            'delete_project',
            'force_delete_project',

            // Tasks
            'view_task',
            'view_all_tasks',
            'view_project_tasks',
            'create_task',
            'update_any_task',
            'update_own_task',
            'delete_task',
            'force_delete_task',

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
