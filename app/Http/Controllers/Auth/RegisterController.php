<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegistrationCode;
use App\Models\User;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the registration view.
     */
    public function showRegistrationForm()
    {
        return view('login');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request)
    {
        try {
            Log::info('Registration attempt with data: ' . json_encode($request->except(['password', 'password_confirmation'])));
            
            // Basic validation for all registrations
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            // Check if this is an admin registration
            $isAdmin = $request->has('is_admin');
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => false,
                'force_password_change' => false,
            ];

            // If admin registration, validate the registration code
            if ($isAdmin) {
                $request->validate([
                    'registration_code' => ['required', 'string'],
                ]);

                $validCode = RegistrationCode::where('code', 'LIKE', $request->registration_code)
                    ->where('active', true)
                    ->where('type', 'admin')
                    ->first();

                if (!$validCode) {
                    return back()
                        ->withInput()
                        ->withErrors(['registration_code' => 'Invalid registration code. Please contact the system administrator.']);
                }

                // Update user data for admin
                $userData['is_admin'] = true;
                $userData['force_password_change'] = true;
            }

            // Create the user
            $user = User::create($userData);
            
            Log::info('User created: ' . $user->email);
            
            // Create a customer record (if your app needs this)
            try {
                if (class_exists('App\\Models\\Customer')) {
                    $names = explode(" ", $user->name);
                    $customer = new Customer();
                    $customer->user_id = $user->id;
                    $customer->first_name = $names[0] ?? '';
                    $customer->last_name = $names[1] ?? '';
                    $customer->save();
                    
                    Log::info('Customer record created for user: ' . $user->email);
                }
            } catch (\Exception $e) {
                Log::warning('Could not create customer record: ' . $e->getMessage());
                // Continue anyway - this isn't critical
            }

            // Fire registered event
            event(new Registered($user));

            // Log the user in
            Auth::login($user);
            
            Log::info('User logged in, redirecting to dashboard');

            // Use multiple fallback approaches for redirection
            try {
                // First approach: Use the RouteServiceProvider HOME constant directly
                return redirect(RouteServiceProvider::HOME)->with('status', 'Account created successfully!');
            } catch (\Exception $e) {
                Log::warning('Failed to redirect using RouteServiceProvider::HOME: ' . $e->getMessage());
                
                try {
                    // Second approach: Use the named route
                    return redirect()->route('home')->with('status', 'Account created successfully!');
                } catch (\Exception $e) {
                    Log::warning('Failed to redirect using named route: ' . $e->getMessage());
                    
                    // Third approach: Direct URL as last resort
                    return redirect('/dashboard')->with('status', 'Account created successfully!');
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return back()
                ->withInput()
                ->withErrors(['general' => 'An error occurred during registration. Please try again.']);
        }
    }
}