@extends('layouts.app')

@section('title', 'Home')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .category-badge {
            display: inline-block;
            padding: 4px 8px;
            margin-bottom: 10px;
            background-color: #7272ff; /* A light blue that matches your theme */
            color: white;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .category-filter {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .category-filter .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
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
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <section id="home">
        <div class="container">
            <h5>NEW ARRAIVALS</h5>
            <h1><span>Best Price</span> This Year</h1>
            <p>Fuel Your Ambition Transform Your Body.</p>
            <button>Shop Now</button>
        </div>
    </section>

    <section id="brand" class="container">
        <div class="row m-0 py-5">
            <img class="img-fluid col-lg-2 col-md-4 col-6" src="{{ asset('storage/images/Index1.png') }}" alt="">
            <img class="img-fluid col-lg-2 col-md-4 col-6" src="{{ asset('storage/images/I2.png') }}" alt="">
            <img class="img-fluid col-lg-2 col-md-4 col-6" src="{{ asset('storage/images/Index3.png') }}" alt="">
            <img class="img-fluid col-lg-2 col-md-4 col-6" src="{{ asset('storage/images/Index4.png') }}" alt="">
            <img class="img-fluid col-lg-2 col-md-4 col-6" src="{{ asset('storage/images/Index5.png') }}" alt="">
            <img class="img-fluid col-lg-2 col-md-4 col-6" src="{{ asset('storage/images/Index6.png') }}" alt="">
        </div>
    </section>

    <section id="new" class="w-100">
        <div class="row p-0 m-0">
            <div class="one col-lg-4 col-md-12 col-12 p-0">
                <img class="img-fluid" src="{{ asset('storage/images/new/1.jpg') }}" alt="">
                <div class="details">
                    <h2>Extreme Rare Sneakers</h2>
                    <button class="text-uppercase">Shop now</button>
                </div>
            </div>
            <div class="one col-lg-4 col-md-12 col-12 p-0">
                <img class="img-fluid" src="{{ asset('storage/images/new/5.jpg') }}" alt="">
                <div class="details">
                    <h2>Awesome Blank Outfit</h2>
                    <button class="text-uppercase">Shop now</button>
                </div>
            </div>
            <div class="one col-lg-4 col-md-12 col-12 p-0">
                <img class="img-fluid" src="{{ asset('storage/images/new/3.jpg') }}" alt="">
                <div class="details">
                    <h2>Sportwear Up To 50% Off</h2>
                    <button class="text-uppercase">Shop now</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Filter Section -->
    @if(isset($categories) && count($categories) > 0)
    <section id="category-filter" class="container my-5">
        <div class="category-filter">
            <h4 class="mb-3">Browse By Category</h4>
            <div class="d-flex flex-wrap">
                <a href="{{ route('shop') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">
                    All Categories
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('shop', ['category' => $category->slug]) }}" 
                       class="btn {{ request('category') == $category->slug ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section id="featured" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
            <h3>Our Featured</h3>
            <hr class="mx-auto">
            <p>Here you can check out our new products with fair price on rymo.</p>
        </div>
        <div class="row mx-auto container-fluid">
            @foreach($products ?? [] as $product)
                <div class="product text-center col-lg-3 col-md-4 col-12">
                    <img class="img-fluid mb-3" src="{{ asset($product->image) }}" alt="{{ $product->title ?? $product->name }}">
                    <div class="star">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                    
                    <!-- Category Badge -->
                    @if(isset($product->category) && $product->category)
                        <span class="category-badge">{{ $product->category->name }}</span>
                    @endif
                    
                    <h5 class="p-name">{{ $product->title ?? $product->name }}</h5>
                    <h4 class="p-price">${{ number_format($product->price, 2) }}</h4>
                    <button class="buy-btn">Buy Now</button>
                </div>
            @endforeach

            @if(empty($products) || count($products) < 4)
                <div class="product text-center col-lg-3 col-md-4 col-12">
                    <img class="img-fluid mb-3" src="{{ asset('storage/images/featured/1.jpg') }}" alt="">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="p-name">Sport Boots</h5>
                    <h4 class="p-price">$92.00</h4>
                    <button class="buy-btn">Buy Now</button>
                </div>
                <div class="product text-center col-lg-3 col-md-4 col-12">
                    <img class="img-fluid mb-3" src="{{ asset('storage/images/featured/2.jpg') }}" alt="">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="p-name">Sport Boots</h5>
                    <h4 class="p-price">$92.00</h4>
                    <button class="buy-btn">Buy Now</button>
                </div>
                <div class="product text-center col-lg-3 col-md-4 col-12">
                    <img class="img-fluid mb-3" src="{{ asset('storage/images/featured/3.jpg') }}" alt="">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="p-name">Sport Boots</h5>
                    <h4 class="p-price">$92.00</h4>
                    <button class="buy-btn">Buy Now</button>
                </div>
                <div class="product text-center col-lg-3 col-md-4 col-12">
                    <img class="img-fluid mb-3" src="{{ asset('storage/images/featured/4.jpg') }}" alt="">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="p-name">Sport Boots</h5>
                    <h4 class="p-price">$92.00</h4>
                    <button class="buy-btn">Buy Now</button>
                </div>
            @endif
        </div>
    </section>

    <section id="banner" class="my-5 py-5">
        <div class="container">
            <h4>MID SEASON'S SALE</h4>
            <h1>Autumn Collection<br>UP TO 20% OFF</h1>
            <button class="text-uppercase">Shop Now</button>
        </div>
    </section>

    <section id="clothes" class="my-5">
        <div class="container text-center mt-5 py-5">
            <h3>Dresses & Jumpsuits</h3>
            <hr class="mx-auto">
            <p>Here you can check out our new products with fair price on rymo.</p>
        </div>
        <div class="row mx-auto container-fluid">
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/clothes/1.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/clothes/2.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/clothes/3.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/clothes/1.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
        </div>
    </section>

    <section id="watches" class="my-5">
        <div class="container text-center mt-5 py-5">
            <h3>Best Watches</h3>
            <hr class="mx-auto">
            <p>Here you can check out our new products with fair price on rymo.</p>
        </div>
        <div class="row mx-auto container-fluid">
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/watches/1.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/watches/2.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/watches/3.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/watches/1.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
        </div>
    </section>

    <section id="shoes" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
            <h3>Running Shoes</h3>
            <hr class="mx-auto">
            <p>Here you can check out our new products with fair price on rymo.</p>
        </div>
        <div class="row mx-auto container-fluid">
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/shoes/8.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/shoes/7.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('storage/images/shoes/6.jpg') }}" alt="">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ isset($product) && isset($product->image_url) ? $product->image_url : (isset($product) && isset($product->image) ? asset($product->image) : asset('storage/images/shoes/5.jpg')) }}" alt="{{ isset($product) ? $product->title ?? $product->name : 'Product' }}">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name">Sport Boots</h5>
                <h4 class="p-price">$92.00</h4>
                <button class="buy-btn">Buy Now</button>
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