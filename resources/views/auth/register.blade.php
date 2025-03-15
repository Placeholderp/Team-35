<x-app-layout>
    {{-- Registration Form Container --}}
    <form action="{{ route('register') }}" method="post" class="w-[400px] mx-auto p-6 my-16">
        @csrf 

        {{-- Form Heading --}}
        <h2 class="text-2xl font-semibold text-center mb-4">Create an account</h2>
        
        {{-- link to the login page --}}
        <p class="text-center text-gray-500 mb-3">
            or
            <a href="{{ route('login') }}" class="text-sm text-purple-700 hover:text-purple-600">
                login with existing account
            </a>
        </p>

        {{-- Displays any status messages (e.g., registration success or error messages) --}}
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        {{-- Input field for user's name --}}
        <div class="mb-4">
            <x-input placeholder="Your name" type="text" name="name" :value="old('name')" />
        </div>
        
        {{-- Input field for user's email --}}
        <div class="mb-4">
            <x-input placeholder="Your Email" type="email" name="email" :value="old('email')" />
        </div>
        
        {{-- Input field for password --}}
        <div class="mb-4">
            <x-input placeholder="Password" type="password" name="password"/>
        </div>
        
        {{-- Input field for password confirmation --}}
        <div class="mb-4">
            <x-input placeholder="Repeat Password" type="password" name="password_confirmation"/>
        </div>

        {{-- Submits the registration form --}}
        <button class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full">
            Signup
        </button>
    </form>
</x-app-layout>
