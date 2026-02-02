<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItems extends Model
{
    protected $table = "order_item";
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity'
    ];
    
    // Relationship with Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Orders::class);
    }
    
    // Relationship with Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class);
    }
    
    // Calculate subtotal
    public function getSubtotalAttribute()
    {
        if ($this->product) {
            return $this->product->price * $this->quantity;
        }
        return 0;
    }
    
    // Formatted subtotal
    public function getFormattedSubtotalAttribute(): string
    {
        return '₹' . number_format($this->subtotal, 2);
    }
}