<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingContent>
 */
class TrainingContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'files' => $this->faker->filePath(),
            'training_id' => 0,
        ];
    }

    public function withTraining($trainingId): static
    {
        return $this->state(function (array $attributes) use ($trainingId) {
            return [
                'training_id' => $trainingId,
            ];
        });
    }
}
