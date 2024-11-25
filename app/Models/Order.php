<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'OrderID';
    protected $fillable = ['CustomerID', 'TotalAmount', 'OrderDate'];
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'OrderID');
    }
}
