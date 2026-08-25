<?php

namespace Tests\Feature\Authorization;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class ProjectMemberControllerTest extends TestCase
{
    /** @test */
    public function project_lead_can_attach_members()
    {
        $lead = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($this->createUserWithRole('owner'));
        $this->addProjectMember($project, $lead, 'project_lead');

        $developer = User::factory()->create();
        $developerRole = Role::where('name', 'developer')->first();

        $response = $this->actingAs($lead)->post(
            "/projects/{$project->id}/members",
            [
                'members' => [
                    ['user_id' => $developer->id, 'role_id' => $developerRole->id],
                ],
            ]
        );

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $developer->id,
            'role_id' => $developerRole->id,
        ]);
    }

    /** @test */
    public function non_manager_cannot_attach_members()
    {
        $developer = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($this->createUserWithRole('owner'));
        $this->addProjectMember($project, $developer, 'developer');

        $newUser = User::factory()->create();
        $role = Role::where('name', 'qa')->first();

        $response = $this->actingAs($developer)->post(
            "/projects/{$project->id}/members",
            [
                'members' => [
                    ['user_id' => $newUser->id, 'role_id' => $role->id],
                ],
            ]
        );

        $response->assertForbidden();
        $this->assertDatabaseMissing('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $newUser->id,
        ]);
    }

    /** @test */
    public function attach_requires_valid_members_payload()
    {
        $owner = $this->createUserWithRole('owner');
        $project = $this->createProjectWithOwner($owner);

        $response = $this->actingAs($owner)->post(
            "/projects/{$project->id}/members",
            ['members' => []]
        );

        $response->assertSessionHasErrors('members');
    }

    /** @test */
    public function attach_rejects_nonexistent_user()
    {
        $owner = $this->createUserWithRole('owner');
        $project = $this->createProjectWithOwner($owner);
        $role = Role::where('name', 'developer')->first();

        $response = $this->actingAs($owner)->post(
            "/projects/{$project->id}/members",
            [
                'members' => [
                    ['user_id' => 999999, 'role_id' => $role->id],
                ],
            ]
        );

        $response->assertSessionHasErrors('members.0.user_id');
    }

    /** @test */
    public function operation_manager_can_change_member_role()
    {
        $om = $this->createUserWithRole('operation_manager');
        $project = $this->createProjectWithOwner($this->createUserWithRole('owner'));
        $member = User::factory()->create();
        $this->addProjectMember($project, $member, 'developer');
        $qaRole = Role::where('name', 'qa')->first();

        $response = $this->actingAs($om)->patch(
            "/projects/{$project->id}/members/{$member->id}",
            ['role_id' => $qaRole->id]
        );

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $member->id,
            'role_id' => $qaRole->id,
        ]);
    }

    /** @test */
    public function change_role_requires_valid_role()
    {
        $owner = $this->createUserWithRole('owner');
        $project = $this->createProjectWithOwner($owner);
        $member = User::factory()->create();
        $this->addProjectMember($project, $member, 'developer');

        $response = $this->actingAs($owner)->patch(
            "/projects/{$project->id}/members/{$member->id}",
            ['role_id' => 999999]
        );

        $response->assertSessionHasErrors('role_id');
    }

    /** @test */
    public function manager_can_remove_member()
    {
        $owner = $this->createUserWithRole('owner');
        $project = $this->createProjectWithOwner($owner);
        $member = User::factory()->create();
        $this->addProjectMember($project, $member, 'developer');

        $response = $this->actingAs($owner)->delete(
            "/projects/{$project->id}/members/{$member->id}"
        );

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $member->id,
        ]);
    }

    /** @test */
    public function project_owner_member_cannot_be_removed()
    {
        $owner = $this->createUserWithRole('owner');
        $project = $this->createProjectWithOwner($owner);

        $response = $this->actingAs($owner)->delete(
            "/projects/{$project->id}/members/{$owner->id}"
        );

        $response->assertForbidden();
        $this->assertDatabaseHas('project_user_roles', [
            'project_id' => $project->id,
            'user_id' => $owner->id,
        ]);
    }

    /** @test */
    public function guest_cannot_manage_project_members()
    {
        $project = $this->createProjectWithOwner($this->createUserWithRole('owner'));
        $member = User::factory()->create();
        $this->addProjectMember($project, $member, 'developer');

        $this->post("/projects/{$project->id}/members", ['members' => []])
            ->assertRedirect('/signin');
        $this->patch("/projects/{$project->id}/members/{$member->id}", ['role_id' => 1])
            ->assertRedirect('/signin');
        $this->delete("/projects/{$project->id}/members/{$member->id}")
            ->assertRedirect('/signin');
    }
}
