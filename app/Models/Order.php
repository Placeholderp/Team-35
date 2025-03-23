<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    
    // Either remove this line completely OR change to 'id'
    protected $primaryKey = 'id';
    
    // Define the attributes that are mass assignable.
    protected $fillable = ['status', 'total_price', 'created_by', 'updated_by'];

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
        return $this->hasOne(Payment::class);
    }

    /**
     * The foreign key is 'created_by' in the orders table.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * This retrieves all order items associated with this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Get the order detail record associated with this order.
     */
    public function detail(): HasOne
    {
        return $this->hasOne(OrderDetail::class);
    }
}