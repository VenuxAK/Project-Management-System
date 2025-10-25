<?php

namespace Database\Factories;

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
        return [
            "name" => $this->faker->sentence(3),
            "priority" => $this->faker->randomElement(['low', 'medium', 'high']),
            "status" => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            "start_date" => $this->faker->dateTimeBetween('-1 month', 'now'),
            "due_date" => $this->faker->dateTimeBetween('now', '+1 month'),
            "assigned_to" => $this->faker->numberBetween(3, 10),
            "project_id" => $this->faker->numberBetween(1, 3),
            "created_by" => 2,
            "updated_by" => 2,
        ];
    }
}
