<?php
use App\Models\User;
use App\Models\Training;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
});

// ========== INDEX ==========
it('can list all trainings', function () {
    Training::factory()->count(3)->create();

    $response = $this->withToken($this->token)->getJson('/api/trainings');

    $response->assertOk()
             ->assertJsonStructure([
                 '*' => ['training_id', 'title', 'description', 'location', 'start_date', 'type', 'price', 'status']
             ]);
});

// ========== SHOW ==========
it('can get a specific training by id', function () {
    $training = Training::factory()->create();

    $response = $this->withToken($this->token)->getJson("/api/trainings/{$training->training_id}");

    $response->assertOk()
             ->assertJsonFragment(['title' => $training->title]);
});

// ========== STORE ==========
it('can create a training', function () {
    $data = [
        'title' => 'React Avancé',
        'description' => 'Formation pour devs frontend',
        'location' => 'Paris',
        'start_date' => now()->addDays(10)->toDateString(),
        'type' => 'upcoming',
        'price' => 499.99,
        'status' => 'pending',
    ];

    $response = $this->withToken($this->token)->postJson('/api/trainings', $data);

    $response->assertCreated()
             ->assertJsonFragment(['title' => 'React Avancé']);

    $this->assertDatabaseHas('trainings', ['title' => 'React Avancé']);
});

// ========== UPDATE ==========
it('can update a training', function () {
    $training = Training::factory()->create();

    $data = [
        'title' => 'Updated Title',
        'description' => 'Nouvelle description',
        'location' => 'Lyon',
        'start_date' => now()->addDays(5)->toDateString(),
        'type' => 'upcoming',
        'price' => 799,
        'status' => 'completed',
    ];

    $response = $this->withToken($this->token)->putJson("/api/trainings/{$training->training_id}", $data);

    $response->assertOk()
             ->assertJsonFragment(['title' => 'Updated Title']);

    $this->assertDatabaseHas('trainings', ['title' => 'Updated Title']);
});

// ========== DESTROY ==========
it('can delete a training', function () {
    $training = Training::factory()->create();

    $response = $this->withToken($this->token)->deleteJson('/api/trainings', [
        'training_id' => $training->training_id
    ]);

    $response->assertOk()
             ->assertJson(['message' => 'Formation supprimée avec succès']);

    $this->assertDatabaseMissing('trainings', ['training_id' => $training->training_id]);
});
