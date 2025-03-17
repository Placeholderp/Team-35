@extends('layouts.app')

@section('title', 'About Us')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('navigation')
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
        <div class="container">
            <img src="{{ asset('storage/images/team_logo.png') }}" alt="">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span><i id="bar" class="fas fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop') }}">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blog') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('about') }}">About</a>
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
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <!-- HERO / PAGE HEADER -->
    <section id="about-hero">
        <!-- Add top padding to avoid navbar overlap -->
        <div class="container pt-5 mt-5">
            <h2 class="font-weight-bold pt-5">About Us</h2>
            <hr>
        </div>
    </section>

    <!-- MAIN ABOUT SECTION -->
    <section id="about-container" class="container pt-5">
        <!-- Row 1: Brand Story / Image -->
        <div class="row mb-5">
            <div class="col-lg-6 col-md-6 col-12">
                <img class="w-100 img-fluid" src="{{ asset('img/about_image.png') }}" alt="About image">
            </div>
            <div class="col-lg-6 col-md-6 col-12 d-flex flex-column justify-content-center">
                <h3 class="font-weight-normal pt-3">Our Story</h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Sed euismod nisi quis massa tincidunt, a dictum dui convallis. 
                    Nam luctus urna id metus convallis, in facilisis tellus congue. 
                    Curabitur volutpat, ipsum in convallis ullamcorper, quam ex malesuada metus, 
                    at dignissim nulla est ac sapien.
                </p>
                <p>
                    Since our beginnings in [Year], we've been committed to delivering 
                    exceptional products and experiences to our customers. Our passion for 
                    quality and innovation has driven us every step of the way.
                </p>
            </div>
        </div>

        <!-- Row 2: Mission & Values -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="font-weight-normal pt-3">Our Mission & Values</h3>
                <p>
                    Our mission is to deliver outstanding products while fostering 
                    a culture of creativity, collaboration, and integrity. 
                    We believe in putting the customer first, pushing boundaries, 
                    and making a positive impact on the world around us.
                </p>
                <p>
                    In everything we do, we strive to uphold the following values:
                    <ul>
                        <li><strong>Integrity:</strong> We are honest and transparent in all our interactions.</li>
                        <li><strong>Innovation:</strong> We embrace new ideas and continuously improve.</li>
                        <li><strong>Community:</strong> We support and give back to the communities we serve.</li>
                    </ul>
                </p>
            </div>
        </div>

        <!-- Row 3: Optional Banner or Image -->
        <div class="row mb-5">
            <div class="col-12">
                <img class="w-100 img-fluid" src="{{ asset('img/ASTON 35.png') }}" alt="About banner">
            </div>
        </div>

        <!-- Row 4: Team Profiles -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="font-weight-normal pt-3">Meet the Team</h3>
            </div>
            <!-- Team Member 1 -->
            <div class="post col-lg-4 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/about/team-1.jpg') }}" alt="Team Member">
                </div>
                <h4 class="text-center font-weight-normal pt-3">Jane Doe</h4>
                <p class="text-center">CEO & Founder</p>
            </div>
            <!-- Team Member 2 -->
            <div class="post col-lg-4 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/about/team-2.jpg') }}" alt="Team Member">
                </div>
                <h4 class="text-center font-weight-normal pt-3">John Smith</h4>
                <p class="text-center">Head of Product</p>
            </div>
            <!-- Team Member 3 -->
            <div class="post col-lg-4 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/about/team-3.jpg') }}" alt="Team Member">
                </div>
                <h4 class="text-center font-weight-normal pt-3">Alex Johnson</h4>
                <p class="text-center">Lead Developer</p>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
@endsection