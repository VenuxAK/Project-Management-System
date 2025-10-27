<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia('IndexView', [
            "projects" => Project::latest()->get(),
            "tasks" => Task::latest()->get(),
        ]);
    }
}
