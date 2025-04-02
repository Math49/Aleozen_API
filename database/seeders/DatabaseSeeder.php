<?php

use App\Models\User;
use App\Models\Training;
use App\Models\TrainingContent;
use App\Models\TrainingReservation;
use App\Models\Course;
use App\Models\CourseReservation;
use App\Models\InterventionReservation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Création de 10 utilisateurs
        $users = User::factory(10)->create();

        // Création de 5 formations
        Training::factory(5)->create()->each(function ($training) use ($users) {
            // Pour chaque training, on crée 3 contenus
            TrainingContent::factory(3)->create([
                'training_id' => $training->id,
            ]);
            

            // Et 5 réservations associées à des utilisateurs aléatoires
            foreach (range(1, 5) as $_) {
                $randomUser = $users->random();
                TrainingReservation::factory()
                    ->withTraining($training->id)
                    ->withUser($randomUser->id)
                    ->create();
            }
        });

        // Création de 5 cours
        Course::factory(5)->create()->each(function ($course) {
            // Chaque cours reçoit 3 réservations
            CourseReservation::factory(3)->withCourse($course->id)->create();
        });

        // Création de 10 réservations d'intervention
        InterventionReservation::factory(10)->create();
    }
}

