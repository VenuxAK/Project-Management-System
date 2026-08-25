<?php

namespace Tests\Feature\Services;

use App\Events\TaskAssigned;
use App\Events\TaskStatusUpdated;
use App\Models\Task;
use App\Models\User;
use App\Services\Tasks\TaskServiceInterface;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    private TaskServiceInterface $taskService;
    private User $pl;
    private User $dev;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskService = app(TaskServiceInterface::class);
        $this->pl = $this->createUserWithRole('project_lead');
        $this->dev = $this->createUserWithRole('developer');
    }

    /** @test */
    public function create_task_persists_and_fires_event()
    {
        Event::fake();
        $project = $this->createProjectWithOwner($this->pl);

        $task = $this->taskService->create([
            'name' => 'Event Test Task',
            'priority' => 'high',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $project->id,
            'assigned_to' => $this->dev->id,
        ], $this->pl);

        $this->assertDatabaseHas('tasks', ['name' => 'Event Test Task']);
        $this->assertEquals($this->pl->id, $task->created_by);
        $this->assertEquals($this->dev->id, $task->assigned_to);
        $this->assertEquals($project->id, $task->project_id);

        Event::assertDispatched(TaskAssigned::class, function ($event) use ($task) {
            return $event->task->id === $task->id
                && $event->assignedUser->id === $this->dev->id
                && $event->actor->id === $this->pl->id;
        });
    }

    /** @test */
    public function create_task_defaults_status_to_pending()
    {
        $project = $this->createProjectWithOwner($this->pl);

        $task = $this->taskService->create([
            'name' => 'Default Status Task',
            'priority' => 'medium',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $project->id,
            'assigned_to' => $this->dev->id,
        ], $this->pl);

        $this->assertEquals('pending', $task->status);
    }

    /** @test */
    public function update_task_modifies_fields()
    {
        $project = $this->createProjectWithOwner($this->pl);

        $task = $this->taskService->create([
            'name' => 'Original',
            'priority' => 'low',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $project->id,
            'assigned_to' => $this->dev->id,
        ], $this->pl);

        $this->taskService->update($task, [
            'name' => 'Updated',
            'priority' => 'high',
            'status' => 'in_progress',
            'assigned_to' => $this->dev->id,
            'project_id' => $project->id,
            'start_date' => '2026-01-01',
            'due_date' => '2026-07-01',
        ], $this->pl);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated',
            'priority' => 'high',
            'status' => 'in_progress',
            'due_date' => '2026-07-01',
        ]);
    }

    /** @test */
    public function toggle_status_changes_completed_to_in_progress()
    {
        $project = $this->createProjectWithOwner($this->pl);

        $task = $this->taskService->create([
            'name' => 'Toggle Me',
            'priority' => 'medium',
            'status' => 'completed',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $project->id,
            'assigned_to' => $this->dev->id,
        ], $this->pl);

        $updatedTask = $this->taskService->toggleStatus($task, $this->dev);

        $this->assertEquals('in_progress', $updatedTask->status);
    }

    /** @test */
    public function toggle_status_changes_pending_to_completed()
    {
        Event::fake();
        $project = $this->createProjectWithOwner($this->pl);

        $task = $this->taskService->create([
            'name' => 'Complete Me',
            'priority' => 'medium',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $project->id,
            'assigned_to' => $this->dev->id,
        ], $this->pl);

        $updatedTask = $this->taskService->toggleStatus($task, $this->pl);

        $this->assertEquals('completed', $updatedTask->status);
        Event::assertDispatched(TaskStatusUpdated::class);
    }

    /** @test */
    public function toggle_status_fires_event_on_both_directions()
    {
        Event::fake();
        $project = $this->createProjectWithOwner($this->pl);

        $task = $this->taskService->create([
            'name' => 'BiDirectional',
            'priority' => 'low',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $project->id,
            'assigned_to' => $this->dev->id,
        ], $this->pl);

        // pending -> completed
        $this->taskService->toggleStatus($task, $this->pl);
        Event::assertDispatched(TaskStatusUpdated::class);

        Event::fake();

        $task->refresh();
        // completed -> in_progress
        $this->taskService->toggleStatus($task, $this->pl);
        Event::assertDispatched(TaskStatusUpdated::class);
    }

    /** @test */
    public function delete_task_removes_from_database()
    {
        $project = $this->createProjectWithOwner($this->pl);

        $task = $this->taskService->create([
            'name' => 'Deletable',
            'priority' => 'low',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $project->id,
            'assigned_to' => $this->dev->id,
        ], $this->pl);

        $this->taskService->delete($task);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function create_task_sets_created_and_updated_by()
    {
        $project = $this->createProjectWithOwner($this->pl);

        $task = $this->taskService->create([
            'name' => 'Audit Trail',
            'priority' => 'medium',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $project->id,
            'assigned_to' => $this->dev->id,
        ], $this->pl);

        $this->assertEquals($this->pl->id, $task->created_by);
        $this->assertEquals($this->pl->id, $task->updated_by);
    }

    /** @test */
    public function create_task_can_assign_to_different_developer()
    {
        $project = $this->createProjectWithOwner($this->pl);
        $dev2 = $this->createUserWithRole('developer');

        $task = $this->taskService->create([
            'name' => 'Assigned Task',
            'priority' => 'high',
            'status' => 'pending',
            'start_date' => '2026-01-01',
            'due_date' => '2026-06-01',
            'project_id' => $project->id,
            'assigned_to' => $dev2->id,
        ], $this->pl);

        $this->assertEquals($dev2->id, $task->assigned_to);
    }
}
