<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    // Enable factory support for Payment model.
    use HasFactory;

    // Define the attributes that are mass assignable.
    protected $fillable = [
        'order_id',   
        'status',     
        'amount',     
        'type',       
        'session_id', 
        'created_by', 
        'updated_by'  
    ];

    /**
     * This method retrieves the associated order for this payment.
     */
    public function order(): HasOne
    {
        // 'id' is the primary key of the Order model and 'order_id' is the foreign key in the Payment model.
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
