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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->enum('maintenance_type', ['routine', 'repair', 'inspection']);
            $table->text('description');
            $table->date('service_date');
            $table->decimal('mileage_at_service', 10, 2);
            $table->decimal('cost', 10, 2);
            $table->string('performed_by');
            $table->enum('status', ['scheduled', 'in_progress', 'completed'])->default('scheduled');
            $table->date('next_service_date')->nullable();
            $table->decimal('next_service_mileage', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
