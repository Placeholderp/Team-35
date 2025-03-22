<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_id';

    // Define the attributes that are mass assignable.
   // In app/Models/Customer.php
protected $fillable = ['first_name', 'last_name', 'email', 'status'];

// Fix the primary key to match your database
 // This should match what's in your database

    /**
     * Define a relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Private helper function to get the associated CustomerAddress.
     */
    private function _getAddresses(): HasOne
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'user_id');
    }

    /**
     * Get the customer's shipping address.
     */
    public function shippingAddress(): HasOne
    {
        return $this->_getAddresses()->where('type', '=', AddressType::Shipping->value);
    }

    /**
     * Get the customer's billing address.
     */
    public function billingAddress(): HasOne
    {
        return $this->_getAddresses()->where('type', '=', AddressType::Billing->value);
    }
}
