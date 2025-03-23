<?php
// File: app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    // Define the primary key based on your database structure
    protected $primaryKey = 'user_id';
    
    // Define the attributes that are mass assignable.
    protected $fillable = ['order_id', 'unit_price', 'product_id', 'quantity'];

    /**
     * An OrderItem belongs to an Order.
     * The order uses user_id as its primary key.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'user_id');
    }

    /**
     * An OrderItem belongs to a Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}