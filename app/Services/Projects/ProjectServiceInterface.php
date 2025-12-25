<?php

namespace App\Services\Projects;

use App\Models\Project;
use App\Models\User;

interface ProjectServiceInterface
{
    public function create(array $data, User $user): Project;
    public function update(Project $project, array $data, User $user): Project;
    public function delete(Project $project): void;
}
