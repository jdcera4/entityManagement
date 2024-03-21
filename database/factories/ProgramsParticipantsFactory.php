<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramsParticipants>
 */
class ProgramsParticipantsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_id' => rand(1, 10),
            'entity_type' => fake()->randomElement(['user', 'company', 'challenge']),
            'entity_id' => rand(1, 10),
        ];
    }
}
