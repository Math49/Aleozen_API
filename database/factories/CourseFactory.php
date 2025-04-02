<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
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
            'description' => $this->faker->paragraph(2),
            'location' => $this->faker->city,
            'start_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'type' => $this->faker->randomElement(['online', 'offline']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
