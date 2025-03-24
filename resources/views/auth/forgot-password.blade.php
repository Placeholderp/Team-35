<x-app-layout>
    {{-- Main layout component for the application --}}
    
    {{-- Password Reset Request Form --}}
    <form action="{{ route('password.email') }}" method="post" class="w-[400px] mx-auto p-6 my-16">
        @csrf 
        
        {{-- Heading for the form --}}
        <h2 class="text-2xl font-semibold text-center mb-5">
            Enter your Email to reset password
        </h2>

        {{-- Display session status messages (e.g., "Password reset link sent") --}}
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        {{-- Informational text with a link to login for users with existing accounts --}}
        <p class="text-center text-gray-500 mb-6">
            or
            <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-500">
                login with existing account
            </a>
        </p>

        {{-- Email input field --}}
        <div class="mb-3">
            <x-input id="email" class="block mt-1 w-full" type="email" name="email" 
                     :value="old('email')" required autofocus 
                     placeholder="Enter your Email Address"/>
        </div>
        
        {{-- Submit button to send the password reset link --}}
        <button class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full">
            Email Password Reset Link
        </button>
    </form>
</x-app-layout>
