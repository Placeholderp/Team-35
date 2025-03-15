<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

     // This allows these fields to be bulk assigned during model creation/updating.
    protected $fillable = ['user_id', 'quantity', 'product_id',];
}
