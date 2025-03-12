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
        Schema::create('intervention_reservations', function (Blueprint $table) {
            $table->id("reservation_id");
            $table->string("first_name", 50);
            $table->string("last_name",50);
            $table->string("email",100);
            $table->string("phone",10);
            $table->date("intervention_date");
            $table->string("type",50);
            $table->text("description");
            $table->string("status",50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervention_reservations');
    }
};
