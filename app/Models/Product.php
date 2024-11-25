<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'ProductID';
    protected $fillable = ['ProductName', 'Description', 'Stock', 'Category'];
    public $timestamps = false;

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'ProductID');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'ProductID');
    }
}
