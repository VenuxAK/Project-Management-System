<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\CreateTaskRequest;
use App\Http\Requests\Tasks\DeleteTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\Tasks\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Task::class);

        return Inertia::render('Task/Index', [
            'tasks' => Task::with('assignee')->visibleTo($request->user())->latest()->get(),
            'projects' => Project::query()->visibleTo($request->user())->latest()->get(['id', 'name']),
            'users' => User::query()->employee()->latest()->get()
        ]);
    }

    public function store(CreateTaskRequest $request)
    {
        $this->taskService->create(
            data: $request->validated(),
            actor: $request->user()
        );

        return redirect()->route('tasks.view')
            ->with('success', 'Task created successfully.');
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->taskService->update(
            task: $task,
            data: $request->validated(),
            actor: $request->user()
        );

        return back()->with('success', 'Task Updated Successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        Gate::authorize('updateStatus', $task);

        $this->taskService->toggleStatus(
            task: $task,
            actor: $request->user()
        );

        return back()->with('success', 'Status updated successfully.');
    }


    public function destroy(DeleteTaskRequest $request, Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.view')
            ->with('success', 'Task deleted successfully.');
    }
}
