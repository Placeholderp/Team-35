@extends('layouts.app')

@section('title', 'Contact Us')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
@endsection

@section('content')
    <!-- HERO / PAGE HEADER -->
    <section id="contact-hero">
        <!-- Add top padding to avoid navbar overlap -->
        <div class="container pt-5 mt-5">
            <h2 class="font-weight-bold pt-5">Contact Us</h2>
            <hr />
        </div>
    </section>

    <!-- CONTACT FORM SECTION -->
    <section id="contact-form" class="container pt-5">
        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-6 col-md-6 col-12 pb-5">
                <h3 class="font-weight-normal mb-4">We'd Love to Hear From You</h3>

                <form>
                    @csrf
                    <div class="form-group">
                        <label for="contactName">Name</label>
                        <input
                            type="text"
                            class="form-control"
                            id="contactName"
                            placeholder="Your Name"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="contactEmail">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            id="contactEmail"
                            placeholder="Your Email"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="contactSubject">Subject</label>
                        <input
                            type="text"
                            class="form-control"
                            id="contactSubject"
                            placeholder="Subject"
                        />
                    </div>

                    <div class="form-group">
                        <label for="contactMessage">Message</label>
                        <textarea
                            class="form-control"
                            id="contactMessage"
                            rows="5"
                            placeholder="Write your message here..."
                            required
                        ></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Send Message</button>
                </form>
            </div>

            <!-- Contact Info or Map -->
            <div class="col-lg-6 col-md-6 col-12 pb-5">
                <h3 class="font-weight-normal mb-4">Our Office</h3>
                <p>
                    <strong>Address:</strong> Aston St, Birmingham B4 7ET
                </p>
                <p>
                    <strong>Phone:</strong> 0121 204 3000
                </p>
                <p>
                    <strong>Email:</strong> Aston35@info.com
                </p>
                <!-- Example: Embedded Google Map -->
                <div style="height: 300px; width: 100%;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2422.529961233278!2d-1.8877646235055693!3d52.48624627218281!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870bc87d87c9a5f%3A0xe0e166ace7378d5d!2sAston%20University!5e0!3m2!1sen!2suk!4v1710183600000!5m2!1sen!2suk" 
                        width="100%"
                        height="100%"
                        style="border: 0;"
                        allowfullscreen=""
                        loading="lazy"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('navigation')
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
        <div class="container">
            <img src="{{ asset('img/logo1.png') }}" alt="Logo" />
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
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
                        <i onclick="window.location.href='{{ route('cart') }}';" class="fal fa-shopping-bag"></i>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('scripts')
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"
        integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG"
        crossorigin="anonymous"
    ></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"
        integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc"
        crossorigin="anonymous"
    ></script>
@endsection