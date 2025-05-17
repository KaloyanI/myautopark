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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('license_plate')->unique();
            $table->string('color');
            $table->decimal('mileage', 10, 2);
            $table->enum('status', ['available', 'booked', 'maintenance'])->default('available');
            $table->decimal('daily_rate', 10, 2);
            $table->text('description')->nullable();
            $table->json('specifications')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
