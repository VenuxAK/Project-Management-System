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
         * Run the Role seeder file first then run User Seeders
         */

        // Owner / Founder
        $ownerRole = Role::where('name', 'owner')->first();
        $owner = User::factory()->create([
            'name' => 'Minn ArKar',
            'email' => 'minnarkar.founder@example.com',
        ]);
        $owner->roles()->attach($ownerRole->id);

        // Operation Manager
        $operationManagerRole = Role::where('name', 'operation_manager')->first();
        $om = User::factory()->create([
            'name' => 'Minn ArKar',
            'email' => 'minnarkar.om@example.com',
        ]);
        $om->roles()->attach($operationManagerRole->id);

        // // Project Leader
        $projectLeaderRole = Role::where('name', 'project_lead')->first();
        $pld = User::factory()->create([
            'name' => 'Minn ArKar',
            'email' => 'minnarkar.pld@example.com',
        ]);
        $pld->roles()->attach($projectLeaderRole->id);

        // Developers
        $developerRole = Role::where('name', 'developer')->first();

        $developer = User::factory()->create([
            'name' => 'Minn Arkar',
            'email' => 'minnarkar.dev@example.com'
        ]);
        $developer->roles()->attach($developerRole->id);

        $developers = User::factory(5)->create();
        foreach ($developers as $developer) {
            $developer->roles()->attach($developerRole);
        }

        // QA
        $qaRole = Role::where('name', 'qa')->first();
        $qa = User::factory()->create([
            'name' => 'Minn Arkar',
            'email' => 'minnarkar.qa@example.com'
        ]);
        $qa->roles()->attach($qaRole->id);

        // Clients
        $clients = User::factory(3)->create();
        $clientRole = Role::where('name', 'client')->first();
        foreach ($clients as $client) {
            $client->roles()->attach($clientRole->id);
        }
    }
}
