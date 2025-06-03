<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Car;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First add the column as nullable
        Schema::table('cars', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable()->after('id');
        });

        // Get the first admin user or create one if none exists
        $owner = User::first();
        
        if ($owner) {
            // Update all existing cars to have this owner
            Car::whereNull('owner_id')->update(['owner_id' => $owner->id]);
        }

        // Now add the foreign key constraint and make the column required
        Schema::table('cars', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable(false)->change();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });
    }
}; 