@extends('layouts.app')

@section('title', 'Checkout')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* Orange Checkout Button Styling */
        .checkout-btn {
            display: block;
            width: 100%;
            padding: 12px 20px;
            background-color: #FF7700; /* Bright orange color */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .checkout-btn:hover {
            background-color: #E65C00; /* Darker orange on hover */
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .checkout-btn:active {
            transform: translateY(0);
        }
    </style>
@endsection

@section('navigation')
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('/images/team_logo.png') }}" alt="Aston35Fitness" class="img-fluid" style="max-height: 45px;">
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span><i id="bar" class="fas fa-bars"></i></span>
        </button>
        
        <!-- Navbar Menu Items -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ route('shop') }}">
                        <span>Shop</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('blog') ? 'active' : '' }}" href="{{ route('blog') }}">
                        <span>Blog</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        <span>About</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('calorie.calculator') ? 'active' : '' }}" href="{{ route('calorie.calculator') }}">
                        <span>Calorie Calculator</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                        <span>Contact Us</span>
                    </a>
                </li>
            </ul>
            
            <!-- Right Action Icons -->
            <ul class="navbar-nav ml-auto">
                <!-- Search Icon -->
                <li class="nav-item d-flex align-items-center">
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Search products..." id="search-input">
                        <i class="fal fa-search search-icon" id="search-icon"></i>
                    </div>
                </li>
                
                <!-- Profile Dropdown -->
                <li class="nav-item d-flex align-items-center ml-3">
                    @auth
                    <div class="profile">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" id="profile-icon">
                        @elseif(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile" id="profile-icon">
                        @else
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile" id="profile-icon">
                        @endif
                        <div class="profile-dropdown" id="profile-dropdown">
                            <div class="profile-dropdown-header">
                                <p class="profile-name">{{ Auth::user()->name }}</p>
                                <p class="profile-email">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="profile-dropdown-body">
                                <a href="{{ route('home') }}" class="profile-dropdown-item">
                                    <i class="fas fa-user"></i> My Account
                                </a>
                                <a href="{{ route('shop') }}" class="profile-dropdown-item">
                                    <i class="fas fa-shopping-bag"></i> Shop
                                </a>
                            </div>
                            <div class="profile-dropdown-footer">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="logout-button">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-primary ml-2">Register</a>
                    </div>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
/* Enhanced Navbar Styling */
.navbar {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    padding: 12px 0;
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.5rem;
    color: var(--primary-color);
}

.navbar-nav .nav-item {
    margin: 0 5px;
    position: relative;
}

.navbar-nav .nav-link {
    color: #333 !important;
    font-weight: 500;
    font-size: 15px;
    padding: 10px 15px !important;
    transition: all 0.3s ease;
    position: relative;
}

.navbar-nav .nav-link span {
    position: relative;
    display: inline-block;
}

.navbar-nav .nav-link span:after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -4px;
    left: 0;
    background-color: var(--primary-color);
    transition: width 0.3s ease;
}

.navbar-nav .nav-link:hover span:after,
.navbar-nav .nav-link.active span:after {
    width: 100%;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    color: var(--primary-color) !important;
}

/* Search Styling */
.search-container {
    position: relative;
    margin-right: 15px;
}

.search-input {
    width: 0;
    padding: 8px 12px 8px 30px;
    border: none;
    border-radius: 50px;
    font-size: 14px;
    background-color: transparent;
    transition: all 0.3s ease;
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    visibility: hidden;
}

.search-input.active {
    width: 200px;
    border: 1px solid #eee;
    opacity: 1;
    visibility: visible;
    background-color: #f9f9f9;
}

