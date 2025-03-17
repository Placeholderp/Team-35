@extends('layouts.app')

@section('title', 'Product Details')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <style>
        .small-img-group {
            display: flex;
            justify-content: space-between;
        }
        .small-img-col {
            flex-basis: 24%;
            cursor: pointer;
        }
        .sproduct select {
            display: block;
            padding: 5px 10px;
        }
        .sproduct input {
            width: 50px;
            height: 40px;
            padding-left: 10px;
            font-size: 16px;
            margin-right: 10px;
        }
        .sproduct input:focus {
            outline: none;
        }
        .buy-btn {
            background: #fb774b;
            opacity: 1;
            transition: 0.3s all;
        }
    </style>
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
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('shop') }}">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blog') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
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

@section('content')
    <section class="container sproduct my-5 pt-5">
        <div class="row mt-5">
            <div class="col-lg-5 col-md-12 col-12">
                <img
                    class="img-fluid w-100 pb-1"
                    src="{{ asset('img/shop/1.jpg') }}"
                    id="MainImg"
                    alt="Men's Fashion T-Shirt"
                />
                <div class="small-img-group">
                    <div class="small-img-col">
                        <img
                            src="{{ asset('img/shop/1.jpg') }}"
                            width="100%"
                            class="small-img"
                            alt="Img1"
                        />
                    </div>
                    <div class="small-img-col">
                        <img
                            src="{{ asset('img/shop/24.jpg') }}"
                            width="100%"
                            class="small-img"
                            alt="Img2"
                        />
                    </div>
                    <div class="small-img-col">
                        <img
                            src="{{ asset('img/shop/25.jpg') }}"
                            width="100%"
                            class="small-img"
                            alt="Img3"
                        />
                    </div>
                    <div class="small-img-col">
                        <img
                            src="{{ asset('img/shop/26.jpg') }}"
                            width="100%"
                            class="small-img"
                            alt="Img4"
                        />
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-12">
                <h6>Home / T-Shirt</h6>
                <h3 class="py-4">Men's Fashion T-Shirt</h3>
                <h2 id="productPrice">$139.00</h2>
                <select class="my-3">
                    <option>Select Size</option>
                    <option>XL</option>
                    <option>XXL</option>
                    <option>Small</option>
                    <option>Large</option>
                </select>
                <!-- Quantity -->
                <input type="number" value="1" id="quantityInput" />
                <!-- Add to Cart / Buy Now button -->
                <button class="buy-btn" id="buyBtn">Add To Cart</button>

                <h4 class="mt-5 mb-4">Product Details</h4>
                <span>
                    The Gildan Ultra Cotton T-shirt is made from a substantial 6.0 oz.
                    per sq. yd. fabric constructed from 100% cotton, this classic fit
                    preshrunk jersey knit provides unmatched comfort with each wear.
                    Featuring a taped neck and shoulder, and a seamless double-needle
                    collar, and available in a range of colors, it offers it all in the
                    ultimate head-turning package.
                </span>
            </div>
        </div>
    </section>

    <section id="featured" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
            <h3>Related Products</h3>
            <hr class="mx-auto" />
        </div>
        <div class="row mx-auto container-fluid">
            <div class="product text-center col-lg-3 col-md-4 col-12">
                <img class="img-fluid mb-3" src="{{ asset('img/featured/1.jpg') }}" alt="Product1" />
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
                <img class="img-fluid mb-3" src="{{ asset('img/featured/2.jpg') }}" alt="Product2" />
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
                <img class="img-fluid mb-3" src="{{ asset('img/featured/3.jpg') }}" alt="Product3" />
                <div class="star">
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
                <img class="img-fluid mb-3" src="{{ asset('img/featured/4.jpg') }}" alt="Product4" />
                <div class="star">
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

    <script>
        // Make the main image switchable on thumbnail clicks:
        const MainImg = document.getElementById("MainImg");
        const smallImgs = document.getElementsByClassName("small-img");
        for (let i = 0; i < smallImgs.length; i++) {
            smallImgs[i].onclick = function () {
                MainImg.src = smallImgs[i].src;
            };
        }

        // *** ADD TO CART LOGIC WITH localStorage ***
        const buyBtn = document.getElementById("buyBtn");
        const quantityInput = document.getElementById("quantityInput");
        const productPriceText = document.getElementById("productPrice").textContent;
        // For simplicity, parse out the numeric price from something like "$139.00"
        const numericPrice = parseFloat(productPriceText.replace("$", "")) || 0;

        buyBtn.addEventListener("click", () => {
            // If your product name is dynamic, retrieve it accordingly
            const productName = "Men's Fashion T-Shirt"; 
            const productImage = MainImg.src; // or a default if you prefer
            const quantity = parseInt(quantityInput.value) || 1;

            // 1) Get existing cart from localStorage (or empty array)
            let cart = JSON.parse(localStorage.getItem("cart")) || [];

            // 2) Create a product object
            const product = {
                name: productName,
                price: numericPrice,
                quantity: quantity,
                image: productImage
            };

            // 3) Push into cart array
            cart.push(product);

            // 4) Save back to localStorage
            localStorage.setItem("cart", JSON.stringify(cart));

            // 5) Go to cart page
            window.location.href = "{{ route('cart') }}";
        });
    </script>
@endsection