<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseReservation>
 */
class CourseReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'course_id' => 0,
        ];
    }

    public function withCourse($courseId): static
    {
        return $this->state(function (array $attributes) use ($courseId) {
            return [
                'course_id' => $courseId,
            ];
        });
    }
}
