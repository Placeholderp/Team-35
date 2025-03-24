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
    Log::info('Logout attempt for user: ' . (Auth::check() ? Auth::user()->email : 'Unknown'));
    
    // Remember redirect URL
    $redirectTo = '/login';
    
    // Logout
    Auth::logout();
    
    // BOTH invalidate session AND regenerate token
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect($redirectTo);
}
}