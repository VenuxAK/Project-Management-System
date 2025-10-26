<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
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
            "status" => $this->faker->randomElement(['planning', 'active', 'completed', 'on-hold']),
            "start_date" => $this->faker->date(),
            "deadline" => $this->faker->date(),
            "created_by" => 2,
            "updated_by" => 2,
        ];
    }
}
