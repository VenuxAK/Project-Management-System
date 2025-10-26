<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Project/Index', [
            "projects" => Project::with("creator")->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        // Validate and store the project data
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "status" => ["required", "in:planning,active,completed,on-hold"],
            "start_date" => ["required", "date"],
            "deadline" => ["required", "date", "after_or_equal:start_date"],
        ]);

        Project::create([
            "name" => $request->name,
            "status" => $request->status,
            "start_date" => $request->start_date,
            "deadline" => $request->deadline,
            "created_by" => Auth::id(),
            "updated_by" => Auth::id(),
        ]);

        return redirect()->route('projects.view')->with('success', 'Project created successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return back()->with('success', 'Project deleted successfully.');
    }
}
