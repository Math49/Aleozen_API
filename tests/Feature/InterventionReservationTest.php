<?php

use App\Models\User;
use App\Models\InterventionReservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
});

// ========== INDEX ==========
it('can list all intervention reservations', function () {
    InterventionReservation::factory()->count(3)->create();

    $response = $this->withToken($this->token)->getJson('/api/intervention-reservations');

    $response->assertOk()
             ->assertJsonCount(3);
});

// ========== SHOW ==========
it('can get a specific intervention reservation by id', function () {
    $reservation = InterventionReservation::factory()->create();

    $response = $this->withToken($this->token)->getJson("/api/intervention-reservations/{$reservation->reservation_id}");

    $response->assertOk()
             ->assertJsonFragment(['email' => $reservation->email]);
});

// ========== STORE ==========
it('can create an intervention reservation', function () {
    $data = [
        'first_name' => 'Claire',
        'last_name' => 'Dupont',
        'email' => 'claire@example.com',
        'phone' => '0601234567',
        'intervention_date' => now()->addDays(10)->toDateString(),
        'type' => 'type1',
        'description' => 'Une intervention urgente',
        'status' => 'pending',
    ];

    $response = $this->withToken($this->token)->postJson('/api/intervention-reservations', $data);

    $response->assertCreated()
             ->assertJsonFragment(['email' => 'claire@example.com']);

    $this->assertDatabaseHas('intervention_reservations', ['email' => 'claire@example.com']);
});

// ========== UPDATE ==========
it('can update an intervention reservation', function () {
    $reservation = InterventionReservation::factory()->create();

    $data = [
        'first_name' => 'NouveauNom',
        'last_name' => $reservation->last_name,
        'email' => $reservation->email,
        'phone' => $reservation->phone,
        'intervention_date' => now()->addDays(7)->toDateString(),
        'type' => 'type2',
        'description' => 'Mise à jour de description',
        'status' => 'confirmed',
    ];

    $response = $this->withToken($this->token)->putJson("/api/intervention-reservations/{$reservation->reservation_id}", $data);

    $response->assertOk()
             ->assertJsonFragment(['first_name' => 'NouveauNom', 'status' => 'confirmed']);

    $this->assertDatabaseHas('intervention_reservations', [
        'reservation_id' => $reservation->reservation_id,
        'first_name' => 'NouveauNom',
    ]);
});

// ========== DESTROY ==========
it('can delete an intervention reservation', function () {
    $reservation = InterventionReservation::factory()->create();

    $response = $this->withToken($this->token)->deleteJson('/api/intervention-reservations', [
        'reservation_id' => $reservation->reservation_id,
    ]);

    $response->assertOk()
             ->assertJson(['message' => 'Réservation d\'intervention supprimée avec succès']);

    $this->assertDatabaseMissing('intervention_reservations', ['reservation_id' => $reservation->reservation_id]);
});
