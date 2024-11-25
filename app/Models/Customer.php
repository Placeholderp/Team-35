<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'CustomerID';
    protected $fillable = ['name', 'PasswordHash', 'Email'];
    public $timestamps = false;

    public function cart()
    {
        return $this->hasOne(Cart::class, 'CustomerID');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'CustomerID');
    }
}