<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
/********************************
Developer: Oliver Blatchford
University ID: 230163795
Function: This makes the order table variables accessible
********************************/
    protected $table = 'orders';
    protected $primaryKey = 'OrderID';
    protected $fillable = ['UserID', 'TotalAmount', 'OrderDate'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'UserID');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'OrderID');
    }
}
