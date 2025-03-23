<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    use HasFactory;
    
    // Define the correct primary key - it should be 'id' not 'user_id'
    protected $primaryKey = 'id';
    public $incrementing = true; // This should be true if id is auto-incrementing
    
    // Define the attributes that are mass assignable.
    protected $fillable = [
        'type',          
        'address1',      
        'address2',      
        'city',          
        'state',         
        'zipcode',       
        'country_code',  
        'user_id',   // Changed from customer_id to user_id
    ];

    /**
     * Define a relationship indicating that a CustomerAddress belongs to a Customer.
     * Explicitly specify the foreign key in this table and the owner key in the Customer table.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id', 'user_id');
    }

    /**
     * Here, the local key 'country_code' on this model corresponds to the 'code' column on the Country model.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
}