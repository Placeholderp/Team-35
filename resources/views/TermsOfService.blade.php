@extends('layouts.app')

@section('title', 'Terms of Service')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('navigation')
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
        <div class="container">
            <img src="{{ asset('/images/team_logo.png') }}" alt="">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span><i id="bar" class="fas fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop') }}">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blog') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('calorie.calculator') }}">Calorie Calculator</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                    </li>
                    <li class="nav-item">
                <i class="fal fa-search"></i>
                <i onclick="window.location.href='{{ route('cart.index') }}';" class="fal fa-shopping-bag"></i>

                    </li>

                        <li class="nav-item">
                        <div class="profile">
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile" id="profile-icon">
                            <div class="profile-dropdown" id="profile-dropdown">
                                <p class="profile-name">Name: John Doe</p>
                                <button id="logout-btn">Logout</button>
                            </div>
                            
                        </div>

            </li>
            
                    
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <section id="terms-of-service" class="container py-5" style="margin-top: 80px;">
        <h1 class="text-center mb-4">Terms of Service</h1>
        <p>Welcome to Aston35Fitness! These Terms of Service ("Terms") govern your access to and use of our website and services. By using our website, you agree to be bound by these Terms.</p>

        <h2>1. Acceptance of Terms</h2>
        <p>By accessing or using our website, you agree to comply with and be bound by these Terms. If you do not agree to these Terms, please do not use our services.</p>

        <h2>2. Use of Our Services</h2>
        <p>Our website is available for personal, non-commercial use. You may not use our website or services for any illegal or unauthorized purposes.</p>

        <h2>3. Account Registration</h2>
        <p>To access certain features on our website, you may need to create an account. You agree to provide accurate and complete information when registering.</p>

        <h2>4. Payments and Pricing</h2>
        <p>All payments for products or services on our website must be made using the available payment methods. Prices for products are subject to change without notice.</p>

        <h2>5. Privacy</h2>
        <p>We respect your privacy and are committed to protecting your personal data. Please review our Privacy Policy for more information on how we handle your data.</p>

        <h2>6. Intellectual Property</h2>
        <p>All content, trademarks, and logos on our website are the property of Aston35Fitness and are protected by intellectual property laws. You may not use our content without our permission.</p>

        <h2>7. Limitation of Liability</h2>
        <p>We are not responsible for any damages or losses resulting from your use of our website, including direct, indirect, incidental, or consequential damages.</p>

        <h2>8. Termination</h2>
        <p>We reserve the right to suspend or terminate your access to our website if you violate these Terms or engage in harmful activities.</p>

        <h2>9. Changes to These Terms</h2>
        <p>We may update or modify these Terms from time to time. Any changes will be posted on this page with an updated revision date. Please review these Terms periodically.</p>

        <h2>10. Contact Us</h2>
        <p>If you have any questions or concerns about these Terms, please contact us at:</p>
        <p>Email: Aston35@info.com</p>
        <p>Phone: 0121 204 3000</p>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection