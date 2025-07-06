<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->decimal('odometer_reading', 10, 2)->comment('Current odometer reading in kilometers');
            $table->decimal('fuel_amount', 8, 2)->comment('Amount of fuel added in liters');
            $table->decimal('fuel_cost', 10, 2)->comment('Cost of fuel');
            $table->decimal('fuel_efficiency', 8, 2)->nullable()->comment('Calculated km/L');
            $table->text('notes')->nullable();
            $table->timestamp('reading_date');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('car_id');
            $table->index('reading_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_metrics');
    }
};
