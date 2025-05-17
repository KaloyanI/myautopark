<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Maintenance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'car_id',
        'maintenance_type',
        'description',
        'service_date',
        'mileage_at_service',
        'cost',
        'performed_by',
        'status',
        'next_service_date',
        'next_service_mileage',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'service_date' => 'date',
        'next_service_date' => 'date',
        'mileage_at_service' => 'decimal:2',
        'next_service_mileage' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    /**
     * Maintenance type constants
     */
    public const TYPE_ROUTINE = 'routine';
    public const TYPE_REPAIR = 'repair';
    public const TYPE_INSPECTION = 'inspection';

    /**
     * Status constants
     */
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    /**
     * Get the validation rules for maintenance creation/update
     *
     * @return array<string, mixed>
     */
    public static function validationRules(): array
    {
        return [
            'car_id' => 'required|exists:cars,id',
            'maintenance_type' => 'required|in:routine,repair,inspection',
            'description' => 'required|string',
            'service_date' => 'required|date',
            'mileage_at_service' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'performed_by' => 'required|string|max:255',
            'status' => 'required|in:scheduled,in_progress,completed',
            'next_service_date' => 'nullable|date|after:service_date',
            'next_service_mileage' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get the car associated with the maintenance record.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Scope a query to only include scheduled maintenance.
     */
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    /**
     * Scope a query to only include in-progress maintenance.
     */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope a query to only include completed maintenance.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope a query to filter by maintenance type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('maintenance_type', $type);
    }

    /**
     * Scope a query to find maintenance records due based on date.
     */
    public function scopeDueByDate(Builder $query, ?Carbon $date = null): Builder
    {
        $date = $date ?: Carbon::now();
        return $query->whereNotNull('next_service_date')
                     ->where('next_service_date', '<=', $date);
    }

    /**
     * Scope a query to find maintenance records due based on mileage.
     */
    public function scopeDueByMileage(Builder $query, float $currentMileage): Builder
    {
        return $query->whereNotNull('next_service_mileage')
                     ->where('next_service_mileage', '<=', $currentMileage);
    }

    /**
     * Check if service is due based on date.
     *
     * @return bool
     */
    public function isServiceDueByDate(): bool
    {
        if (!$this->next_service_date) {
            return false;
        }

        return $this->next_service_date->lte(Carbon::now());
    }

    /**
     * Check if service is due based on mileage.
     *
     * @param float $currentMileage
     * @return bool
     */
    public function isServiceDueByMileage(float $currentMileage): bool
    {
        if (!$this->next_service_mileage) {
            return false;
        }

        return $this->next_service_mileage <= $currentMileage;
    }

    /**
     * Calculate days until next service.
     *
     * @return int|null
     */
    public function getDaysUntilNextServiceAttribute(): ?int
    {
        if (!$this->next_service_date) {
            return null;
        }

        return Carbon::now()->diffInDays($this->next_service_date, false);
    }

    /**
     * Calculate miles until next service.
     *
     * @param float $currentMileage
     * @return float|null
     */
    public function getMilesUntilNextService(float $currentMileage): ?float
    {
        if (!$this->next_service_mileage) {
            return null;
        }

        return $this->next_service_mileage - $currentMileage;
    }
}
