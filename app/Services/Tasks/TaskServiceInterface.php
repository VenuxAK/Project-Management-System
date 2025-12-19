<?php

namespace App\Services\Tasks;

use App\Models\Task;
use App\Models\User;

interface TaskServiceInterface
{
    // public function listTasksForUser(Request $request): Task;

    public function create(array $data, User $actor): Task;

    public function update(Task $task, array $data, User $actor): Task;

    public function toggleStatus(Task $task, User $actor): Task;
}
