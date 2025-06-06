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
        Schema::table('cars', function (Blueprint $table) {
            $table->string('vin')->nullable()->after('license_plate');
            $table->string('fuel_type')->nullable()->after('mileage');
            $table->string('transmission')->nullable()->after('fuel_type');
            $table->integer('seats')->default(5)->after('transmission');
            // Add photo column if it doesn't exist
            if (!Schema::hasColumn('cars', 'photo')) {
                $table->string('photo')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['vin', 'fuel_type', 'transmission', 'seats']);
            if (Schema::hasColumn('cars', 'photo')) {
                $table->dropColumn('photo');
            }
        });
    }
}; 