<?php

use App\Models\User;
use App\Models\Training;
use App\Models\TrainingReservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
});

// ========== INDEX ==========
it('can list all training reservations', function () {
    TrainingReservation::factory()->count(3)->create();

    $response = $this->withToken($this->token)->getJson('/api/training-reservations');

    $response->assertOk()
             ->assertJsonStructure([
                 '*' => ['reservation_id', 'first_name', 'last_name', 'email', 'phone', 'status', 'training_id'],
             ]);
});

// ========== SHOW ==========
it('can get a specific training reservation', function () {
    $reservation = TrainingReservation::factory()->create();

    $response = $this->withToken($this->token)->getJson("/api/training-reservations/{$reservation->reservation_id}");

    $response->assertOk()
             ->assertJsonFragment(['email' => $reservation->email]);
});

// ========== STORE ==========
it('can create a training reservation with file', function () {
    Storage::fake('local');
    $training = Training::factory()->create();
    $file = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf');

    $data = [
        'first_name' => 'Laura',
        'last_name' => 'Martin',
        'email' => 'laura@example.com',
        'phone' => '0600000000',
        'status' => 'pending',
        'pay' => "payed",
        'interview_date' => now()->addWeek()->format('Y-m-d'),
        'training_id' => $training->training_id,
        'user_id' => $this->user->user_id,
        'application_file' => $file,
    ];

    $response = $this->withToken($this->token)->postJson('/api/training-reservations', $data);

    $response->assertCreated()
             ->assertJsonFragment(['email' => 'laura@example.com']);

    Storage::assertExists('applications/' . $file->hashName());
    $this->assertDatabaseHas('training_reservations', ['email' => 'laura@example.com']);
});

// ========== UPDATE ==========
it('can update a training reservation', function () {
    $training = Training::factory()->create();
    $reservation = TrainingReservation::factory()->create();

    $data = [
        'first_name' => 'Updated',
        'last_name' => 'User',
        'email' => 'updated@example.com',
        'phone' => '0700000000',
        'status' => 'confirmed',
        'interview_date' => now()->addWeek()->format('Y-m-d'),
        'pay' => "payed",
    ];

    $response = $this->withToken($this->token)->putJson("/api/training-reservations/{$reservation->reservation_id}", $data);

    $response->assertOk()
             ->assertJsonFragment(['email' => 'updated@example.com']);

    $this->assertDatabaseHas('training_reservations', ['email' => 'updated@example.com']);
});

// ========== DESTROY ==========
it('can delete a training reservation', function () {
    $reservation = TrainingReservation::factory()->create();

    $response = $this->withToken($this->token)->deleteJson('/api/training-reservations', [
        'reservation_id' => $reservation->reservation_id,
    ]);

    $response->assertOk()
             ->assertJson(['message' => 'Réservation de formation supprimée avec succès']);

    $this->assertDatabaseMissing('training_reservations', ['reservation_id' => $reservation->reservation_id]);
});
