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
        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pickup_city_id');
            $table->unsignedBigInteger('dropoff_city_id');
            $table->string('pickup_address')->nullable();
            $table->string('dropoff_address')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('arrival_time')->nullable();
            $table->decimal('distance', 10, 2)->nullable();
            $table->integer('available_seats')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('journey_date')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journeys');
    }
};
