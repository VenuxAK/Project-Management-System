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
use Inertia\Inertia;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(Request $request)
    {
        $projects = Project::latest()
            ->when(
                $request->user()->isProjectManager(),
                fn($query)
                => $query->where('created_by', $request->user()->id)
            );

        return Inertia::render('Task/Index', [
            'tasks' => Task::latest()->forUser($request->user())->get(),
            'projects' => $projects->get(['id', 'name']),
            'users' => User::latest()->employee()->get(['id', 'name']),
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
