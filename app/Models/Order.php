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
    
    // Changed primary key to standard 'id'
    protected $primaryKey = 'id';
    
    // Define attributes that are mass assignable
    protected $fillable = ['status', 'total_price', 'created_by', 'updated_by'];

    // Handle date formatting
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Add this to prevent Laravel from trying to save non-existent columns
    protected $guarded = [];
    
    // This makes user_id a virtual attribute that isn't saved to database
    protected $appends = ['user_id'];
    
    // Get the user_id (virtual attribute for backward compatibility)
    public function getUserIdAttribute()
    {
        return $this->id;
    }

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
        // Updated to use standard relationship
        return $this->hasOne(Payment::class);
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
        // Updated to use standard relationship
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Get the order detail record associated with this order.
     */
    public function detail(): HasOne
    {
        // Updated to use standard relationship
        return $this->hasOne(OrderDetail::class)->withDefault([
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
     * Modified boot method with safer backward compatibility approach
     */
    protected static function boot()
    {
        parent::boot();
        
        static::retrieved(function ($model) {
            // Debug logging to check user relationship
            if ($model->user) {
                Log::info('Order user loaded:', [
                    'order_id' => $model->id, 
                    'user_id' => $model->user->id,
                    'has_customer' => $model->user->customer ? 'yes' : 'no'
                ]);
            } else {
                Log::warning('Order user not loaded:', [
                    'order_id' => $model->id, 
                    'created_by' => $model->created_by
                ]);
            }
        });
    }
}