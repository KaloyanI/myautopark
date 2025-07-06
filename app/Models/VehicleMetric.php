<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMetric extends Model
{
    protected $fillable = [
        'car_id',
        'odometer_reading',
        'fuel_amount',
        'fuel_cost',
        'fuel_efficiency',
        'notes',
        'reading_date',
    ];

    protected $casts = [
        'reading_date' => 'datetime',
        'odometer_reading' => 'decimal:2',
        'fuel_amount' => 'decimal:2',
        'fuel_cost' => 'decimal:2',
        'fuel_efficiency' => 'decimal:2',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    // Calculate fuel efficiency (km/L)
    public function calculateFuelEfficiency(?float $previousOdometerReading): void
    {
        if ($previousOdometerReading) {
            $distance = $this->odometer_reading - $previousOdometerReading;
            $this->fuel_efficiency = $distance / $this->fuel_amount;
            $this->save();
        }
    }
}
