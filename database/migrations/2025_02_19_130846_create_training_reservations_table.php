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
        Schema::create('training_reservations', function (Blueprint $table) {
            $table->id('reservation_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email',50);
            $table->string('phone', 10)->nullable();
            $table->string('application_file', 255);
            $table->date('interview_date');
            $table->string('status', 50)->default('pending');
            $table->string('pay', 50);
            $table->foreignId('training_id')->references('training_id')->on('trainings');
            $table->foreignId('user_id')->references('user_id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_reservations');
    }
};
