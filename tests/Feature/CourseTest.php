<?php

use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
});

// ========== INDEX ==========
it('can list all courses', function () {
    Course::factory()->count(3)->create();

    $response = $this->withToken($this->token)->getJson('/api/courses');

    $response->assertOk()
             ->assertJsonCount(3);
});

// ========== SHOW ==========
it('can get a specific course by id', function () {
    $course = Course::factory()->create();

    $response = $this->withToken($this->token)->getJson("/api/courses/{$course->course_id}");

    $response->assertOk()
             ->assertJsonFragment(['title' => $course->title]);
});

// ========== STORE ==========
it('can create a course', function () {
    $data = [
        'title' => 'Laravel Masterclass',
        'description' => 'Un cours complet sur Laravel.',
        'location' => 'Paris',
        'type' => 'online',
        'start_date' => now()->addWeek()->toDateString(),
        'status' => 'active',
    ];

    $response = $this->withToken($this->token)->postJson('/api/courses', $data);

    $response->assertCreated()
             ->assertJsonFragment(['title' => 'Laravel Masterclass']);

    $this->assertDatabaseHas('courses', ['title' => 'Laravel Masterclass']);
});

// ========== UPDATE ==========
it('can update a course', function () {
    $course = Course::factory()->create();

    $data = [
        'title' => 'Mise à jour de titre',
        'status' => 'inactive',
    ];

    $response = $this->withToken($this->token)->putJson("/api/courses/{$course->course_id}", $data);

    $response->assertOk()
             ->assertJsonFragment(['title' => 'Mise à jour de titre']);

    $this->assertDatabaseHas('courses', ['course_id' => $course->course_id, 'title' => 'Mise à jour de titre']);
});

// ========== DESTROY ==========
it('can delete a course', function () {
    $course = Course::factory()->create();

    $response = $this->withToken($this->token)->deleteJson('/api/courses', [
        'course_id' => $course->course_id,
    ]);

    $response->assertOk()
             ->assertJson(['message' => 'Course deleted successfully']);

    $this->assertDatabaseMissing('courses', ['course_id' => $course->course_id]);
});