.search-icon {
    cursor: pointer;
    font-size: 16px;
    color: #555;
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.search-icon:hover {
    color: var(--primary-color);
}

/* Profile Dropdown */
.profile {
    position: relative;
    cursor: pointer;
}

.profile img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.profile img:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.profile-dropdown {
    position: absolute;
    top: 45px;
    right: 0;
    width: 240px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s ease;
    z-index: 1000;
    border: 1px solid rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.profile-dropdown.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.profile-dropdown-header {
    padding: 15px;
    border-bottom: 1px solid #f1f1f1;
    background-color: #f9f9f9;
}

.profile-dropdown-header .profile-name {
    margin: 0;
    font-weight: 600;
    font-size: 15px;
    color: #333;
}

.profile-dropdown-header .profile-email {
    margin: 5px 0 0 0;
    font-size: 12px;
    color: #777;
}

.profile-dropdown-body {
    padding: 10px 0;
}

.profile-dropdown-item {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: #444;
    font-size: 14px;
    transition: all 0.2s ease;
    text-decoration: none;
}

.profile-dropdown-item:hover {
    background-color: rgba(230, 92, 0, 0.05);
    color: var(--primary-color);
}

.profile-dropdown-item i {
    margin-right: 10px;
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.profile-dropdown-footer {
    padding: 10px 15px;
    border-top: 1px solid #f1f1f1;
}

.profile-dropdown-footer button {
    width: 100%;
    padding: 8px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
    color: #555;
    border: 1px solid #eee;
    border-radius: 5px;
    font-size: 14px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.profile-dropdown-footer button:hover {
    background-color: #f1f1f1;
    color: #e74c3c;
}

.profile-dropdown-footer button i {
    margin-right: 8px;
}

/* Mobile responsive */
@media (max-width: 991px) {
    .navbar-collapse {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: absolute;
        top: 70px;
        left: 10px;
        right: 10px;
        z-index: 1000;
    }
    
    .navbar-nav {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .navbar-nav .nav-link {
        padding: 12px 0 !important;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .navbar-nav .nav-item:last-child .nav-link {
        border-bottom: none;
    }
    
    .navbar .navbar-toggler {
        padding: 5px;
        outline: none;
    }
    
    .navbar .navbar-toggler:focus {
        box-shadow: none;
    }
    
    #bar {
        font-size: 22px;
        padding: 7px;
        border-radius: 4px;
        color: var(--primary-color);
    }
    
    .search-container {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .search-input.active {
        position: relative;
        width: 100%;
        transform: none;
        margin-top: 15px;
    }
    
    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .profile {
        margin-bottom: 15px;
    }
    
    .profile-dropdown {
        position: static;
        width: 100%;
        margin-top: 15px;
        box-shadow: none;
        border: 1px solid #eee;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile dropdown toggle
    const profileIcon = document.getElementById('profile-icon');
    const profileDropdown = document.getElementById('profile-dropdown');
    
    if (profileIcon && profileDropdown) {
        profileIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target) && e.target !== profileIcon) {
                profileDropdown.classList.remove('active');
            }
        });
    }
    
    // Search functionality
    const searchIcon = document.getElementById('search-icon');
    const searchInput = document.getElementById('search-input');
    
    if (searchIcon && searchInput) {
        searchIcon.addEventListener('click', function() {
            searchInput.classList.toggle('active');
            if (searchInput.classList.contains('active')) {
                searchInput.focus();
            }
        });
        
        // Close search when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target !== searchIcon && e.target !== searchInput) {
                searchInput.classList.remove('active');
            }
        });
    }
});
</script>
@endsection

