<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        \Log::info('Password change request', ['data' => $request->except('current_password', 'new_password')]);
        
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8'],
                'force_change' => ['boolean'],
            ]);
    
            if ($validator->fails()) {
                \Log::error('Password validation failed', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $user = Auth::user();
            
            // Check if user is authenticated
            if (!$user) {
                \Log::error('User not authenticated');
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }
    
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                \Log::error('Current password is incorrect');
                return response()->json([
                    'message' => 'Current password is incorrect'
                ], 422);
            }
    
            // Update user password directly
            $user->password = Hash::make($request->new_password);
            $user->force_password_change = false;
            $saved = $user->save();
            
            \Log::info('Password update result', ['success' => $saved]);
    
            return response()->json([
                'message' => 'Password changed successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'force_password_change' => false,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Password change exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Password change failed: ' . $e->getMessage(),
                'exception_type' => get_class($e)
            ], 500);
        }
    }
}