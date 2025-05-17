<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'total_cost',
        'status',
        'payment_status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Status constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Payment status constants
     */
    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_REFUNDED = 'refunded';

    /**
     * Get the validation rules for booking creation/update
     *
     * @return array<string, mixed>
     */
    public static function validationRules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'total_cost' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,refunded',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get the car associated with the booking.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Get the user associated with the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include pending bookings.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include confirmed bookings.
     */
    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    /**
     * Scope a query to only include completed bookings.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope a query to only include active bookings (pending or confirmed).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    /**
     * Calculate the duration of the booking in days.
     *
     * @return int
     */
    public function getDurationInDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1; // Include the start day
    }

    /**
     * Calculate the total cost based on the car's daily rate and booking duration.
     * 
     * @return float
     */
    public function calculateTotalCost(): float
    {
        return $this->car->daily_rate * $this->duration_in_days;
    }

    /**
     * Check if the booking can be cancelled.
     *
     * @return bool
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]) 
            && $this->start_date->isFuture();
    }

    /**
     * Check if the booking is currently active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        $now = Carbon::now();
        return $this->status === self::STATUS_CONFIRMED 
            && $now->between($this->start_date, $this->end_date);
    }
}
