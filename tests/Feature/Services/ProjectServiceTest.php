<?php

namespace Tests\Feature\Services;

use App\Models\Role;
use App\Models\User;
use App\Services\Projects\ProjectServiceInterface;
use Tests\TestCase;

class ProjectServiceTest extends TestCase
{
    private ProjectServiceInterface $projectService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectService = app(ProjectServiceInterface::class);
    }

    /** @test */
    public function create_project_adds_creator_as_owner_member()
    {
        $pl = $this->createUserWithRole('project_lead');

        $project = $this->projectService->create(
            [
                'name' => 'Service Test Project',
                'status' => 'planning',
                'start_date' => '2026-01-01',
                'deadline' => '2026-06-01',
            ],
            $pl
        );

        $this->assertDatabaseHas('projects', ['name' => 'Service Test Project']);
        $this->assertDatabaseHas('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $pl->id,
        ]);

        $ownerRoleId = Role::where('name', 'owner')->value('id');
        $this->assertDatabaseHas('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $pl->id,
            'role_id' => $ownerRoleId,
        ]);
    }

    /** @test */
    public function create_project_with_additional_members()
    {
        $pl = $this->createUserWithRole('project_lead');
        $dev = $this->createUserWithRole('developer');
        $qa = $this->createUserWithRole('qa');

        $devRoleId = Role::where('name', 'developer')->value('id');
        $qaRoleId = Role::where('name', 'qa')->value('id');

        $project = $this->projectService->create(
            [
                'name' => 'Team Project',
                'status' => 'active',
                'start_date' => '2026-01-01',
                'deadline' => '2026-06-01',
            ],
            $pl,
            [
                ['user_id' => $dev->id, 'role_id' => $devRoleId],
                ['user_id' => $qa->id, 'role_id' => $qaRoleId],
            ]
        );

        $this->assertDatabaseHas('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $dev->id,
            'role_id' => $devRoleId,
        ]);
        $this->assertDatabaseHas('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $qa->id,
            'role_id' => $qaRoleId,
        ]);
    }

    /** @test */
    public function update_project_changes_fields()
    {
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);

        $this->projectService->update($project, [
            'name' => 'Updated Project Name',
            'status' => 'completed',
            'start_date' => '2026-01-01',
            'deadline' => '2026-03-01',
        ], $pl);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project Name',
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function update_project_syncs_members()
    {
        $pl = $this->createUserWithRole('project_lead');
        $dev = $this->createUserWithRole('developer');
        $project = $this->createProjectWithOwner($pl);

        $devRoleId = Role::where('name', 'developer')->value('id');

        $this->projectService->update($project, [
            'name' => 'Synced Members',
            'status' => 'active',
            'start_date' => '2026-01-01',
            'deadline' => '2026-06-01',
            'members' => [
                ['user_id' => $dev->id, 'role_id' => $devRoleId],
            ],
        ], $pl);

        $this->assertDatabaseHas('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $dev->id,
            'role_id' => $devRoleId,
        ]);
    }

    /** @test */
    public function delete_project_removes_from_database()
    {
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);

        $this->projectService->delete($project);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /** @test */
    public function create_project_without_members_only_has_creator()
    {
        $pl = $this->createUserWithRole('project_lead');

        $project = $this->projectService->create(
            [
                'name' => 'Solo Project',
                'status' => 'planning',
                'start_date' => '2026-01-01',
                'deadline' => '2026-06-01',
            ],
            $pl
        );

        $membersCount = $project->members()->count();
        $this->assertEquals(1, $membersCount);
    }

    /** @test */
    public function project_factory_defaults_have_valid_data()
    {
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);

        $this->assertNotNull($project->name);
        $this->assertContains($project->status, ['planning', 'active', 'completed', 'on-hold']);
        $this->assertNotNull($project->start_date);
        $this->assertNotNull($project->deadline);
        $this->assertEquals($pl->id, $project->created_by);
        $this->assertEquals($pl->id, $project->updated_by);
    }
}
