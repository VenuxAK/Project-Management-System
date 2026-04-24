<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return inertia('IndexView', [
            "projects" => Project::query()->visibleTo($request->user())->latest()->get(),
            "tasks" => Task::query()->visibleTo($request->user())->latest()->get(),
        ]);
    }
}
