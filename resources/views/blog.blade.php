@extends('layouts.app')

@section('title', 'Blog')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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