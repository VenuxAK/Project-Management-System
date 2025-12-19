<?php

namespace App\Http\Controllers\Task;

use App\Events\TaskAssigned;
use App\Events\TaskStatusUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\CreateTaskRequest;
use App\Http\Requests\Tasks\DeleteTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::query();         // Initialize Task query builder
        $projects = Project::query();   // Initialize Project query builder

        if ($request->user()->isAdministrator()) {
            $tasks = $tasks->with('assignee');
        }
        if ($request->user()->isProjectManager()) {
            $tasks = $tasks->with('assignee')->where('created_by', $request->user()->id); // Filter tasks created by the project manager
            $projects = $projects->where('created_by', $request->user()->id); // Filter projects created by the project manager
        }
        if ($request->user()->isEmployee()) {
            $tasks = $tasks->with('assignee')->where('assigned_to', $request->user()->id); // Filter tasks assigned to the employee
        }

        return Inertia::render('Task/Index', [
            'tasks' => $tasks->latest()->get(),
            'projects' => $projects->latest()->get(['id', 'name']),
            'users' => User::where('role_id', '3')->get(['id', 'name']),
        ]);
    }

    public function store(CreateTaskRequest $request)
    {
        $task = Task::create([
            "name" => $request->name,
            "priority" => $request->priority,
            "status" => $request->status,
            "start_date" => $request->start_date,
            "due_date" => $request->due_date,
            "project_id" => $request->project_id,
            "assigned_to" => $request->assigned_to,
            "created_by" => Auth::id(),
            "updated_by" => Auth::id(),
        ]);

        // Send event notification to the assigned user
        event(new TaskAssigned(
            task: $task,
            assignedUser: $task->assignee,
            actor: Auth::user()
        ));

        return redirect()->route('tasks.view')->with('success', 'Task created successfully.');
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update([
            "name" => $request->name,
            "status" => $request->status,
            "priority" => $request->priority,
            "assigned_to" => $request->assigned_to,
            "project_id" => $request->project_id,
            "start_date" => $request->start_date,
            "due_date" => $request->due_date,
        ]);

        return back()->with('success', 'Task Updated Successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->update([
            "status" => $task->status === "completed" ? "in_progress" : "completed",
            "updated_by" => Auth::id()
        ]);

        if ($task->status === "completed") {
            // Refresh task instance to get the latest data with relations
            $task->refresh()->load('creator', 'updater');

            // Send event notification about status update
            event(new TaskStatusUpdated(
                task: $task,
                creator: $task->creator,
                actor: Auth::user()
            ));
        }

        return back()->with('success', 'Status updated successfully.');
    }


    public function destroy(DeleteTaskRequest $request, Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.view')->with('success', 'Task deleted successfully.');
    }
}
