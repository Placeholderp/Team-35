<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user exists and is an admin
        if (!$user || !Hash::check($request->password, $user->password) || !$user->is_admin) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect or you do not have admin privileges.'],
            ]);
        }

        // Create token for admin user
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Register a new admin user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'registration_code' => 'required|string',
        ]);

        // Verify the registration code
        $validRegistrationCode = config('auth.admin_registration_code', env('ADMIN_REGISTRATION_CODE'));
        
        if ($request->registration_code !== $validRegistrationCode) {
            return response()->json([
                'message' => 'Invalid registration code'
            ], 403);
        }

        // Create the user with admin role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
            'force_password_change' => true, // Force password change on first login
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user
        ], 201);
    }

    /**
     * Change user password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        $user = $request->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->force_password_change = false; // Remove the force password change flag
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully',
            'user' => $user
        ]);
    }

    /**
     * Get authenticated user details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true
        ]);
    }
}