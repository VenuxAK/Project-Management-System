<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Run first the Role and User Seeders
         * Create Roles
         */
        Role::factory()->create([
            'name' => 'Admin',
        ]);
        Role::factory()->create([
            'name' => 'Project Manager',
        ]);
        Role::factory()->create([
            'name' => 'Developer',
        ]);

        User::factory()->create([
            'name' => 'Minn ArKar',
            'email' => 'minnarkar.admin@example.com',
            'role_id' => 1,
        ]);
        User::factory()->create([
            'name' => 'ArKar Minn',
            'email' => 'arkarminn.pm@example.com',
            'role_id' => 2,
        ]);
        User::factory(8)->create();
    }
}
