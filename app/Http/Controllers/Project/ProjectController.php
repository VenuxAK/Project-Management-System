<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectRequest;
use App\Http\Requests\Projects\DeleteProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::query(); // Initialize project query builder

        if ($request->user()->isAdministrator()) {
            $projects = $projects->with("creator"); // Eager load creator relationship
        }
        if ($request->user()->isProjectManager()) {
            $projects = $projects->where('created_by', $request->user()->id); // Filter projects by creator
        }

        return Inertia::render('Project/Index', [
            "projects" => $projects->latest()->get(),
            'project' => Inertia::optional(fn() => Project::where('id', $request->pj_id)->get()) // Load specific project if pj_id is provided
        ]);
    }

    public function store(CreateProjectRequest $request)
    {
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

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update([
            "name" => $request->name,
            "status" => $request->status,
            "start_date" => $request->start_date,
            "deadline" => $request->deadline,
        ]);

        return back()->with('success', 'Project ' . $project->id . ' was updated!');
    }

    public function destroy(DeleteProjectRequest $request, Project $project)
    {
        $project->delete();

        return back()->with('success', 'Project deleted successfully.');
    }
}
