<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Customer extends Model
{
    use HasFactory;
    
    // Define primary key
    protected $primaryKey = 'user_id';
    
    // Ensure Laravel knows the primary key is not auto-incrementing
    public $incrementing = false;
    
    // Define the attributes that are mass assignable.
    protected $fillable = ['first_name', 'last_name', 'email', 'status', 'user_id'];
    
    // Cast status to integer to ensure database compatibility
    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * Boot method to add model event hooks
     */
    protected static function boot()
    {
        parent::boot();
        
        // Handle status field for new records
        static::creating(function ($customer) {
            // Explicitly set status to 1 (active) by default if not set
            if ($customer->status === null || !isset($customer->status)) {
                $customer->status = 1;
            } 
            // Convert boolean status to integer
            else if (is_bool($customer->status)) {
                $customer->status = $customer->status ? 1 : 0;
            }
            // Convert string status to integer
            else if (is_string($customer->status)) {
                // Handle different string formats
                if ($customer->status === 'true' || $customer->status === '1' || $customer->status === 'active') {
                    $customer->status = 1;
                } else {
                    $customer->status = 0;
                }
            }
        });
    }

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