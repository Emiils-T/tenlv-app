<?php

namespace Database\Factories;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tournament>
 */
class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'date' => fake()->dateTimeThisMonth,
            'organiser_id' => fake()->numberBetween(1,3),
            'court_id' => fake()->numberBetween(1,3)

        ];
    }
}
