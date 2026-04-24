<?php

namespace Tests;

use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedPermissions();
        $this->seedRoles();
    }

    protected function seedPermissions(): void
    {
        $permissions = [
            'view_project', 'view_all_projects', 'create_project', 'update_project',
            'update_any_project', 'delete_project', 'manage_project_members',
            'view_task', 'view_all_tasks', 'view_project_tasks', 'create_task',
            'assign_task', 'update_task', 'update_any_task', 'delete_task',
            'manage_users', 'view_reports',
        ];

        foreach ($permissions as $name) {
            Permission::create(['name' => $name]);
        }
    }

    protected function seedRoles(): void
    {
        $allPermissions = Permission::all();

        // owner — global scope, all permissions
        $owner = Role::create(['name' => 'owner', 'scope' => 'global']);
        $owner->permissions()->sync($allPermissions->pluck('id'));

        // operation_manager — global scope, subset
        $om = Role::create(['name' => 'operation_manager', 'scope' => 'global']);
        $om->permissions()->sync(
            Permission::whereIn('name', [
                'view_project', 'view_all_projects', 'create_project', 'update_project',
                'manage_project_members', 'view_task', 'view_all_tasks', 'create_task',
                'assign_task', 'update_task',
            ])->pluck('id')
        );

        // project_lead — project scope
        $pl = Role::create(['name' => 'project_lead', 'scope' => 'project']);
        $pl->permissions()->sync(
            Permission::whereIn('name', [
                'view_project', 'create_project', 'update_project', 'manage_project_members',
                'view_task', 'create_task', 'assign_task', 'update_task', 'delete_task',
            ])->pluck('id')
        );

        // developer — project scope
        $dev = Role::create(['name' => 'developer', 'scope' => 'project']);
        $dev->permissions()->sync(
            Permission::whereIn('name', ['view_project', 'view_task', 'update_task'])->pluck('id')
        );

        // qa — project scope
        $qa = Role::create(['name' => 'qa', 'scope' => 'project']);
        $qa->permissions()->sync(
            Permission::whereIn('name', ['view_project', 'view_task', 'update_task'])->pluck('id')
        );

        // client — project scope
        $client = Role::create(['name' => 'client', 'scope' => 'project']);
        $client->permissions()->sync(
            Permission::whereIn('name', ['view_project', 'view_task'])->pluck('id')
        );
    }

    protected function createUserWithRole(string $roleName): User
    {
        $user = User::factory()->create();
        $role = Role::where('name', $roleName)->first();
        $user->roles()->attach($role);
        return $user;
    }

    protected function createProjectWithOwner(User $owner): Project
    {
        $project = Project::factory()->create([
            'created_by' => $owner->id,
            'updated_by' => $owner->id,
        ]);
        $ownerRole = Role::where('name', 'owner')->first();
        $project->members()->attach($owner->id, ['role_id' => $ownerRole->id]);
        return $project;
    }

    protected function addProjectMember(Project $project, User $user, string $roleName): void
    {
        $role = Role::where('name', $roleName)->first();
        $project->members()->attach($user->id, ['role_id' => $role->id]);
    }
}
