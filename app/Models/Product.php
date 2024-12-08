<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
/********************************
Developer: Oliver Blatchford
University ID: 230163795
Function: This makes the Product table variables accessible
********************************/
    protected $table = 'product';
    protected $primaryKey = 'ProductID';
    protected $fillable = ['ProductName', 'Description', 'Stock', 'Category'];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'ProductID');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'ProductID');
    }
}
