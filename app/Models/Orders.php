<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Str;

class Orders extends Model
{protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'delivery_information'
    ];
    
    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_information' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Relationship with User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Relationship with OrderItems
    public function items(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
    
    // Status badge color
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'On Delivery' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'Completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'Cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        };
    }
    
    // Status icon
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'Pending' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'On Delivery' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'Completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'Cancelled' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        };
    }
    
    // Generate order number (static method)
    public static function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(10));
    }
    
    // Accessor for formatted total amount
    public function getFormattedTotalAttribute(): string
    {
        return '₹' . number_format($this->total_amount, 2);
    }
    
    // Accessor for item count
    public function getItemCountAttribute(): int
    {
        return $this->items()->count();
    }
    
    // Scope for active orders (not completed or cancelled)
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Pending', 'On Delivery']);
    }
}