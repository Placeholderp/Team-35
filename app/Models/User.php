<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
/********************************
Developer: Oliver Blatchford
University ID: 230163795
Function: This makes the user table variables accessible,
********************************/
    use HasFactory;
    protected $primaryKey = 'userID';
    protected $fillable = [
        'username',
        'passwordHash',
        'email',
        'role',
        'name',
    ];

    //Hides sensitive data
    protected $hidden = [
        'passwordHash',
        //Remebers users being logged in
        'remember_token',
    ];

    //Puts data into correct format and hashes the password for extra protection
    protected $casts = [
        'password' => 'hashed',
    ];
}
