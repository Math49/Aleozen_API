<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ========== REGISTER ==========
it('registers a new user successfully (auth required)', function () {
    $admin = User::factory()->create(); // ou un user ayant le droit de créer un compte
    $token = $admin->createToken('auth-token')->plainTextToken;

    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '0600000000',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->withToken($token)->postJson('/api/register', $data);

    $response->assertCreated()
             ->assertJsonStructure([
                 'message',
                 'user' => ['first_name', 'last_name', 'email', 'phone'],
                 'token'
             ]);
});


it('fails to register with invalid data', function () {
    $admin = User::factory()->create();
    $token = $admin->createToken('auth-token')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/register', []);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'phone', 'password']);
});

// ========== LOGIN ==========
it('logs in an existing user successfully', function () {
    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'jane@example.com',
        'password' => 'password123',
    ]);

    $response->assertOk()
             ->assertJsonStructure([
                 'message',
                 'user' => ['first_name', 'last_name', 'email', 'phone', 'role'],
                 'token'
             ]);
});

it('fails to login with wrong credentials', function () {
    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'jane@example.com',
        'password' => 'wrongpass',
    ]);

    $response->assertStatus(422)
                ->assertJsonValidationErrors(['error']);
});

// ========== LOGOUT ==========
it('logs out an authenticated user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth-token')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/logout');

    $response->assertOk()
             ->assertJson(['message' => 'Déconnexion réussie']);
});

