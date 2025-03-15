<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
   
    use HasFactory;

    // Define the attributes that are mass assignable.
    protected $fillable = [
        'type',          
        'address1',      
        'address2',      
        'city',          
        'state',         
        'zipcode',       
        'country_code',  
        'customer_id',   
    ];

    /**
     * Define a relationship indicating that a CustomerAddress belongs to a Customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Here, the local key 'country_code' on this model corresponds to the 'code' column on the Country model.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
}
