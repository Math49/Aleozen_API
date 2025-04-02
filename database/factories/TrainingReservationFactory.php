<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Training;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingReservation>
 */
class TrainingReservationFactory extends Factory
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
            'phone' => $this->faker->numerify('06########'),
            'application_file' => $this->faker->word . '.pdf',
            'interview_date' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'pay' => $this->faker->randomFloat(2, 100, 1000),
            'training_id' => Training::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
        ];
    }

    public function withTraining(int $trainingId): static
    {
        return $this->state(fn (array $attributes) => [
            'training_id' => $trainingId,
        ]);
    }
    public function withUser(int $userId): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $userId,
        ]);
    }
}
