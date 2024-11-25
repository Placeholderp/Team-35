<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'CartID';
    protected $fillable = ['CustomerID'];
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'CartID');
    }
}

