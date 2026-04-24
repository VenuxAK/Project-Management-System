<?php

namespace Tests\Feature\Authorization;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class TaskPolicyTest extends TestCase
{
    private Project $project;
    private User $pl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pl = $this->createUserWithRole('project_lead');
        $this->project = $this->createProjectWithOwner($this->pl);
    }

    /** @test */
    public function guest_cannot_view_tasks()
    {
        $response = $this->get('/tasks');
        $response->assertRedirect('/signin');
    }

    /** @test */
    public function owner_can_view_all_tasks()
    {
        $owner = $this->createUserWithRole('owner');
        $this->actingAs($owner);
        $response = $this->get('/tasks');
        $response->assertStatus(200);
    }

    /** @test */
    public function project_lead_can_create_task()
    {
        $this->actingAs($this->pl);
        $dev = $this->createUserWithRole('developer');

        $response = $this->post('/tasks', [
            'name' => 'Test Task',
            'priority' => 'medium',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tasks', ['name' => 'Test Task']);
    }

    /** @test */
    public function developer_cannot_create_task()
    {
        $dev = $this->createUserWithRole('developer');
        $this->addProjectMember($this->project, $dev, 'developer');
        $this->actingAs($dev);

        $response = $this->post('/tasks', [
            'name' => 'Unauthorized Task',
            'priority' => 'low',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function developer_can_update_own_assigned_task()
    {
        $dev = $this->createUserWithRole('developer');
        $this->addProjectMember($this->project, $dev, 'developer');
        $this->actingAs($this->pl);

        $task = Task::create([
            'name' => 'Dev Task',
            'priority' => 'high',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
            'created_by' => $this->pl->id,
            'updated_by' => $this->pl->id,
        ]);

        $this->actingAs($dev);

        $response = $this->put("/tasks/{$task->id}", [
            'name' => 'Updated by Dev',
            'priority' => 'high',
            'status' => 'in_progress',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tasks', ['name' => 'Updated by Dev']);
    }

    /** @test */
    public function developer_cannot_update_task_assigned_to_another()
    {
        $dev1 = $this->createUserWithRole('developer');
        $dev2 = $this->createUserWithRole('developer');
        $this->addProjectMember($this->project, $dev1, 'developer');
        $this->addProjectMember($this->project, $dev2, 'developer');
        $this->actingAs($this->pl);

        $task = Task::create([
            'name' => 'Assigned to Dev2',
            'priority' => 'medium',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev2->id,
            'created_by' => $this->pl->id,
            'updated_by' => $this->pl->id,
        ]);

        $this->actingAs($dev1);

        $response = $this->put("/tasks/{$task->id}", [
            'name' => 'Hacked',
            'priority' => 'medium',
            'status' => 'completed',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev2->id,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function owner_can_update_any_task_even_not_assigned()
    {
        $owner = $this->createUserWithRole('owner');
        $dev = $this->createUserWithRole('developer');
        $this->addProjectMember($this->project, $dev, 'developer');
        $this->actingAs($this->pl);

        $task = Task::create([
            'name' => 'Owner bypass task',
            'priority' => 'low',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
            'created_by' => $this->pl->id,
            'updated_by' => $this->pl->id,
        ]);

        $this->actingAs($owner);

        $response = $this->put("/tasks/{$task->id}", [
            'name' => 'Owner Updated',
            'priority' => 'low',
            'status' => 'completed',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tasks', ['name' => 'Owner Updated']);
    }

    /** @test */
    public function project_lead_can_delete_task()
    {
        $this->actingAs($this->pl);
        $dev = $this->createUserWithRole('developer');
        $this->addProjectMember($this->project, $dev, 'developer');

        $task = Task::create([
            'name' => 'Deletable Task',
            'priority' => 'medium',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
            'created_by' => $this->pl->id,
            'updated_by' => $this->pl->id,
        ]);

        $response = $this->delete("/tasks/{$task->id}");
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function developer_cannot_delete_task()
    {
        $dev = $this->createUserWithRole('developer');
        $this->addProjectMember($this->project, $dev, 'developer');
        $this->actingAs($this->pl);

        $task = Task::create([
            'name' => 'Undeletable',
            'priority' => 'medium',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
            'created_by' => $this->pl->id,
            'updated_by' => $this->pl->id,
        ]);

        $this->actingAs($dev);

        $response = $this->delete("/tasks/{$task->id}");
        $response->assertStatus(403);
    }

    /** @test */
    public function developer_can_toggle_status_of_own_task()
    {
        $dev = $this->createUserWithRole('developer');
        $this->addProjectMember($this->project, $dev, 'developer');
        $this->actingAs($this->pl);

        $task = Task::create([
            'name' => 'Status Toggle Task',
            'priority' => 'low',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
            'created_by' => $this->pl->id,
            'updated_by' => $this->pl->id,
        ]);

        $this->actingAs($dev);

        $response = $this->patch("/tasks/{$task->id}/update-status");
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function client_cannot_update_task_status()
    {
        $client = $this->createUserWithRole('client');
        $this->addProjectMember($this->project, $client, 'client');
        $dev = $this->createUserWithRole('developer');
        $this->addProjectMember($this->project, $dev, 'developer');
        $this->actingAs($this->pl);

        $task = Task::create([
            'name' => 'Client no touch',
            'priority' => 'medium',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $this->project->id,
            'assigned_to' => $dev->id,
            'created_by' => $this->pl->id,
            'updated_by' => $this->pl->id,
        ]);

        $this->actingAs($client);

        $response = $this->patch("/tasks/{$task->id}/update-status");
        $response->assertStatus(403);
    }
}
