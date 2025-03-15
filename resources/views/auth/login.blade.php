<x-app-layout>
    {{-- Main container: Wraps the login form inside the application layout --}}
    <form method="POST" action="{{ route('login') }}" class="w-[400px] mx-auto p-6 my-16">
        {{-- Heading: Title for the login form --}}
        <h2 class="text-2xl font-semibold text-center mb-5">
            Login to your account
        </h2>

        {{-- Provides an alternative option to create a new account --}}
        <p class="text-center text-gray-500 mb-6">
            or
            <a
                href="{{ route('register') }}"
                class="text-sm text-purple-700 hover:text-purple-600"
            >
                create new account
            </a>
        </p>

        {{-- Displays any status messages --}}
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        @csrf

        {{-- Allows the user to enter their email address --}}
        <div class="mb-4">
            <x-input type="email" name="email" placeholder="Your email address" :value="old('email')"/>
        </div>

        {{-- Allows the user to enter their password --}}
        <div class="mb-4">
            <x-input type="password" name="password" placeholder="Your password" :value="old('password')" />
        </div>

        {{-- "Remember Me" checkbox and link for password reset --}}
        <div class="flex justify-between items-center mb-5">
            {{-- Remember Me Checkbox --}}
            <div class="flex items-center">
                <input
                    id="loginRememberMe"
                    type="checkbox"
                    class="mr-3 rounded border-gray-300 text-purple-500 focus:ring-purple-500"
                />
                <label for="loginRememberMe">Remember Me</label>
            </div>

            {{-- Forgot Password Link: Displays if the password reset route exists --}}
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-purple-700 hover:text-purple-600">
                    Forgot Password?
                </a>
            @endif
        </div>

        {{-- Submits the form --}}
        <button
            class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full"
        >
            Login
        </button>
    </form>
</x-app-layout>
