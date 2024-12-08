<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
/********************************
Developer: Oliver Blatchford
University ID: 230163795
Function: This makes the customer table variables accessible
********************************/
    protected $table = 'customer';
    protected $primaryKey = 'CustomerID';
    protected $fillable = ['name', 'PasswordHash', 'Email'];

    public function cart()
    {
        return $this->hasOne(Cart::class, 'CustomerID');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'CustomerID');
    }
}