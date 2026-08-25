<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\AttachProjectMembersRequest;
use App\Http\Requests\Projects\RemoveProjectMemberRequest;
use App\Http\Requests\Projects\UpdateProjectMemberRequest;
use App\Models\Project;
use App\Models\User;
use App\Services\Projects\ProjectMemberService;

class ProjectMemberController extends Controller
{
    public function __construct(private ProjectMemberService $memberService) {}

    public function store(AttachProjectMembersRequest $request, Project $project)
    {
        $this->memberService->addMembers($project, $request->validated('members'));

        return back()->with('success', 'Project members added.');
    }

    public function update(UpdateProjectMemberRequest $request, Project $project, User $user)
    {
        $this->memberService->updateMemberRole($project, $user, $request->validated('role_id'));

        return back()->with('success', 'Member role updated.');
    }

    public function destroy(RemoveProjectMemberRequest $request, Project $project, User $user)
    {
        $this->memberService->removeMember($project, $user);

        return back()->with('success', 'Member removed from the project.');
    }
}
