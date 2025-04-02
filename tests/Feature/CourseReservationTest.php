<?php

use App\Models\User;
use App\Models\Course;
use App\Models\CourseReservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
});

// ========== INDEX ==========
it('can list all course reservations', function () {
    CourseReservation::factory()
        ->count(3)
        ->create();


    $response = $this->withToken($this->token)->getJson('/api/course-reservations');

    $response->assertOk()
             ->assertJsonStructure([
                 '*' => ['reservation_id', 'first_name', 'last_name', 'email', 'phone', 'status', 'course_id'],
             ]);
});

// ========== SHOW ==========
it('can get a specific course reservation by id', function () {
    $reservation = CourseReservation::factory()->create();

    $response = $this->withToken($this->token)->getJson("/api/course-reservations/{$reservation->reservation_id}");

    $response->assertOk()
             ->assertJsonFragment(['email' => $reservation->email]);
});

// ========== STORE ==========
it('can create a course reservation', function () {
    $course = Course::factory()->create();

    $data = [
        'first_name' => 'Alice',
        'last_name' => 'Smith',
        'email' => 'alice@example.com',
        'phone' => '0600000000',
        'status' => 'pending',
        'course_id' => $course->course_id,
    ];

    $response = $this->withToken($this->token)->postJson('/api/course-reservations', $data);

    $response->assertCreated()
             ->assertJsonFragment(['email' => 'alice@example.com']);

    $this->assertDatabaseHas('course_reservations', ['email' => 'alice@example.com']);
});

// ========== UPDATE ==========
it('can update a course reservation', function () {
    $reservation = CourseReservation::factory()->create();

    $response = $this->withToken($this->token)->putJson("/api/course-reservations/{$reservation->reservation_id}", [
        'status' => 'confirmed',
        'first_name' => 'UpdatedName',
    ]);

    $response->assertOk()
             ->assertJsonFragment(['status' => 'confirmed', 'first_name' => 'UpdatedName']);

    $this->assertDatabaseHas('course_reservations', [
        'reservation_id' => $reservation->reservation_id,
        'status' => 'confirmed',
        'first_name' => 'UpdatedName',
    ]);
});

// ========== DESTROY ==========
it('can delete a course reservation', function () {
    $reservation = CourseReservation::factory()->create();

    $response = $this->withToken($this->token)->deleteJson('/api/course-reservations', [
        'reservation_id' => $reservation->reservation_id,
    ]);

    $response->assertOk()
             ->assertJson(['message' => 'Réservation de stage supprimée avec succès']);

    $this->assertDatabaseMissing('course_reservations', ['reservation_id' => $reservation->reservation_id]);
});
