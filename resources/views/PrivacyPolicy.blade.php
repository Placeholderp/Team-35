@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('navigation')
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
        <div class="container">
            <img src="{{ asset('img/logo1.png') }}" alt="">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span><i id="bar" class="fas fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('blog') }}">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('calorie.calculator') }}">Calorie Calculator</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact Us</a></li>
                    <li class="nav-item">
                        <i onclick="window.location.href='{{ route('cart.index') }}';" class="fal fa-shopping-bag"></i>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <section id="privacy-policy" class="container py-5" style="margin-top: 80px;">
        <h1 class="text-center mb-4">Privacy Policy</h1>
        <p>At Aston35Fitness, we value your privacy and are committed to protecting your personal information. This privacy policy outlines how we collect, use, and safeguard your data when you interact with our website.</p>

        <h2>Information We Collect</h2>
        <p>We collect various types of information, including:</p>
        <ul>
            <li><strong>Personal Information:</strong> Such as name, email address, and payment information when you make a purchase.</li>
            <li><strong>Usage Data:</strong> Information about how you interact with our website (e.g., IP address, browser type, pages visited).</li>
        </ul>

        <h2>How We Use Your Information</h2>
        <p>We use the information we collect to:</p>
        <ul>
            <li>Process transactions and deliver services or products you request.</li>
            <li>Improve your browsing experience and personalize content.</li>
            <li>Communicate with you, including sending promotional offers or newsletters (with your consent).</li>
            <li>Comply with legal obligations and protect our website and users.</li>
        </ul>

        <h2>Data Security</h2>
        <p>Your privacy is a priority for us. We implement various security measures, including encryption and access controls, to protect your personal information.</p>

        <h2>Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Access the personal information we hold about you.</li>
            <li>Request corrections or deletion of your personal information, subject to certain conditions.</li>
            <li>Withdraw your consent at any time for marketing communications.</li>
        </ul>

        <h2>Cookies</h2>
        <p>Our website uses cookies to enhance user experience. You can manage cookie preferences through your browser settings.</p>

        <h2>Changes to This Policy</h2>
        <p>We may update this Privacy Policy occasionally. When changes are made, the updated policy will be posted on this page with a revised date. Please review it periodically.</p>

        <h2>Contact Us</h2>
        <p>If you have any questions or concerns about our privacy practices, feel free to contact us at:</p>
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
@endsection