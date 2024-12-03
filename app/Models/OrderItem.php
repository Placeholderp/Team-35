<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
/********************************
Developer: Oliver Blatchford
University ID: 230163795
Function: This makes the OrderItem table variables accessible
********************************/
    protected $table = 'orderitem';
    protected $primaryKey = 'OrderItemID';
    protected $fillable = ['OrderID', 'ProductID', 'Quantity', 'Price'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }
}

