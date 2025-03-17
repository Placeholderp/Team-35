@extends('layouts.app')

@section('title', 'Modern Day Slavery Statement')

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
    <section id="modern-day-slavery-statement" class="container py-5" style="margin-top: 80px;">
        <h1 class="text-center mb-4">Modern Day Slavery Statement</h1>
        <p>At Aston35Fitness, we are committed to ensuring that our business operations are free from modern-day slavery and human trafficking. We take a zero-tolerance approach to any form of exploitation in our operations or supply chain.</p>

        <h2>1. Our Commitment</h2>
        <p>We are committed to upholding the highest standards of ethics and integrity in our business operations. We do not tolerate slavery or human trafficking, and we take active steps to identify and prevent such practices within our business and supply chain.</p>

        <h2>2. Our Supply Chain</h2>
        <p>Our supply chain consists of various manufacturers, suppliers, and distributors. We work closely with our suppliers to ensure that they adhere to the same high standards of ethical business practices and are committed to preventing slavery and human trafficking.</p>

        <h2>3. Risk Assessment</h2>
        <p>We regularly assess the risk of modern-day slavery within our supply chain. This includes reviewing the practices of our suppliers and taking steps to mitigate any risks associated with forced labor, human trafficking, and other forms of exploitation.</p>

        <h2>4. Due Diligence</h2>
        <p>We conduct due diligence to ensure that our suppliers, contractors, and business partners are compliant with the Modern Slavery Act. This includes requesting information on their policies, labor practices, and supply chain management procedures.</p>

        <h2>5. Training and Awareness</h2>
        <p>We provide regular training to our employees and contractors on the risks of modern-day slavery. This training helps them identify potential signs of exploitation and understand their role in upholding our commitment to ethical practices.</p>

        <h2>6. Reporting and Accountability</h2>
        <p>We have established a clear process for reporting any concerns related to modern-day slavery. Any reports will be investigated thoroughly, and appropriate action will be taken to address any issues. Employees, suppliers, and stakeholders can contact us directly to report any concerns.</p>

        <h2>7. Future Actions</h2>
        <p>We are continually reviewing and improving our practices to ensure that modern-day slavery is not a part of our operations or supply chain. We are committed to making our supply chain more transparent and ensuring that we take every possible step to eliminate any risk of exploitation.</p>

        <h2>8. Contact Us</h2>
        <p>If you have any questions or concerns regarding our Modern Day Slavery Statement or any related matter, please contact us at:</p>
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