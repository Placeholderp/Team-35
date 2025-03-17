<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegistrationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'registration_code' => ['required', 'string'],
        ]);

        // Validate registration code
        $validCode = RegistrationCode::where('code', 'LIKE', $request->registration_code)
        ->where('active', true)
        ->where('type', 'admin')
        ->first();
        if (!$validCode) {
            return response()->json([
                'message' => 'Invalid registration code. Please contact the system administrator.'
            ], 400);
        }

        // Create user with force_password_change flag
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
            'force_password_change' => true,
        ]);

        return response()->json([
            'message' => 'Admin account created successfully',
            'user' => $user
        ], 201);
    }
}