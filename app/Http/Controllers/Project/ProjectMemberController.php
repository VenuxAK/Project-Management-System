<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    //
}

/**
 *

    public function store(Request $request, Project $project)
    {
        $this->authorize('manageMembers', $project);

        $validated = $request->validate([
            'members' => 'required|array|min:1',
            'members.*.user_id' => 'required|exists:users,id',
            'members.*.role_id' => 'required|exists:roles,id',
        ]);

        $this->memberService->syncMembers($project, $validated['members']);

        return back()->with('success', 'Project members added.');
    }

 *
 */
