<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'showLoginForm']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        try {
            Log::info('Login attempt for email: ' . $request->email);
            
            // Validate the login request
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Attempt to log the user in
            $credentials = $request->only('email', 'password');
            $remember = $request->filled('remember');

            if (Auth::attempt($credentials, $remember)) {
                // Authentication passed
                Log::info('Login successful for: ' . $request->email);
                
                // Regenerate the session
                $request->session()->regenerate();
                
                return redirect()->intended($this->redirectTo);
            }

            // Authentication failed
            Log::info('Login failed for: ' . $request->email);
            
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => trans('auth.failed'),
                ]);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        try {
            Log::info('Logout attempt by user: ' . (Auth::user() ? Auth::user()->email : 'Unknown'));
            
            // Remember the user was logged in before we log them out
            $wasLoggedIn = Auth::check();
            
            // Log the user out
            Auth::logout();
            
            // Don't invalidate the session, just regenerate the CSRF token
            // This is key to avoiding the Page Expired error
            $request->session()->regenerateToken();
            
            Log::info('User logged out successfully');
            
            // Use direct URL path to avoid route resolution issues
            return redirect('/login');
        } 
        catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            
            // Even if there's an error, try to redirect the user somewhere
            return redirect('/');
        }
    }
}