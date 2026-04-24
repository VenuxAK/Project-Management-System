<?php

namespace Tests\Feature\Authorization;

use App\Models\Project;
use App\Models\User;
use App\Services\Projects\ProjectAccessService;
use Tests\TestCase;

class ProjectAccessServiceTest extends TestCase
{
    private ProjectAccessService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ProjectAccessService::class);
    }

    /** @test */
    public function owner_has_all_global_permissions_without_project()
    {
        $owner = $this->createUserWithRole('owner');

        $this->assertTrue($this->service->can($owner, 'view_project'));
        $this->assertTrue($this->service->can($owner, 'create_project'));
        $this->assertTrue($this->service->can($owner, 'update_any_project'));
        $this->assertTrue($this->service->can($owner, 'delete_project'));
        $this->assertTrue($this->service->can($owner, 'manage_users'));
        $this->assertTrue($this->service->can($owner, 'view_reports'));
    }

    /** @test */
    public function operation_manager_has_global_permissions_without_project()
    {
        $om = $this->createUserWithRole('operation_manager');

        $this->assertTrue($this->service->can($om, 'view_project'));
        $this->assertTrue($this->service->can($om, 'create_project'));
        $this->assertTrue($this->service->can($om, 'update_project'));
        $this->assertTrue($this->service->can($om, 'manage_project_members'));
    }

    /** @test */
    public function operation_manager_cannot_delete_project_without_project_context()
    {
        $om = $this->createUserWithRole('operation_manager');

        $this->assertFalse($this->service->can($om, 'delete_project'));
    }

    /** @test */
    public function project_lead_has_no_global_permissions_without_project()
    {
        $pl = $this->createUserWithRole('project_lead');

        $this->assertFalse($this->service->can($pl, 'view_project'));
        $this->assertFalse($this->service->can($pl, 'create_project'));
    }

    /** @test */
    public function project_lead_can_view_project_as_member()
    {
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);

        $this->assertTrue($this->service->can($pl, 'view_project', $project));
    }

    /** @test */
    public function project_lead_cannot_view_project_not_member_of()
    {
        $pl = $this->createUserWithRole('project_lead');
        $owner = $this->createUserWithRole('owner');
        $project = $this->createProjectWithOwner($owner);

        $this->assertFalse($this->service->can($pl, 'view_project', $project));
    }

    /** @test */
    public function developer_can_view_and_update_assigned_task_project()
    {
        $dev = $this->createUserWithRole('developer');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->addProjectMember($project, $dev, 'developer');

        $this->assertTrue($this->service->can($dev, 'view_project', $project));
        $this->assertTrue($this->service->can($dev, 'view_task', $project));
        $this->assertTrue($this->service->can($dev, 'update_task', $project));
    }

    /** @test */
    public function developer_cannot_create_task()
    {
        $dev = $this->createUserWithRole('developer');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->addProjectMember($project, $dev, 'developer');

        $this->assertFalse($this->service->can($dev, 'create_task', $project));
        $this->assertFalse($this->service->can($dev, 'delete_task', $project));
    }

    /** @test */
    public function qa_has_same_permissions_as_developer()
    {
        $qa = $this->createUserWithRole('qa');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->addProjectMember($project, $qa, 'qa');

        $this->assertTrue($this->service->can($qa, 'view_project', $project));
        $this->assertTrue($this->service->can($qa, 'view_task', $project));
        $this->assertTrue($this->service->can($qa, 'update_task', $project));
        $this->assertFalse($this->service->can($qa, 'create_task', $project));
        $this->assertFalse($this->service->can($qa, 'delete_task', $project));
    }

    /** @test */
    public function client_can_only_view()
    {
        $client = $this->createUserWithRole('client');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->addProjectMember($project, $client, 'client');

        $this->assertTrue($this->service->can($client, 'view_project', $project));
        $this->assertTrue($this->service->can($client, 'view_task', $project));
        $this->assertFalse($this->service->can($client, 'update_task', $project));
        $this->assertFalse($this->service->can($client, 'create_task', $project));
        $this->assertFalse($this->service->can($client, 'delete_task', $project));
    }

    /** @test */
    public function non_member_is_denied_project_scoped_permission()
    {
        $dev = $this->createUserWithRole('developer');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);

        $this->assertFalse($this->service->can($dev, 'view_project', $project));
    }

    /** @test */
    public function global_permission_bypasses_membership_check()
    {
        $owner = $this->createUserWithRole('owner');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);

        $this->assertTrue($this->service->can($owner, 'update_any_project', $project));
    }

    /** @test */
    public function multiple_roles_resolve_correct_permissions()
    {
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);

        $this->assertTrue($this->service->can($pl, 'manage_project_members', $project));
        $this->assertTrue($this->service->can($pl, 'delete_task', $project));
        $this->assertTrue($this->service->can($pl, 'assign_task', $project));
        $this->assertTrue($this->service->can($pl, 'create_project', $project));
    }

    /** @test */
    public function unknown_permission_is_denied()
    {
        $owner = $this->createUserWithRole('owner');
        $this->assertFalse($this->service->can($owner, 'nonexistent_permission'));
    }

    /** @test */
    public function user_with_no_role_has_no_permissions()
    {
        $user = User::factory()->create();
        $this->assertFalse($this->service->can($user, 'view_project'));
    }
}
