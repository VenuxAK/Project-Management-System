<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'owner' => [
                'scope' => 'global',
                'permissions' => Permission::all(),
            ],

            'operation_manager' => [
                'scope' => 'global',
                'permissions' => [
                    'view_project',
                    'view_all_projects',
                    'create_project',
                    'update_project',
                    'manage_project_members',

                    'view_task',
                    'view_all_tasks',
                    'create_task',
                    'assign_task',
                    'update_task',
                ],
            ],

            'project_lead' => [
                'scope' => 'project',
                'permissions' => [
                    'view_project',
                    'create_project',
                    'update_project',

                    'view_task',
                    'view_project_tasks',
                    'create_task',
                    'assign_task',
                    'update_task',
                    'delete_task',
                ],
            ],

            'developer' => [
                'scope' => 'project',
                'permissions' => [
                    'view_project',
                    'view_task',
                    'update_task',
                ],
            ],

            'qa' => [
                'scope' => 'project',
                'permissions' => [
                    'view_project',
                    'view_task',
                    'update_task',
                ],
            ],

            'client' => [
                'scope' => 'project',
                'permissions' => [
                    'view_project',
                    'view_task',
                ],
            ],
        ];

        foreach ($roles as $name => $data) {
            $role = Role::firstOrCreate(
                ['name' => $name],
                ['scope' => $data['scope']]
            );

            if ($data['permissions'] instanceof \Illuminate\Support\Collection) {
                $role->permissions()->sync(
                    $data['permissions']->pluck('id')
                );
            } else {
                $permissionIds = Permission::whereIn('name', $data['permissions'])
                    ->pluck('id');

                $role->permissions()->sync($permissionIds);
            }
        }
    }
}
