<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'quantity',
        'previous_quantity',
        'new_quantity',
        'type',
        'reference',
        'notes',
        'created_by'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

