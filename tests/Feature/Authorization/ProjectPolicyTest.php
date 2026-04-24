<?php

namespace Tests\Feature\Authorization;

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class ProjectPolicyTest extends TestCase
{
    /** @test */
    public function guest_cannot_view_projects()
    {
        $response = $this->get('/projects');
        $response->assertRedirect('/signin');
    }

    /** @test */
    public function owner_can_view_all_projects()
    {
        $owner = $this->createUserWithRole('owner');
        $this->actingAs($owner);

        $response = $this->get('/projects');
        $response->assertStatus(200);
    }

    /** @test */
    public function owner_can_create_project()
    {
        $owner = $this->createUserWithRole('owner');
        $this->actingAs($owner);

        $response = $this->post('/projects', [
            'name' => 'Test Project',
            'status' => 'planning',
            'start_date' => '2026-01-01',
            'deadline' => '2026-06-01',
            'members' => [],
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('projects', ['name' => 'Test Project']);
    }

    /** @test */
    public function owner_can_update_any_project()
    {
        $owner = $this->createUserWithRole('owner');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->actingAs($owner);

        $response = $this->put("/projects/{$project->id}", [
            'name' => 'Updated Name',
            'status' => 'active',
            'start_date' => '2026-01-01',
            'deadline' => '2026-06-01',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('projects', ['name' => 'Updated Name']);
    }

    /** @test */
    public function owner_can_delete_project()
    {
        $owner = $this->createUserWithRole('owner');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->actingAs($owner);

        $response = $this->delete("/projects/{$project->id}");

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /** @test */
    public function project_member_can_view_project()
    {
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->actingAs($pl);

        $response = $this->get('/projects');
        $response->assertStatus(200);
        $response->assertSee($project->name);
    }

    /** @test */
    public function non_member_cannot_see_project_in_list()
    {
        $pl1 = $this->createUserWithRole('project_lead');
        $pl2 = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl1);
        $this->actingAs($pl2);

        $response = $this->get('/projects');
        $response->assertStatus(200);
        $response->assertDontSee($project->name);
    }

    /** @test */
    public function project_lead_can_update_own_project()
    {
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->actingAs($pl);

        $response = $this->put("/projects/{$project->id}", [
            'name' => 'Lead Updated',
            'status' => 'active',
            'start_date' => '2026-01-01',
            'deadline' => '2026-06-01',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('projects', ['name' => 'Lead Updated']);
    }

    /** @test */
    public function developer_cannot_delete_project()
    {
        $dev = $this->createUserWithRole('developer');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->addProjectMember($project, $dev, 'developer');
        $this->actingAs($dev);

        $response = $this->delete("/projects/{$project->id}");
        $response->assertStatus(403);
    }

    /** @test */
    public function developer_cannot_update_project()
    {
        $dev = $this->createUserWithRole('developer');
        $pl = $this->createUserWithRole('project_lead');
        $project = $this->createProjectWithOwner($pl);
        $this->addProjectMember($project, $dev, 'developer');
        $this->actingAs($dev);

        $response = $this->put("/projects/{$project->id}", [
            'name' => 'Hacked',
            'status' => 'active',
            'start_date' => '2026-01-01',
            'deadline' => '2026-06-01',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function client_can_view_but_not_create_projects()
    {
        $client = $this->createUserWithRole('client');
        $this->actingAs($client);

        $response = $this->post('/projects', [
            'name' => 'Client Project',
            'status' => 'planning',
            'start_date' => '2026-01-01',
            'deadline' => '2026-06-01',
            'members' => [],
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthorized_user_gets_403_on_create()
    {
        $dev = $this->createUserWithRole('developer');
        $this->actingAs($dev);

        $response = $this->post('/projects', [
            'name' => 'Dev Project',
            'status' => 'planning',
            'start_date' => '2026-01-01',
            'deadline' => '2026-06-01',
            'members' => [],
        ]);

        $response->assertStatus(403);
    }
}
