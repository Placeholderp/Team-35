<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cart extends Model
{
/********************************
Developer: Oliver Blatchford
University ID: 230163795
Function: This makes the cart table variables accessible
********************************/
    protected $table = 'cart';
    protected $primaryKey = 'CartID';
    protected $fillable = ['CustomerID'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'CartID');
    }
}

