<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectRequest;
use App\Http\Requests\Projects\DeleteProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use App\Services\Projects\ProjectServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ProjectController extends Controller
{

    public function __construct(private ProjectServiceInterface $projectService) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Project::class);



        return Inertia::render('Project/Index', [
            "projects" => Project::query()->visibleTo($request->user())->latest()->get(),
            'project' => Inertia::optional(
                fn() =>
                Project::query()->with([
                    'members:id, name',
                    'members.roles:id, name'
                ])->where('id', $request->pj_id)->first()
            ), // Load specific project if pj_id is provided to update
            "users" => User::select('id', 'name')->employee()->get(),
            "roles" => Role::select('id', 'name')->get()
        ]);
    }

    public function store(CreateProjectRequest $request)
    {
        $validated = $request->safe()->only('members');
        $members = collect($validated['members'] ?? [])
            ->filter(fn($m) => !empty($m['user_id']) && !empty($m['role_id']))
            ->values()
            ->toArray();
        $this->projectService->create(
            $request->safe()->only(['name', 'status', 'start_date', 'deadline']),
            $request->user(),
            $members // Extract just the IDs
        );

        return redirect()->route('projects.view')->with('success', 'Project created successfully.');
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->projectService->update($project, $request->validated(), $request->user());

        return back()->with('success', 'Project ' . $project->id . ' was updated!');
    }

    public function destroy(DeleteProjectRequest $request, Project $project)
    {
        $this->projectService->delete($project);

        return back()->with('success', 'Project deleted successfully.');
    }
}
