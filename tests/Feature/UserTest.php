<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Crée un utilisateur authentifié pour tous les tests
beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
});

// ========== INDEX ==========
it('can list all users', function () {
    User::factory()->count(3)->create();

    $response = $this->withToken($this->token)->getJson('/api/users');

    $response->assertOk()
             ->assertJsonStructure([
                 '*' => ['user_id', 'first_name', 'last_name', 'email', 'phone', 'role'],
             ]);
});

// ========== SHOW ==========
it('can get a specific user by id', function () {
    $target = User::factory()->create();

    $response = $this->withToken($this->token)->getJson("/api/users/{$target->user_id}");

    $response->assertOk()
             ->assertJsonFragment(['email' => $target->email]);
});

// ========== STORE ==========
it('can create a user', function () {
    $data = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'testuser@example.com',
        'phone' => '0612345678',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'user',
    ];

    $response = $this->withToken($this->token)->postJson('/api/users', $data);

    $response->assertCreated()
             ->assertJsonFragment(['email' => 'testuser@example.com']);

    $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
});

// ========== UPDATE ==========
it('can update a user', function () {
    $target = User::factory()->create();

    $data = [
        'first_name' => 'Updated',
        'last_name' => 'Name',
        'email' => 'updated@example.com',
        'phone' => '0699999999',
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
        'role' => 'admin',
    ];

    $response = $this->withToken($this->token)->putJson("/api/users/{$target->user_id}", $data);

    $response->assertOk()
             ->assertJsonFragment(['email' => 'updated@example.com']);

    $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
});

// ========== DESTROY ==========
it('can delete a user', function () {
    $target = User::factory()->create();

    $response = $this->withToken($this->token)->deleteJson('/api/users', [
        'user_id' => $target->user_id,
    ]);

    $response->assertOk()
             ->assertJson(['message' => 'User deleted successfully']);

    $this->assertDatabaseMissing('users', ['user_id' => $target->user_id]);
});
