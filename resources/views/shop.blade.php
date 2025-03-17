@extends('layouts.app')

@section('title', 'Shop')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <style>
        .product img {
            width: 100%;
            height: auto;
            box-sizing: border-box;
            object-fit: cover;
        }

        #featured > div.row.mx-auto.container > nav > ul > li.page-item.active > a {
            background-color: black;
        }

        #featured > div.row.mx-auto.container > nav > ul > li:nth-child(n):hover > a {
            background-color: coral;
            color: #fff;
        }

        .pagination a {
            color: #000;
        }
    </style>
@endsection

@section('navigation')
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
        <div class="container">
            <img src="{{ asset('img/logo1.png') }}" alt="" />
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
                        <i onclick="window.location.href='{{ route('cart.index') }}';" class="fal fa-shopping-bag"></i>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <section id="featured" class="my-5 py-5">
        <div class="container mt-5 py-5">
            <h2 class="font-weight-bold">Our Featured</h2>
            <hr />
            <p>
                Here you can check out our new products with fair price on
                rymo.
            </p>
        </div>
        <div class="row mx-auto container">
            @foreach($products as $product)
            <div
                onclick="window.location.href='{{ route('product.view', ['product' => $product->slug]) }}';"
                class="product col-lg-3 col-md-4 col-12 text-center"
            >
                <img class="mb-3 img-fluid" src="{{ asset($product->image) }}" alt="{{ $product->name }}" />
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

            @if(count($products) < 1)
            <!-- Fallback product if no products found in database -->
            <div class="product col-lg-3 col-md-4 col-12 text-center">
                <img class="mb-3 img-fluid" src="{{ asset('img/shop/1.jpg') }}" alt="" />
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

            <nav aria-label="...">
                <ul class="pagination mt-5">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item" aria-current="page">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('scripts')
    <!-- Popper & Bootstrap JS -->
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

    <!-- *** Add-to-cart SCRIPT *** -->
    <script>
      // Grab all buttons with class "buy-btn"
      const buyButtons = document.querySelectorAll(".buy-btn");

      // For each "Buy Now" button, set up a click listener
      buyButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
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

          // 8) (Optionally redirect) or just alert:
          alert(name + " added to cart!");
          // If you want immediate cart page redirect, uncomment:
          // window.location.href = "cart.html";
        });
      });
    </script>
@endsection