<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class CarExpense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'car_id',
        'expense_type',
        'title',
        'description',
        'amount',
        'expense_date',
        'due_date',
        'expiry_date',
        'reference_number',
        'provider',
        'payment_status',
        'documents',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expense_date' => 'date',
        'due_date' => 'date',
        'expiry_date' => 'date',
        'amount' => 'decimal:2',
        'documents' => 'array',
    ];

    /**
     * Expense type constants
     */
    public const TYPE_INSURANCE = 'insurance';
    public const TYPE_TOLL_TAX = 'toll_tax';
    public const TYPE_REGISTRATION = 'registration';
    public const TYPE_FINE = 'fine';
    public const TYPE_OTHER = 'other';

    /**
     * Payment status constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';

    /**
     * Get the validation rules for expense creation/update
     *
     * @return array<string, mixed>
     */
    public static function validationRules(): array
    {
        return [
            'car_id' => 'required|exists:cars,id',
            'expense_type' => 'required|in:insurance,toll_tax,registration,fine,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:expense_date',
            'expiry_date' => 'nullable|date|after:expense_date',
            'reference_number' => 'nullable|string|max:255',
            'provider' => 'nullable|string|max:255',
            'payment_status' => 'required|in:pending,paid,overdue',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get the car associated with the expense.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Scope a query to only include expenses of a specific type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('expense_type', $type);
    }

    /**
     * Scope a query to only include pending expenses.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('payment_status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include paid expenses.
     */
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('payment_status', self::STATUS_PAID);
    }

    /**
     * Scope a query to only include overdue expenses.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('payment_status', self::STATUS_OVERDUE);
    }

    /**
     * Scope a query to only include expenses due within a specific date range.
     */
    public function scopeDueBetween(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('due_date', [$start, $end]);
    }

    /**
     * Scope a query to only include expenses expiring within a specific date range.
     */
    public function scopeExpiringBetween(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('expiry_date', [$start, $end]);
    }

    /**
     * Check if the expense is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->payment_status !== self::STATUS_PAID;
    }

    /**
     * Check if the expense is expiring soon (within 30 days).
     */
    public function isExpiringSoon(): bool
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(Carbon::now()) <= 30;
    }

    /**
     * Get the formatted amount with currency symbol.
     */
    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Get the expense type label.
     */
    public function getTypeLabel(): string
    {
        return match($this->expense_type) {
            self::TYPE_INSURANCE => 'Insurance',
            self::TYPE_TOLL_TAX => 'Toll Tax',
            self::TYPE_REGISTRATION => 'Registration',
            self::TYPE_FINE => 'Fine',
            default => 'Other',
        };
    }

    /**
     * Get the payment status label.
     */
    public function getStatusLabel(): string
    {
        return match($this->payment_status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PAID => 'Paid',
            self::STATUS_OVERDUE => 'Overdue',
            default => 'Unknown',
        };
    }
} 