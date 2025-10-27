<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {

        return Inertia::render('Task/Index', [
            'tasks' => Task::with(['assignee'])->latest()->get(),
            'projects' => Project::latest()->get(['id', 'name']),
            'users' => User::where('role_id', '3')->get(['id', 'name']),
        ]);
    }

    public function store(CreateTaskRequest $request)
    {
        // Logic to store a new task
        $task = $request->validated();

        // Save the task to the database (omitted for brevity)
        $task = Task::create([
            "name" => $task['name'],
            "priority" => $task['priority'],
            "status" => $task['status'],
            "start_date" => $task['start_date'],
            "due_date" => $task['due_date'],
            "project_id" => $task['project_id'],
            "assigned_to" => $task['assigned_to'],
            "created_by" => Auth::id(),
            "updated_by" => Auth::id(),
        ]);

        return redirect()->route('tasks.view')->with('success', 'Task created successfully.');
    }

    public function destroy(Request $request, $id)
    {
        // Logic to delete a task
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('tasks.view')->with('success', 'Task deleted successfully.');
    }
}
