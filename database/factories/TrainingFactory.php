<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'location' => $this->faker->city,
            'start_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'type' => $this->faker->randomElement(['online', 'offline']),
            'price' => $this->faker->numberBetween(100, 1000),
            'status' => $this->faker->randomElement(['upcoming', 'ongoing', 'completed']),
        ];
    }
}