@section('content')
<section id="checkout" class="container pt-5 mt-5">
    <h2 class="font-weight-bold pt-5">Checkout</h2>
    <hr>
    
    <div class="row mt-5">
        <!-- Left Column - Customer Details -->
        <div class="col-lg-7">
            <h3>Billing Details</h3>
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                <div class="row">
                    <!-- First Name -->
                    <div class="col-md-6 mb-3">
                        <label for="firstName">First name</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" 
                               value="{{ $customer->first_name ?? Auth::user()->first_name ?? '' }}" required>
                    </div>

                    <!-- Last Name Field -->
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" 
                               value="{{ $customer->last_name ?? Auth::user()->last_name ?? '' }}" required>
                    </div>

                    <!-- Email Field -->
                    <div class="col-md-12 mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ Auth::user()->email ?? '' }}" required>
                    </div>

                    <!-- Address Line 1 Field -->
                    <div class="col-md-12 mb-3">
                        <label for="address">Address Line 1</label>
                        <input type="text" class="form-control" id="address" name="address" 
                               value="{{ $billingAddress->address1 ?? '' }}" required>
                    </div>

                    <!-- Address Line 2 Field -->
                    <div class="col-md-12 mb-3">
                        <label for="address2">Address Line 2 (Optional)</label>
                        <input type="text" class="form-control" id="address2" name="address2" 
                               value="{{ $billingAddress->address2 ?? '' }}">
                    </div>

                    <!-- City Field -->
                    <div class="col-md-6 mb-3">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" 
                               value="{{ $billingAddress->city ?? '' }}" required>
                    </div>

                    <!-- ZIP/Postal Code Field -->
                    <div class="col-md-6 mb-3">
                        <label for="zip">Postal Code</label>
                        <input type="text" class="form-control" id="zip" name="zip" 
                               value="{{ $billingAddress->zipcode ?? '' }}" required>
                    </div>

                    <!-- State/Province Field -->
                    <div class="col-md-6 mb-3">
                        <label for="state">State/Province</label>
                        <input type="text" class="form-control" id="state" name="state" 
                               value="{{ $billingAddress->state ?? '' }}">
                    </div>

                    <!-- Country Field -->
                    <div class="col-md-6 mb-3">
                        <label for="country">Country</label>
                        <select class="form-select form-control" id="country" name="country" required>
                            <option value="UK" {{ isset($billingAddress) && $billingAddress->country_code == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                            <!-- Add more countries as needed -->
                        </select>
                    </div>
                    
                    <!-- Order Notes -->
                    <div class="col-md-12 mb-3">
                        <label for="notes">Order Notes (optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <h3 class="mb-3">Payment</h3>
                
                <div class="payment-methods mb-4">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="creditCard" value="credit_card" checked>
                        <label class="form-check-label" for="creditCard">
                            Credit Card
                        </label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                        <label class="form-check-label" for="paypal">
                            PayPal
                        </label>
                    </div>
                    
                    <div id="credit-card-details" class="mt-3">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="cc-name">Name on card</label>
                                <input type="text" class="form-control" id="cc-name" name="cc_name">
                                <small class="text-muted">Full name as displayed on card</small>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="cc-number">Credit card number</label>
                                <input type="text" class="form-control" id="cc-number" name="cc_number">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="cc-expiration">Expiration</label>
                                <input type="text" class="form-control" id="cc-expiration" name="cc_expiration" placeholder="MM/YY">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="cc-cvv">CVV</label>
                                <input type="text" class="form-control" id="cc-cvv" name="cc_cvv">
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <button class="checkout-btn" type="submit">
                    PLACE ORDER
                </button>
            </form>
        </div>
        
        <!-- Right Column - Order Summary -->
        <div class="col-lg-5">
            <div class="order-summary p-4 bg-light rounded">
                <h3 class="mb-3">Order Summary</h3>
                
                <div id="cart-items">
                    <!-- Cart items will be dynamically inserted here -->
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-2">
                    <h6>Subtotal</h6>
                    <span id="summary-subtotal">$0.00</span>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <h6>Shipping</h6>
                    <span id="summary-shipping">$0.00</span>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-4">
                    <h5>Total</h5>
                    <span id="summary-total" class="font-weight-bold">$0.00</span>
                </div>
            </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Load cart items and order summary from localStorage
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const orderSummary = JSON.parse(localStorage.getItem('orderSummary')) || {
                subtotal: '0.00',
                shipping: '0.00',
                total: '0.00'
            };
            
            // Update summary values
            document.getElementById('summary-subtotal').textContent = `${orderSummary.subtotal}`;
            document.getElementById('summary-shipping').textContent = `${orderSummary.shipping}`;
            document.getElementById('summary-total').textContent = `${orderSummary.total}`;
            
            // Render cart items in the summary
            const cartItemsContainer = document.getElementById('cart-items');
            
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<p class="text-center">Your cart is empty</p>';
            } else {
                cart.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'cart-item d-flex justify-content-between align-items-center mb-3';
                    itemElement.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="cart-item-img mr-3">
                                <img src="${item.image}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover;">
                            </div>
                            <div>
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">Qty: ${item.quantity}</small>
                            </div>
                        </div>
                        <div>
                            <span>${(item.price * item.quantity).toFixed(2)}</span>
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemElement);
                });
            }
            
            // Toggle payment method details
            const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
            const creditCardDetails = document.getElementById('credit-card-details');
            
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'credit_card') {
                        creditCardDetails.style.display = 'block';
                    } else {
                        creditCardDetails.style.display = 'none';
                    }
                });
            });
            
            // Credit card number formatting (4444 4444 4444 4444)
            const ccNumberInput = document.getElementById('cc-number');
            ccNumberInput.addEventListener('input', function(e) {
                // Remove any non-digit characters
                let value = this.value.replace(/\D/g, '');
                
                // Limit to 16 digits
                if (value.length > 16) {
                    value = value.substring(0, 16);
                }
                
                // Add a space after every 4 digits
                let formattedValue = '';
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
                
                // Update the input value
                this.value = formattedValue;
            });
            
            ccNumberInput.addEventListener('blur', function() {
                // Check if card number is exactly 16 digits
                const digitsOnly = this.value.replace(/\D/g, '');
                if (digitsOnly.length !== 16) {
                    this.setCustomValidity('Credit card number must be 16 digits');
                    this.reportValidity();
                } else {
                    this.setCustomValidity('');
                }
            });
            
            // Expiration date formatting (MM/YY)
            const expirationInput = document.getElementById('cc-expiration');
            expirationInput.addEventListener('input', function(e) {
                // Remove any non-digit characters
                let value = this.value.replace(/\D/g, '');
                
                // Format as MM/YY
                if (value.length > 0) {
                    // Handle month (limit to 01-12)
                    if (value.length === 1 && parseInt(value) > 1) {
                        value = '0' + value;
                    } else if (value.length === 2 && parseInt(value) > 12) {
                        value = '12';
                    }
                    
                    // Add slash after month
                    if (value.length > 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2);
                    }
                    
                    // Limit to MM/YY format (max 5 chars including slash)
                    if (value.length > 5) {
                        value = value.substring(0, 5);
                    }
                }
                
                // Update the input value
                this.value = value;
            });
            
            // CVV validation - exactly 3 digits
            const cvvInput = document.getElementById('cc-cvv');
            cvvInput.addEventListener('input', function() {
                // Remove any non-digit characters
                let value = this.value.replace(/\D/g, '');
                
                // Limit to 3 digits
                if (value.length > 3) {
                    value = value.substring(0, 3);
                }
                
                // Update the input value
                this.value = value;
            });
            
            // Handle form submission
            const checkoutForm = document.getElementById('checkout-form');
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                // Validate credit card fields if credit card is selected
                if (document.getElementById('creditCard').checked) {
                    const ccNumber = document.getElementById('cc-number');
                    const ccCvv = document.getElementById('cc-cvv');
                    const ccExpiration = document.getElementById('cc-expiration');
                    
                    // Check card number
                    const cardDigits = ccNumber.value.replace(/\D/g, '');
                    if (cardDigits.length !== 16) {
                        ccNumber.setCustomValidity('Credit card number must be 16 digits');
                        ccNumber.reportValidity();
                        return;
                    }
                    
                    // Check CVV
                    if (ccCvv.value.length !== 3) {
                        ccCvv.setCustomValidity('CVV must be 3 digits');
                        ccCvv.reportValidity();
                        return;
                    }
                    
                    // Check expiration date format
                    if (!/^\d{2}\/\d{2}$/.test(ccExpiration.value)) {
                        ccExpiration.setCustomValidity('Please enter a valid expiration date (MM/YY)');
                        ccExpiration.reportValidity();
                        return;
                    }
                }
                
                // If form is valid, add localStorage cart items to hidden fields
                if (checkoutForm.checkValidity()) {
                    // Get cart from localStorage
                    const cart = JSON.parse(localStorage.getItem('cart')) || [];
                    
                    // Create a hidden input to send the cart data to the server
                    const cartInput = document.createElement('input');
                    cartInput.type = 'hidden';
                    cartInput.name = 'cart_data';
                    cartInput.value = JSON.stringify(cart);
                    
                    // Add this hidden input to the form
                    checkoutForm.appendChild(cartInput);
                    
                    // Now submit the form
                    checkoutForm.submit();
                } else {
                    checkoutForm.reportValidity();
                }
            });
        });
    </script>
@endsection