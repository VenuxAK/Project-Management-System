<?php

namespace App\Services\Tasks;

use App\Events\TaskAssigned;
use App\Events\TaskStatusUpdated;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskService implements TaskServiceInterface
{
    public function create(array $data, User $actor): Task
    {
        return DB::transaction(function () use ($data, $actor) {
            $task = Task::create([
                "name" => $data['name'],
                "priority" => $data['priority'],
                "status" => $data['status'],
                "start_date" => $data['start_date'],
                "due_date" => $data['due_date'],
                "project_id" => $data['project_id'],
                "assigned_to" => $data['assigned_to'],
                "created_by" => $actor->id,
                "updated_by" => $actor->id,
            ]);

            // Send event notification to the assigned user
            event(new TaskAssigned(
                task: $task,
                assignedUser: $task->assignee,
                actor: $actor
            ));

            return $task;
        });
    }

    public function update(Task $task, array $data, User $actor): Task
    {
        return DB::transaction(function () use ($task, $data, $actor) {

            // Implement later ***
            // $assigneeChanged = array_key_exists('assigned_to', $data) && $data['assigned_to'] !== $task->assigned_to;

            $task->update([
                "name" => $data['name'],
                "status" => $data['status'],
                "priority" => $data['priority'],
                "assigned_to" => $data['assigned_to'],
                "project_id" => $data['project_id'],
                "start_date" => $data['start_date'],
                "due_date" => $data['due_date'],
                "updated_by" => $actor->id
            ]);

            return $task;
        });
    }

    public function toggleStatus(Task $task, User $actor): Task
    {
        return DB::transaction(function () use ($task, $actor) {
            $task->update([
                "status" => $task->status === "completed" ? "in_progress" : "completed",
                "updated_by" => $actor->id
            ]);

            if ($task->status === "completed") {
                // Refresh task instance to get the latest data with relations
                $task->refresh()->load('creator', 'updater');

                // Send event notification about status update
                event(new TaskStatusUpdated(
                    task: $task,
                    creator: $task->creator,
                    actor: $actor
                ));
            }

            return $task;
        });
    }
}
