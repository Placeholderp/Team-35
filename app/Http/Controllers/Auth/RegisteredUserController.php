<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Cart;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create a new user record with the validated data.
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create a corresponding customer record.
        $names = explode(" ", $user->name);
        $customer = new Customer();
        $customer->user_id    = $user->id;
        $customer->first_name = $names[0] ?? '';
        $customer->last_name  = $names[1] ?? '';
        $customer->save();

        // Log in the newly registered user.
        Auth::login($user);

        Cart::moveCartItemsIntoDb();

        return redirect(RouteServiceProvider::HOME);
    }
}
