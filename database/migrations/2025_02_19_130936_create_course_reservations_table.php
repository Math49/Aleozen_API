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
        Schema::create('course_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name',50);
            $table->string('email',100);
            $table->string('phone',10);
            $table->string('status',50);
            $table->string('pay',50);
            $table->timestamps();
            $table->foreignId('course_id')->constrained('courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_reservations');
    }
};
