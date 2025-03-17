@extends('layouts.app')

@section('title', 'About Us')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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