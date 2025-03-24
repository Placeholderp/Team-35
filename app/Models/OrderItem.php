<?php
// File: app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    // Changed to standard primary key
    protected $primaryKey = 'id';
    
    // Define the attributes that are mass assignable.
    protected $fillable = ['order_id', 'unit_price', 'product_id', 'quantity'];

    /**
     * An OrderItem belongs to an Order.
     * Using standard Laravel relationship conventions.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * An OrderItem belongs to a Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}