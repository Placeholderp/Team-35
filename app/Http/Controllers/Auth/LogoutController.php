<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        // Log the logout attempt (optional)
        Log::info('Logout attempt for user: ' . (Auth::check() ? Auth::user()->email : 'Unknown'));
        
        // Get intended destination before logout
        $redirectTo = '/login';
        
        // Perform logout
        Auth::logout();
        
        // Regenerate session (but don't invalidate yet)
        $request->session()->regenerate();
        
        // Redirect to login
        return redirect($redirectTo);
    }
}