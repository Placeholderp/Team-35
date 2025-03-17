@extends('layouts.app')

@section('title', 'Blog')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
                        <a class="nav-link active" href="{{ route('blog') }}">Blog</a>
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
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <section id="blog-home">
        <div class="container pt-5 mt-5">
            <h2 class="font-weight-bold pt-5">Blogs</h2>
            <hr>
        </div>
    </section>

    <section id="blog-container" class="container pt-5">
        <div class="row">
            @foreach($featuredPosts ?? [] as $post)
            <div class="post col-lg-6 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/' . $post->image) }}" alt="{{ $post->title }}">
                </div>
                <h3 class="text-center font-weight-normal pt-3">{{ $post->title }}</h3>
                <p class="text-center">{{ $post->created_at->format('M d, Y') }}</p>
            </div>
            @endforeach

            <!-- If no dynamic data, use the static content -->
            @if(empty($featuredPosts))
            <div class="post col-lg-6 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/1.jpg') }}" alt="">
                </div>
                <h3 class="text-center font-weight-normal pt-3">The best ways to change your summer wardrobe into autumn wardrobe.</h3>
                <p class="text-center">Jan 11, 2021</p>
            </div>
            <div class="post col-lg-6 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/2.jpg') }}" alt="">
                </div>
                <h3 class="text-center font-weight-normal pt-3">Men's fashion in leather.</h3>
                <p class="text-center">Jan 11, 2021</p>
            </div>
            <div class="post col-lg-6 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/3.jpg') }}" alt="">
                </div>
                <h3 class="text-center font-weight-normal pt-3">DIYer and TV host Trisha Hershberger's journey through gaming keeps evolving.</h3>
                <p class="text-center">Jan 11, 2021</p>
            </div>
            <div class="post col-lg-6 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/4.jpg') }}" alt="">
                </div>
                <h3 class="text-center font-weight-normal pt-3">The best ways to change your summer wardrobe into autumn wardrobe.</h3>
                <p class="text-center">Jan 11, 2021</p>
            </div>
            @endif

            <div class="col-lg-12 col-md-12 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/banner.webp') }}" alt="">
                </div>
            </div>

            <!-- More blog posts -->
            @foreach($regularPosts ?? [] as $post)
            <div class="post col-lg-4 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/' . $post->image) }}" alt="{{ $post->title }}">
                </div>
                <h4 class="font-weight-normal pt-3">{{ $post->title }}</h4>
            </div>
            @endforeach

            <!-- If no dynamic data, use the static content -->
            @if(empty($regularPosts))
            <div class="post col-lg-4 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/1.webp') }}" alt="">
                </div>
                <h4 class="font-weight-normal pt-3">The best ways to change your summer wardrob.</h4>
            </div>
            <div class="post col-lg-4 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/3.webp') }}" alt="">
                </div>
                <h4 class="font-weight-normal pt-3">Lenovo's smarter devices stoke professional passions</h4>
            </div>
            <div class="post col-lg-4 col-md-6 col-12 pb-5">
                <div class="post-img">
                    <img class="w-100 img-fluid" src="{{ asset('img/blog/2.webp') }}" alt="">
                </div>
                <h4 class="font-weight-normal pt-3">Take a 3D tour through a Microsoft datacenter</h4>
            </div>
            @endif
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