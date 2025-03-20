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
            background-color: #7272ff;
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
            <h5>NEW ARRIVALS</h5>
            <h1><span>Best Price</span> This Year</h1>
            <p>Fuel Your Ambition Transform Your Body.</p>
            <a href="{{ route('shop') }}" class="btn">Shop Now</a>
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
            @if(isset($banners) && count($banners) > 0)
                @foreach($banners as $banner)
                <div class="one col-lg-4 col-md-12 col-12 p-0">
                    <img class="img-fluid" src="{{ asset($banner->image) }}" alt="{{ $banner->title }}">
                    <div class="details">
                        <h2>{{ $banner->title }}</h2>
                        <a href="{{ $banner->link_url ?? route('shop') }}" class="text-uppercase btn">{{ $banner->button_text ?? 'Shop now' }}</a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="one col-lg-4 col-md-12 col-12 p-0">
                    <img class="img-fluid" src="{{ asset('storage/images/new/1.jpg') }}" alt="">
                    <div class="details">
                        <h2>Extreme Rare Sneakers</h2>
                        <a href="{{ route('shop', ['category' => 'shoes']) }}" class="text-uppercase btn">Shop now</a>
                    </div>
                </div>
                <div class="one col-lg-4 col-md-12 col-12 p-0">
                    <img class="img-fluid" src="{{ asset('storage/images/new/5.jpg') }}" alt="">
                    <div class="details">
                        <h2>Awesome Blank Outfit</h2>
                        <a href="{{ route('shop', ['category' => 'clothing']) }}" class="text-uppercase btn">Shop now</a>
                    </div>
                </div>
                <div class="one col-lg-4 col-md-12 col-12 p-0">
                    <img class="img-fluid" src="{{ asset('storage/images/new/3.jpg') }}" alt="">
                    <div class="details">
                        <h2>Sportwear Up To 50% Off</h2>
                        <a href="{{ route('shop', ['category' => 'sportswear']) }}" class="text-uppercase btn">Shop now</a>
                    </div>
                </div>
            @endif
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

    <!-- Featured Products Section -->
   <!-- Featured Products Section -->
<section id="featured" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
        <h3>Our Featured Products</h3>
        <hr class="mx-auto">
        <p>Check out our best-selling products with fair prices.</p>
    </div>
    <div class="row mx-auto container-fluid">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="product text-center col-lg-3 col-md-4 col-12">
                    <img class="img-fluid mb-3" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    
                    @if(isset($product->category))
                        <span class="category-badge">{{ $product->category->name }}</span>
                    @endif
                    
                    <h5 class="p-name">{{ $product->name }}</h5>
                    <h4 class="p-price">${{ number_format($product->price, 2) }}</h4>
                    <button class="buy-btn">Buy Now</button>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center">
                <p>No products available at the moment.</p>
            </div>
        @endif
    </div>
</section>

    <!-- Banner Section -->
    <section id="banner" class="my-5 py-5">
        <div class="container">
            <h4>MID SEASON'S SALE</h4>
            <h1>Autumn Collection<br>UP TO 20% OFF</h1>
            <a href="{{ route('shop', ['sale' => 'true']) }}" class="text-uppercase btn">Shop Now</a>
        </div>
    </section>

    <!-- Specific Category Sections -->
    
    <!-- Protein Category Section -->
    <section id="category-protein" class="my-5">
        <div class="container text-center mt-5 py-5">
            <h3>Protein Products</h3>
            <hr class="mx-auto">
            <p>High-quality protein supplements to support your muscle growth and recovery.</p>
        </div>
        <div class="row mx-auto container-fluid">
            @php
                $proteinProducts = collect();
                if(isset($products)) {
                    foreach($products as $product) {
                        if(isset($product->category) && 
                           strtolower($product->category->name) == 'protein') {
                            $proteinProducts->push($product);
                        }
                    }
                }
            @endphp
            
            @if(count($proteinProducts) > 0)
                @foreach($proteinProducts as $product)
                    <div class="product text-center col-lg-3 col-md-4 col-12">
                        <img class="img-fluid mb-3" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        <div class="star">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        
                        <h5 class="p-name">{{ $product->name }}</h5>
                        <h4 class="p-price">${{ number_format($product->price, 2) }}</h4>
                        <button class="buy-btn">Buy Now</button>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p>No protein products available at the moment.</p>
                </div>
            @endif
        </div>
    </section>
    
    <!-- Powder Category Section -->
    <section id="category-powder" class="my-5">
        <div class="container text-center mt-5 py-5">
            <h3>Powder Products</h3>
            <hr class="mx-auto">
            <p>Premium quality powder supplements to enhance your workout and daily nutrition.</p>
        </div>
        <div class="row mx-auto container-fluid">
            @php
                $powderProducts = collect();
                if(isset($products)) {
                    foreach($products as $product) {
                        if(isset($product->category) && 
                           strtolower($product->category->name) == 'powder') {
                            $powderProducts->push($product);
                        }
                    }
                }
            @endphp
            
            @if(count($powderProducts) > 0)
                @foreach($powderProducts as $product)
                    <div class="product text-center col-lg-3 col-md-4 col-12">
                        <img class="img-fluid mb-3" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        <div class="star">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        
                        <h5 class="p-name">{{ $product->name }}</h5>
                        <h4 class="p-price">${{ number_format($product->price, 2) }}</h4>
                        <button class="buy-btn">Buy Now</button>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p>No powder products available at the moment.</p>
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
    
    <!-- Add-to-cart Script -->
    <script>
      // Grab all buttons with class "buy-btn"
      const buyButtons = document.querySelectorAll(".buy-btn");

      // For each "Buy Now" button, set up a click listener
      buyButtons.forEach((btn) => {
        btn.addEventListener("click", function (event) {
          // Prevent default action (which would be navigation for anchor tags)
          event.preventDefault();
          
          // The parent product card has .product
          const productCard = this.closest(".product");
          if (!productCard) return;

          // 1) Get product image (from <img> inside .product)
          const imgTag = productCard.querySelector("img");
          const image = imgTag ? imgTag.src : "";

          // 2) Get product name (from .p-name)
          const nameTag = productCard.querySelector(".p-name");
          const name = nameTag ? nameTag.textContent.trim() : "Untitled";

          // 3) Get product price (from .p-price) & parse out $
          const priceTag = productCard.querySelector(".p-price");
          let price = 0;
          if (priceTag) {
            price = parseFloat(priceTag.textContent.replace("$", "")) || 0;
          }

          // Default quantity is 1 since there's no quantity input here
          const quantity = 1;

          // 4) Load existing cart from localStorage
          let cart = JSON.parse(localStorage.getItem("cart")) || [];

          // 5) Create a product object
          const product = {
            name: name,
            price: price,
            quantity: quantity,
            image: image
          };

          // 6) Add product to cart array
          cart.push(product);

          // 7) Save updated cart back to localStorage
          localStorage.setItem("cart", JSON.stringify(cart));

          // 8) Alert user that product was added
          alert(name + " added to cart!");
        });
      });
    </script>
@endsection