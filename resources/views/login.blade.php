<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login & Registration - Aston35Fitness</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style1.css') }}">
</head>
<body>
    <div class="container">
        <div class="toggle-box">
            <h1>Hello, Welcome!</h1>
            <p>Don't have an account?</p>
            <button class="btn register-btn">Register</button>
        </div>

        <div class="form-container">
            <!-- Login Form -->
            <div class="form-box login-form">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <h1>Login</h1>
                    
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="input-box">
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        <i class='bx bxs-envelope'></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" id="login-password" placeholder="Password" required>
                        <span class="toggle-password" data-target="login-password">
                            <i class='bx bx-hide'></i>
                        </span>
                    </div>
                    
                    <div class="remember-me">
                        <label>
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                    </div>
                    
                    <div class="forgot-link">
                      
                    </div>
                    
                    <button type="submit" class="btn">Login</button>
                    <p>Don't have an account? <a href="#" class="toggle-register">Register</a></p>
                </form>
            </div>

            <!-- Registration Form -->
            <div class="form-box register-form">
                <form action="{{ route('register') }}" method="POST" id="register-form">
                    @csrf
                    <!-- Add an explicit CSRF token field -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <h1>Register</h1>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="input-box">
                        <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    
                    <div class="input-box">
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        <i class='bx bxs-envelope'></i>
                    </div>
                    
                    <div class="input-box">
                        <input type="password" name="password" id="register-password" placeholder="Password" required>
                        <span class="toggle-password" data-target="register-password">
                            <i class='bx bx-hide'></i> 
                        </span>
                    </div>
                    
                    <div class="input-box">
                        <input type="password" name="password_confirmation" id="register-password-confirm" placeholder="Confirm Password" required>
                        <span class="toggle-password" data-target="register-password-confirm">
                            <i class='bx bx-hide'></i> 
                        </span>
                    </div>
                    
                    <div class="admin-toggle">
                        <label>
                            <input type="checkbox" id="admin-toggle-checkbox" name="is_admin"> Register as admin
                        </label>
                    </div>
                    
                    <div class="input-box admin-code-box" style="display: none;">
                        <input type="text" name="registration_code" id="registration-code" placeholder="Admin Registration Code">
                        <i class='bx bxs-key'></i>
                    </div>
                    
                    <button type="submit" class="btn">Register</button>
                    <p>Already have an account? <a href="#" class="toggle-login">Login</a></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Go back to home link -->
    <div class="back-to-home">
        <a href="{{ route('home') }}">
            <i class='bx bx-arrow-back'></i> Back to Home
        </a>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
    
    <!-- Additional script for CSRF handling -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission handler for registration
        const registerForm = document.getElementById('register-form');
        
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                // Don't prevent default submission - let the form submit normally with the token
                
                // Just for debugging - log the token being sent
                console.log('CSRF Token in form:', this.querySelector('input[name="_token"]').value);
                console.log('CSRF Token in meta:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            });
        }
        
        // Add global AJAX setup for CSRF if using any AJAX calls
        if (typeof window.jQuery !== 'undefined') {
            window.jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
        }
    });
    </script>
</body>
</html>