<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'PaymentID';
    protected $fillable = ['OrderID', 'CardDetails', 'PaymentStatus', 'PaymentDate'];
    public $timestamps = false;

   

public function order()
{
    return $this->belongsTo(Order::class, 'OrderID');
}

public function scopeStatus($query, $status)
    {
        return $query->where('PaymentStatus', $status);
    }
   
}