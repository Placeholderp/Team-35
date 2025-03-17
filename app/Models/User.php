<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'is_admin',
        'force_password_change'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'force_password_change' => 'boolean'
    ];

    /**
     * Get the user's admin status based on email.
     * This method will ensure users with certain emails are always treated as admins
     * regardless of what's in the database.
     *
     * @return bool
     */
    public function getIsAdminAttribute($value)
    {
    
        // Otherwise, return the actual value from the database
        return (bool) $value;
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}