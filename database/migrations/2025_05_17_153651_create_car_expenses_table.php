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
        Schema::create('car_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->enum('expense_type', ['insurance', 'toll_tax', 'registration', 'fine', 'other']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->date('due_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('provider')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->json('documents')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_expenses');
    }
}; 