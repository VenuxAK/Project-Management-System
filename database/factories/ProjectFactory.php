<?php

namespace Database\Factories;

use App\Models\Role;
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
        $om = Role::where('name', 'operation_manager')->first('id');

        return [
            "name" => $this->faker->sentence(3),
            "status" => $this->faker->randomElement(['planning', 'active', 'completed', 'on-hold']),
            "start_date" => $this->faker->date(),
            "deadline" => $this->faker->date(),
            "created_by" => $om->id,
            "updated_by" => $om->id,
        ];
    }
}
