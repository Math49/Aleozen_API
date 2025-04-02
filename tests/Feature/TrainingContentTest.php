<?php

use App\Models\User;
use App\Models\Training;
use App\Models\TrainingContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local'); // simulate file storage
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
});

// ========== INDEX ==========
it('can list all training contents', function () {
    TrainingContent::factory()->count(3)->create();

    $response = $this->withToken($this->token)->getJson('/api/training-contents');

    $response->assertOk()
             ->assertJsonStructure([
                 '*' => ['content_id', 'name', 'description', 'files', 'training_id']
             ]);
});

// ========== SHOW ==========
it('can get a specific training content by id', function () {
    $content = TrainingContent::factory()->create();

    $response = $this->withToken($this->token)->getJson("/api/training-contents/{$content->content_id}");

    $response->assertOk()
             ->assertJsonFragment(['name' => $content->name]);
});

// ========== STORE ==========
it('can create a training content with a file', function () {
    $training = Training::factory()->create();
    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $response = $this->withToken($this->token)->postJson('/api/training-contents', [
        'name' => 'Introduction Laravel',
        'description' => 'Contenu de base',
        'files' => $file,
        'training_id' => $training->training_id,
    ]);

    $response->assertCreated()
             ->assertJsonFragment(['name' => 'Introduction Laravel']);

    Storage::assertExists('training_contents/' . $file->hashName());
});

// ========== UPDATE ==========
it('can update a training content', function () {
    $training = Training::factory()->create();
    $content = TrainingContent::factory()->create();
    $file = UploadedFile::fake()->create('update.docx', 200, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

    $response = $this->withToken($this->token)->putJson("/api/training-contents/{$content->content_id}", [
        'name' => 'Updated Name',
        'description' => 'Mise à jour',
        'files' => $file,
    ]);

    $response->assertOk()
             ->assertJsonFragment(['name' => 'Updated Name']);

    Storage::assertExists('training_contents/' . $file->hashName());
});

// ========== DESTROY ==========
it('can delete a training content', function () {
    $content = TrainingContent::factory()->create();

    $response = $this->withToken($this->token)->deleteJson('/api/training-contents', [
        'content_id' => $content->content_id,
    ]);

    $response->assertOk()
             ->assertJson(['message' => 'Training content deleted successfully']);

    $this->assertDatabaseMissing('training_contents', ['content_id' => $content->content_id]);
});
