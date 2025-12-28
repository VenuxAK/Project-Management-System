<?php

use App\Models\Role;

function get_role_id(string $name): int
{
    $id = Role::query()->where('name', $name)->value('id');

    if (!$id) {
        throw new Exception("Role {$name} could not be found.");
    }

    return $id;
}
