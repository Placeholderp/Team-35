@extends('layouts.app')

@section('title', 'Cart')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('content')
    <!-- CART HEADER -->
    <section id="cart" class="container pt-5">
        <h2 class="font-weight-bold pt-5 mt-5">Shopping Cart</h2>
        <hr />
    </section>

    <!-- CART TABLE -->
    <section id="cart-container" class="container my-5">
        <table width="100%">
            <thead>
                <tr>
                    <td>Remove</td>
                    <td>Image</td>
                    <td>Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                </tr>
            </thead>
            <!-- We'll build this table body dynamically from localStorage: -->
            <tbody id="cart-body">
                <!-- Rows will be inserted here by JS -->
            </tbody>
        </table>
    </section>

    <!-- CART BOTTOM (COUPON + TOTAL) -->
    <section id="cart-bottom" class="container">
        <div class="row">
            <div class="coupon col-lg-6 col-md-6 col-12 mb-4">
                <div>
                    <h5>COUPON</h5>
                    <p>Enter your coupon code if you have one.</p>
                    <input type="text" placeholder="Coupon Code" />
                    <button>APPLY COUPON</button>
                </div>
            </div>
            <div class="total col-lg-6 col-md-6 col-12">
                <div>
                    <h5>CART TOTAL</h5>

                    <!-- Subtotal Row -->
                    <div class="d-flex justify-content-between">
                        <h6>Subtotal</h6>
                        <p id="subtotal-amount">$0.00</p>
                    </div>

                    <!-- Shipping Row (hard-coded at $40 for example) -->
                    <div class="d-flex justify-content-between">
                        <h6>Shipping</h6>
                        <p id="shipping-amount">$40.00</p>
                    </div>
                    <hr class="second-hr" />

                    <!-- Grand Total Row -->
                    <div class="d-flex justify-content-between">
                        <h6>Total</h6>
                        <p id="grandtotal-amount">$40.00</p>
                    </div>
                    <button class="ml-auto">PROCEED TO CHECKOUT</button>
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
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
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
                        <i class="fal fa-shopping-bag active"></i>
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

    <script>
        const cartBody = document.getElementById("cart-body");
        const subtotalEl = document.getElementById("subtotal-amount");
        const shippingEl = document.getElementById("shipping-amount");
        const grandTotalEl = document.getElementById("grandtotal-amount");

        // Hard-code shipping cost for example
        const shippingCost = 40;

        // 1) Load cart items from localStorage
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        // 2) Render table rows for each item in cart
        function renderCart() {
            cartBody.innerHTML = ""; // clear out old rows

            cart.forEach((item, index) => {
                // Create a new row
                const row = document.createElement("tr");

                // row HTML
                row.innerHTML = `
                    <!-- Remove button (trash icon) -->
                    <td>
                        <a href="javascript:void(0);" data-index="${index}" class="remove-btn">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                    <!-- Image -->
                    <td>
                        <img src="${item.image}" alt="Product" style="width:60px;" />
                    </td>
                    <!-- Product Name -->
                    <td>
                        <h5>${item.name}</h5>
                    </td>
                    <!-- Price -->
                    <td>
                        <h5>$${item.price.toFixed(2)}</h5>
                    </td>
                    <!-- Quantity -->
                    <td>
                        <input 
                            class="w-25 pl-1 quantity-input" 
                            type="number" 
                            value="${item.quantity}" 
                            min="1"
                            data-index="${index}" 
                        />
                    </td>
                    <!-- Total (price * quantity) -->
                    <td>
                        <h5 class="row-total">$${(item.price * item.quantity).toFixed(2)}</h5>
                    </td>
                `;

                cartBody.appendChild(row);
            });
        }

        // 3) Calculate Subtotal & Grand Total
        function updateTotals() {
            let subtotal = 0;
            cart.forEach((item) => {
                subtotal += item.price * item.quantity;
            });

            subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
            const grandTotal = subtotal + shippingCost;
            grandTotalEl.textContent = `$${grandTotal.toFixed(2)}`;
        }

        // 4) Setup event listeners for quantity changes & remove
        function setupEventListeners() {
            // *** Remove Buttons ***
            const removeButtons = document.querySelectorAll(".remove-btn");
            removeButtons.forEach((btn) => {
                btn.addEventListener("click", function () {
                    const index = parseInt(this.getAttribute("data-index"));
                    // Remove item from cart array
                    cart.splice(index, 1);
                    // Save updated cart to localStorage
                    localStorage.setItem("cart", JSON.stringify(cart));
                    // Re-render + recalc
                    renderCart();
                    setupEventListeners();
                    updateTotals();
                });
            });

            // *** Quantity Inputs ***
            const quantityInputs = document.querySelectorAll(".quantity-input");
            quantityInputs.forEach((input) => {
                input.addEventListener("change", function () {
                    const index = parseInt(this.getAttribute("data-index"));
                    let newQty = parseInt(this.value) || 1;
                    // Update cart array
                    cart[index].quantity = newQty;
                    // Save back to localStorage
                    localStorage.setItem("cart", JSON.stringify(cart));
                    // Re-render + recalc
                    renderCart();
                    setupEventListeners();
                    updateTotals();
                });
            });
        }

        // INITIALIZE PAGE
        renderCart();
        setupEventListeners();
        updateTotals();
    </script>
@endsection