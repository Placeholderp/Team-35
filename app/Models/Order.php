<?php
// File: app/Models/Order.php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
class Order extends Model
{
    use HasFactory;
    
    // Define user_id as the primary key to match your database structure
    protected $primaryKey = 'user_id';
    
    // Define attributes that are mass assignable
    protected $fillable = ['status', 'total_price', 'created_by', 'updated_by'];

    // Handle date formatting
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Check if the order is paid.
     */
    public function isPaid()
    {
        return $this->status === OrderStatus::Paid->value;
    }

    /**
     * This returns the associated payment for the order.
     */
    public function payment(): HasOne
    {
        // Specify that the foreign key in payments table is order_id and references user_id
        return $this->hasOne(Payment::class, 'order_id', 'user_id');
    }

    /**
     * Get the user who created this order.
     * The foreign key is 'created_by' in the orders table.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * This retrieves all order items associated with this order.
     */
    public function items(): HasMany
    {
        // Specify that the foreign key in order_items is order_id and references user_id
        return $this->hasMany(OrderItem::class, 'order_id', 'user_id');
    }
    
    /**
     * Get the order detail record associated with this order.
     */
    public function detail(): HasOne
    {
        // Specify that the foreign key in order_details is order_id and references user_id
        return $this->hasOne(OrderDetail::class, 'order_id', 'user_id')->withDefault([
            'first_name' => '',
            'last_name' => '',
            'address1' => '',
            'city' => '',
            'zipcode' => '',
            'country_code' => ''
        ]);
    }

    /**
     * Get the customer for this order through the user relationship
     */
    public function customer()
    {
        return $this->user ? $this->user->customer() : null;
    }
    
    /**
     * Boot method to add global error handling
     */
    protected static function boot()
    {
        parent::boot();
        
        static::retrieved(function ($model) {
            // Ensure ID property exists for frontend compatibility
            $model->id = $model->user_id;
            
            // Add debug logging to check user relationship
            if ($model->user) {
                Log::info('Order user loaded:', [
                    'order_id' => $model->user_id, 
                    'user_id' => $model->user->id,
                    'has_customer' => $model->user->customer ? 'yes' : 'no'
                ]);
            } else {
                Log::warning('Order user not loaded:', [
                    'order_id' => $model->user_id, 
                    'created_by' => $model->created_by
                ]);
            }
        });
    }
}