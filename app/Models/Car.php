<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'brand',
        'model',
        'year',
        'license_plate',
        'vin',
        'color',
        'mileage',
        'fuel_type',
        'transmission',
        'seats',
        'status',
        'daily_rate',
        'description',
        'specifications',
        'photo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'specifications' => 'array',
        'mileage' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'year' => 'integer',
        'seats' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url',
    ];

    /**
     * Status constants
     */
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_BOOKED = 'booked';
    public const STATUS_MAINTENANCE = 'maintenance';

    /**
     * Fuel type constants
     */
    public const FUEL_TYPE_GASOLINE = 'gasoline';
    public const FUEL_TYPE_DIESEL = 'diesel';
    public const FUEL_TYPE_ELECTRIC = 'electric';
    public const FUEL_TYPE_HYBRID = 'hybrid';

    /**
     * Transmission constants
     */
    public const TRANSMISSION_AUTOMATIC = 'automatic';
    public const TRANSMISSION_MANUAL = 'manual';
    public const TRANSMISSION_SEMI_AUTO = 'semi-automatic';

    /**
     * Get the validation rules for car creation/update
     *
     * @return array<string, mixed>
     */
    public static function validationRules(): array
    {
        return [
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:20|unique:cars,license_plate',
            'vin' => 'required|string|max:17',
            'color' => 'required|string|max:50',
            'mileage' => 'required|numeric|min:0',
            'fuel_type' => 'required|string|in:gasoline,diesel,electric,hybrid',
            'transmission' => 'required|string|in:automatic,manual,semi-automatic',
            'seats' => 'required|integer|min:1|max:20',
            'status' => 'required|in:available,booked,maintenance',
            'daily_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'specifications' => 'nullable|json',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get the bookings for the car.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the maintenance records for the car.
     */
    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Get the expenses for the car.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(CarExpense::class);
    }

    /**
     * Get the total expenses amount for a specific type.
     */
    public function getTotalExpensesByType(string $type): float
    {
        return $this->expenses()
            ->where('expense_type', $type)
            ->where('payment_status', CarExpense::STATUS_PAID)
            ->sum('amount');
    }

    /**
     * Get all pending expenses.
     */
    public function getPendingExpenses()
    {
        return $this->expenses()
            ->where('payment_status', CarExpense::STATUS_PENDING)
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get all expenses expiring soon (within 30 days).
     */
    public function getExpiringSoonExpenses()
    {
        return $this->expenses()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(30))
            ->orderBy('expiry_date')
            ->get();
    }

    /**
     * Check if the car is available for booking
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Get the full name of the car (brand and model)
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    /**
     * Get the URL for the car's photo.
     *
     * @return string|null
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return null;
        }
        return asset('storage/' . $this->photo);
    }

    /**
     * Get the owner of the car.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
