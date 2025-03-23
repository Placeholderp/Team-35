<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;
    
    // Define primary key
    protected $primaryKey = 'user_id';
    
    // Ensure Laravel knows the primary key is not auto-incrementing
    public $incrementing = false;
    
    // Define the attributes that are mass assignable.
    protected $fillable = ['first_name', 'last_name', 'email', 'status', 'user_id'];

    /**
     * Define a relationship to the User model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get all addresses for this customer.
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class, 'user_id', 'user_id');
    }

    /**
     * Get the customer's shipping address.
     */
    public function shippingAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class, 'user_id', 'user_id')
            ->where('type', '=', AddressType::Shipping->value);
    }

    /**
     * Get the customer's billing address.
     */
    public function billingAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class, 'user_id', 'user_id')
            ->where('type', '=', AddressType::Billing->value);
    }
}