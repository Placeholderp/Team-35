<?php
// File: app/Models/OrderDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_details';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
      
        'first_name',
        'last_name',
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country_code',
    ];
    
    /**
     * Get the order that owns the order detail.
     * Updated to use standard Laravel relationship conventions.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    
    /**
     * Get the user that owns the order detail.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the country that the order is shipping to.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
    
    /**
     * Get the formatted full address
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address1;
        
        if (!empty($this->address2)) {
            $address .= ', ' . $this->address2;
        }
        
        $address .= ', ' . $this->city;
        
        if (!empty($this->state)) {
            $address .= ', ' . $this->state;
        }
        
        $address .= ' ' . $this->zipcode;
        
        if ($this->country) {
            $address .= ', ' . $this->country->name;
        }
        
        return $address;
    }
}