<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
/********************************
Developer: Oliver Blatchford
University ID: 230163795
Function: This makes the cartItem table variables accessible
********************************/
    protected $table = 'cartitem';
    protected $primaryKey = 'CartItemID';
    protected $fillable = ['CartID', 'ProductID', 'Quantity'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'CartID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }
}

