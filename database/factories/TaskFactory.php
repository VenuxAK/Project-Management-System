<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pldr = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'like', 'project_lead');
        })->first('id');

        $developerIds = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'like', 'developer');
        })->pluck('id')->toArray();

        $projectIds = Project::pluck('id')->toArray();

        return [
            "name" => $this->faker->sentence(3),
            "priority" => $this->faker->randomElement(['low', 'medium', 'high']),
            "status" => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            "start_date" => $this->faker->dateTimeBetween('-1 month', 'now'),
            "due_date" => $this->faker->dateTimeBetween('now', '+1 month'),
            "assigned_to" => $this->faker->randomElement($developerIds),
            "project_id" => fake()->randomElement($projectIds),
            "created_by" => $pldr->id,
            "updated_by" => $pldr->id,
        ];
    }
}
