<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_contents', function (Blueprint $table) {
            $table->id('content_id');
            $table->string('name', 50);
            $table->text('description');
            $table->string('files', 255);
            $table->foreignId('training_id')->references('training_id')->on('trainings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_contents');
    }
};
