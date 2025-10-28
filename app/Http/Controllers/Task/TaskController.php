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
        $task = $request->validated();

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

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $request->validate([
            "name" => ["required", "string"],
            "priority" => ["required", "string", "in:low,medium,high"],
            "status" => ["required", "string", "in:pending,in_progress,completed"],
            "assigned_to" => ["required", "exists:users,id"],
            "project_id" => ["required", "exists:projects,id"],
            "start_date" => ["required", "date"],
            "due_date" => ["required", "date", "after_or_equal:start_date"],
        ]);

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

    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            "status" => $task->status === "completed" ? "in_progress" : "completed"
        ]);

        return back()->with('success', 'Status updated successfully.');
    }


    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('tasks.view')->with('success', 'Task deleted successfully.');
    }
}
