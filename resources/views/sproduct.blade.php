@extends('layouts.app')

@section('title', 'Product Details')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Product detail page styles */
        .product-detail-container {
            margin-top: 120px;
            padding-bottom: 50px;
        }
        
        .breadcrumb-section {
            background-color: #f8f9fa;
            padding: 15px 0;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .breadcrumb-item {
            font-size: 14px;
            color: #666;
        }
        
        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .product-gallery {
            position: relative;
            margin-bottom: 30px;
        }
        
        .main-image {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            position: relative;
        }
        
        .main-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .main-image:hover img {
            transform: scale(1.02);
        }
        
        .thumbnail-container {
            display: flex;
            gap: 10px;
        }
        
        .thumbnail {
            width: 80px;
            height: 80px;
            border-radius: 5px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            opacity: 0.7;
        }
        
        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .thumbnail.active, .thumbnail:hover {
            border-color: var(--primary-color);
            opacity: 1;
        }
        
        .product-info {
            padding-left: 20px;
        }
        
        .product-category {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            display: inline-block;
        }
        
        .product-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }
        
        .product-rating {
            margin-bottom: 15px;
        }
        
        .product-rating i {
            color: #ffc107;
            font-size: 16px;
        }
        
        .review-count {
            font-size: 14px;
            color: #666;
            margin-left: 10px;
        }
        
        .product-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 1.2rem;
            margin-right: 10px;
        }
        
        .discount-badge {
            background-color: #28a745;
            color: white;
            font-size: 0.8rem;
            padding: 3px 8px;
            border-radius: 4px;
            margin-left: 10px;
            font-weight: 600;
        }
        
        .product-description {
            font-size: 15px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 25px;
        }
        
        .product-form {
            margin-bottom: 25px;
        }
        
        .size-selector {
            margin-bottom: 20px;
        }
        
        .size-selector label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .size-selector select {
            width: 100%;
            max-width: 200px;
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 15px;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .quantity-selector {
            margin-bottom: 20px;
        }
        
        .quantity-selector label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .quantity-input-group {
            display: flex;
            max-width: 150px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .quantity-btn {
            background-color: #f1f1f1;
            border: none;
            width: 40px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .quantity-btn:hover {
            background-color: #e1e1e1;
        }
        
        .quantity-input {
            border: none;
            text-align: center;
            width: 70px;
            font-size: 16px;
            padding: 8px 0;
        }
        
        .quantity-input:focus {
            outline: none;
        }
        
        .stock-status {
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .in-stock {
            color: #28a745;
            font-weight: 600;
        }
        
        .low-stock {
            color: #ffc107;
            font-weight: 600;
        }
        
        .out-of-stock {
            color: #dc3545;
            font-weight: 600;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .buy-btn {
            flex: 1;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            max-width: 220px;
        }
        
        .buy-btn:hover {
            background-color: #e65202;
        }
        
        .buy-btn i {
            font-size: 16px;
        }
        
        .btn-disabled {
            opacity: 0.7;
            pointer-events: none;
            background-color: #999 !important;
        }
        
        .wishlist-btn {
            width: 46px;
            height: 46px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .wishlist-btn:hover {
            border-color: #e65202;
            color: #e65202;
        }
        
        .product-details-tabs {
            margin: 40px 0;
        }
        
        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #666;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 0;
            position: relative;
        }
        
        .nav-tabs .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: transparent;
        }
        
        .nav-tabs .nav-link.active::after,
        .nav-tabs .nav-link:hover::after {
            width: 100%;
        }
        
        .tab-content {
            padding: 20px 0;
        }
        
        .tab-pane h4 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }
        
        .tab-pane p {
            font-size: 15px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 15px;
        }
        
        .product-meta {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .meta-label {
            font-weight: 600;
            width: 120px;
            color: #333;
        }
        
        .meta-value {
            color: #666;
        }
        
        .related-products {
            margin-top: 60px;
        }
        
        .related-products h3 {
            position: relative;
            display: inline-block;
            font-weight: 700;
            margin-bottom: 30px;
        }
        
        .related-products h3:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 3px;
            background-color: var(--primary-color);
            bottom: -8px;
            left: 25%;
        }
        
        /* Benefits section */
        .product-benefits {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin-top: 40px;
        }
        
        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .benefit-icon {
            font-size: 24px;
            color: var(--primary-color);
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .benefit-content h5 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .benefit-content p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .product-info {
                padding-left: 0;
                margin-top: 30px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .buy-btn {
                max-width: 100%;
            }
            
            .wishlist-btn {
                width: 100%;
                margin-top: 10px;
            }
            
            .thumbnail {
                width: 60px;
                height: 60px;
            }
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
                
                <!-- Cart Icon -->
                <li class="nav-item d-flex align-items-center cart-icon-container">
                    <a href="{{ route('cart.index') }}" class="cart-icon">
                        <i class="fal fa-shopping-bag"></i>
                        <span class="cart-count" id="cart-count">0</span>
                    </a>
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
                                <!-- Using dashboard route instead of profile.show -->
                                <a href="{{ route('home') }}" class="profile-dropdown-item">
                                    <i class="fas fa-user"></i> My Account
                                </a>
                                <!-- Using shop route instead of profile.settings -->
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

/* Cart Icon Styling */
.cart-icon-container {
    margin-right: 15px;
    position: relative;
}

.cart-icon {
    position: relative;
    display: inline-block;
}

.cart-icon i {
    font-size: 18px;
    color: #555;
    transition: all 0.3s ease;
}

.cart-icon:hover i {
    color: var(--primary-color);
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: var(--primary-color);
    color: white;
    font-size: 10px;
    font-weight: 600;
    width: 18px;
    height: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

/* Enhanced Profile Dropdown */
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
    
    .cart-icon-container {
        margin-right: 0;
        margin-bottom: 15px;
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
    
    // Load cart count from localStorage
    const updateCartCount = () => {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = cart.length;
            if (cart.length > 0) {
                cartCountElement.style.display = 'flex';
            } else {
                cartCountElement.style.display = 'none';
            }
        }
    };
    
    updateCartCount();
    
    // Product image gallery functionality
    const mainImg = document.getElementById('main-product-img');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    if (mainImg && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumbnail
                this.classList.add('active');
                
                // Update main image src
                const imgSrc = this.querySelector('img').getAttribute('src');
                mainImg.setAttribute('src', imgSrc);
            });
        });
    }
    
    // Quantity selector functionality - fixed version
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decrease-quantity');
    const increaseBtn = document.getElementById('increase-quantity');
    
    if (quantityInput && decreaseBtn && increaseBtn) {
        // Decrement button
        decreaseBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default button behavior
            let currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        // Increment button
        increaseBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default button behavior
            let currentValue = parseInt(quantityInput.value) || 1;
            let maxValue = parseInt(quantityInput.getAttribute('max')) || 999;
            
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });
        
        // Direct input validation
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            let minValue = parseInt(this.getAttribute('min')) || 1;
            let maxValue = parseInt(this.getAttribute('max')) || 999;
            
            if (isNaN(value) || value < minValue) {
                this.value = minValue;
            } else if (value > maxValue) {
                this.value = maxValue;
            }
        });
        
        // Prevent non-numeric input
        quantityInput.addEventListener('keypress', function(e) {
            if (!/^\d*$/.test(e.key)) {
                e.preventDefault();
            }
        });
    } else {
        console.log('Quantity selectors not found:', {
            input: !!quantityInput,
            decreaseBtn: !!decreaseBtn,
            increaseBtn: !!increaseBtn
        });
    }
    
    // Add to cart functionality
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            // Skip if button is disabled
            if (this.disabled || this.classList.contains('btn-disabled')) {
                return;
            }
            
            // Get product data
            const productId = this.getAttribute('data-product-id');
            const productName = document.querySelector('.product-title').textContent;
            const productImage = mainImg.getAttribute('src');
            const productPriceText = document.querySelector('.product-price').textContent;
            const productPrice = parseFloat(productPriceText.replace(/[^0-9.]/g, ''));
            const quantity = parseInt(quantityInput.value) || 1;
            
            // Get selected size if available
            const sizeSelect = document.getElementById('size-select');
            const selectedSize = sizeSelect ? sizeSelect.value : null;
            
            // Create product object
            const product = {
                id: productId,
                name: productName,
                price: productPrice,
                quantity: quantity,
                size: selectedSize,
                image: productImage
            };
            
            // Add to cart
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.push(product);
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Update cart count
            updateCartCount();
            
            // Show success message
            alert(productName + ' added to cart!');
        });
    }
    
    // Tabs functionality
    const tabLinks = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
    const tabContents = document.querySelectorAll('.tab-pane');
    
    if (tabLinks.length > 0 && tabContents.length > 0) {
        tabLinks.forEach(tabLink => {
            tabLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs and contents
                tabLinks.forEach(link => link.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active', 'show'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show corresponding content
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.classList.add('active', 'show');
                }
            });
        });
    }
});
</script>
@endsection

@section('content')
<div class="container product-detail-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                    @if(isset($product->category))
                    <li class="breadcrumb-item"><a href="{{ route('shop', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Product Gallery -->
        <div class="col-md-6">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" id="main-product-img">
                </div>
                
                <div class="thumbnail-container">
                    <div class="thumbnail active">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                    </div>
                    
                    @if(isset($product->gallery) && count($product->gallery) > 0)
                        @foreach($product->gallery as $image)
                        <div class="thumbnail">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->title }}">
                        </div>
                        @endforeach
                    @else
                        <!-- Placeholder thumbnails if no gallery images -->
                        <div class="thumbnail">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                        </div>
                        <div class="thumbnail">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                        </div>
                        <div class="thumbnail">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-md-6">
            <div class="product-info">
                @if(isset($product->category))
                <span class="product-category">{{ $product->category->name }}</span>
                @endif
                
                <h1 class="product-title">{{ $product->title }}</h1>
                
                <div class="product-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span class="review-count">(24 reviews)</span>
                </div>
                
                <div class="product-price">
                    @if(isset($product->sale_price) && $product->sale_price < $product->price)
                        <span class="original-price">${{ number_format($product->price, 2) }}</span>
                        ${{ number_format($product->sale_price, 2) }}
                        @php
                            $discountPercentage = round((($product->price - $product->sale_price) / $product->price) * 100);
                        @endphp
                        <span class="discount-badge">{{ $discountPercentage }}% OFF</span>
                    @else
                        ${{ number_format($product->price, 2) }}
                    @endif
                </div>
                
                <p class="product-description">
                    {{ $product->description }}
                </p>
                
                <div class="product-form">
                    @if(isset($product->sizes) && count($product->sizes) > 0)
                    <div class="size-selector">
                        <label for="size-select">Select Size</label>
                        <select id="size-select" class="form-control">
                            <option value="">Select Size</option>
                            @foreach($product->sizes as $size)
                            <option value="{{ $size }}">{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="quantity-selector">
                        <label for="quantity">Quantity</label>
                        <div class="quantity-input-group">
                            <button type="button" id="decrease-quantity" class="quantity-btn">-</button>
                            <input type="number" id="quantity" class="quantity-input" value="1" min="1" 
                                   @if(isset($product->track_inventory) && $product->track_inventory)
                                   max="{{ $product->quantity }}"
                                   @endif>
                            <button type="button" id="increase-quantity" class="quantity-btn">+</button>
                        </div>
                    </div>
                    
                    @if(isset($product->track_inventory) && $product->track_inventory)
                    <div class="stock-status">
                        @if($product->quantity <= 0)
                            <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                        @elseif($product->quantity <= $product->reorder_level)
                            <span class="low-stock"><i class="fas fa-exclamation-triangle"></i> Only {{ $product->quantity }} left in stock</span>
                        @else
                            <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock</span>
                        @endif
                    </div>
                    @endif
                    
                    <div class="action-buttons">
                        @if(isset($product->track_inventory) && $product->track_inventory && $product->quantity <= 0)
                            <button class="buy-btn btn-disabled" disabled><i class="fas fa-shopping-cart"></i> Sold Out</button>
                        @else
                            <button id="add-to-cart-btn" class="buy-btn" data-product-id="{{ $product->id }}">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        @endif
                        
                        <button class="wishlist-btn">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                
                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">SKU:</span>
                        <span class="meta-value">{{ $product->sku ?? 'P-' . $product->id }}</span>
                    </div>
                    
                    @if(isset($product->category))
                    <div class="meta-item">
                        <span class="meta-label">Category:</span>
                        <span class="meta-value">{{ $product->category->name }}</span>
                    </div>
                    @endif
                    
                    @if(isset($product->tags) && count($product->tags) > 0)
                    <div class="meta-item">
                        <span class="meta-label">Tags:</span>
                        <span class="meta-value">
                            @foreach($product->tags as $tag)
                                <a href="{{ route('shop', ['tag' => $tag->slug]) }}" class="product-tag">{{ $tag->name }}</a>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Tabs -->
    <div class="product-details-tabs">
        <ul class="nav nav-tabs" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="description-tab" data-bs-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Description</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="additional-info-tab" data-bs-toggle="tab" href="#additional-info" role="tab" aria-controls="additional-info" aria-selected="false">Additional Information</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews (24)</a>
            </li>
        </ul>
        <div class="tab-content" id="productTabsContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                <h4>Product Description</h4>
                <p>{{ $product->description }}</p>
                
                @if(isset($product->long_description))
                <p>{{ $product->long_description }}</p>
                @else
                <p>Our premium fitness supplements are crafted with the highest quality ingredients to support your fitness goals. Whether you're looking to build muscle, improve endurance, or support recovery, this product is designed to help you achieve optimal results.</p>
                <p>All our products undergo rigorous testing to ensure purity, potency, and effectiveness. We're committed to providing you with supplements that are free from harmful additives and fillers, focusing instead on research-backed ingredients that deliver real results.</p>
                @endif
                
                <!-- Key Benefits -->
                <h4>Key Benefits</h4>
                <ul>
                    <li>Supports muscle growth and recovery</li>
                    <li>Enhances energy and performance</li>
                    <li>Contains high-quality, research-backed ingredients</li>
                    <li>Free from artificial colors and preservatives</li>
                    <li>Manufactured in a GMP-certified facility</li>
                </ul>
                
                <!-- How to Use -->
                <h4>How to Use</h4>
                <p>For best results, take one serving daily with water or your favorite beverage. Ideal timing depends on your specific goals - before workouts to boost energy, after workouts to support recovery, or anytime throughout the day to meet your nutritional needs.</p>
            </div>
            <div class="tab-pane fade" id="additional-info" role="tabpanel" aria-labelledby="additional-info-tab">
                <h4>Additional Information</h4>
                
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Weight</th>
                            <td>{{ $product->weight ?? '500' }} g</td>
                        </tr>
                        <tr>
                            <th>Dimensions</th>
                            <td>{{ $product->dimensions ?? '10 × 5 × 5' }} cm</td>
                        </tr>
                        @if(isset($product->sizes) && count($product->sizes) > 0)
                        <tr>
                            <th>Size</th>
                            <td>{{ implode(', ', $product->sizes) }}</td>
                        </tr>
                        @endif
                        @if(isset($product->flavors) && count($product->flavors) > 0)
                        <tr>
                            <th>Flavors</th>
                            <td>{{ implode(', ', $product->flavors) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Ingredients</th>
                            <td>{{ $product->ingredients ?? 'Premium quality ingredients - see product label for details' }}</td>
                        </tr>
                        <tr>
                            <th>Allergens</th>
                            <td>{{ $product->allergens ?? 'May contain milk, soy. Produced in a facility that also processes eggs, tree nuts, peanuts, fish and shellfish' }}</td>
                        </tr>
                        <tr>
                            <th>Storage</th>
                            <td>Store in a cool, dry place away from direct sunlight</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <h4>Customer Reviews</h4>
                
                <div class="reviews-summary mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <div class="overall-rating mb-3">
                                <h2>4.5</h2>
                                <div class="star-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <p>Based on 24 reviews</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="rating-breakdown">
                                <div class="rating-item d-flex align-items-center mb-2">
                                    <span class="rating-label">5 stars</span>
                                    <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div>
                                    </div>
                                    <span class="rating-count">18</span>
                                </div>
                                <div class="rating-item d-flex align-items-center mb-2">
                                    <span class="rating-label">4 stars</span>
                                    <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 15%"></div>
                                    </div>
                                    <span class="rating-count">4</span>
                                </div>
                                <div class="rating-item d-flex align-items-center mb-2">
                                    <span class="rating-label">3 stars</span>
                                    <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 8%"></div>
                                    </div>
                                    <span class="rating-count">2</span>
                                </div>
                                <div class="rating-item d-flex align-items-center mb-2">
                                    <span class="rating-label">2 stars</span>
                                    <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <span class="rating-count">0</span>
                                </div>
                                <div class="rating-item d-flex align-items-center">
                                    <span class="rating-label">1 star</span>
                                    <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <span class="rating-count">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sample Reviews -->
                <div class="reviews-list">
                    <div class="review-item p-4 mb-4 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="reviewer-info">
                                <h5 class="mb-0">John D.</h5>
                                <span class="text-muted small">Verified Buyer</span>
                            </div>
                            <div class="review-date text-muted small">July 15, 2023</div>
                        </div>
                        <div class="review-rating mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <h6 class="review-title font-weight-bold">Great product, amazing results!</h6>
                        <p class="review-text mb-0">I've been using this product for about a month now and have seen significant improvements in my recovery time. The taste is great and it mixes well. Highly recommend for anyone serious about their fitness journey.</p>
                    </div>
                    
                    <div class="review-item p-4 mb-4 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="reviewer-info">
                                <h5 class="mb-0">Sarah M.</h5>
                                <span class="text-muted small">Verified Buyer</span>
                            </div>
                            <div class="review-date text-muted small">June 28, 2023</div>
                        </div>
                        <div class="review-rating mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="far fa-star text-warning"></i>
                        </div>
                        <h6 class="review-title font-weight-bold">Good quality but could improve the flavor</h6>
                        <p class="review-text mb-0">The product quality is excellent and I've definitely noticed improvements in my workout performance. The only reason I'm giving 4 stars instead of 5 is that the flavor could be better. Otherwise, a solid product!</p>
                    </div>
                    
                    <div class="review-item p-4 mb-4 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="reviewer-info">
                                <h5 class="mb-0">Michael R.</h5>
                                <span class="text-muted small">Verified Buyer</span>
                            </div>
                            <div class="review-date text-muted small">June 10, 2023</div>
                        </div>
                        <div class="review-rating mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <h6 class="review-title font-weight-bold">Best supplement I've tried</h6>
                        <p class="review-text mb-0">After trying numerous supplements, this has by far been the most effective for me. I've noticed significant improvements in my strength and endurance since starting. The price point is also reasonable compared to similar products on the market.</p>
                    </div>
                </div>
                
                <!-- Write a Review Form -->
                <div class="write-review mt-5">
                    <h4>Write a Review</h4>
                    <form>
                        <div class="form-group mb-3">
                            <label for="reviewRating">Your Rating</label>
                            <div class="rating-input">
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="reviewTitle">Review Title</label>
                            <input type="text" class="form-control" id="reviewTitle" placeholder="Give your review a title">
                        </div>
                        <div class="form-group mb-3">
                            <label for="reviewBody">Your Review</label>
                            <textarea class="form-control" id="reviewBody" rows="4" placeholder="Write your review here"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Benefits -->
    <div class="product-benefits">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="benefit-content">
                        <h5>Free Shipping</h5>
                        <p>On all orders over $50</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="benefit-content">
                        <h5>60-Day Returns</h5>
                        <p>Money back guarantee</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="benefit-content">
                        <h5>Secure Checkout</h5>
                        <p>100% protected payment</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="benefit-content">
                        <h5>Quality Guarantee</h5>
                        <p>Tested for purity & potency</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <section class="related-products">
        <div class="container text-center">
            <h3>You May Also Like</h3>
        </div>
        <div class="row mt-4">
            <!-- Sample Related Products -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product text-center">
                    <img class="img-fluid mb-3" src="{{ asset('storage/' . $product->image) }}" alt="Related Product">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="p-name">Premium Protein Powder</h5>
                    <p class="p-description">High-quality protein to support muscle growth and recovery.</p>
                    <h4 class="p-price">$49.99</h4>
                    <div class="d-flex justify-content-between mt-2">
                        <button class="buy-btn buy-now-btn" data-product="1">Buy Now</button>
                        <a href="#" class="btn btn-outline-secondary details-btn">Details</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product text-center">
                    <img class="img-fluid mb-3" src="{{ asset('storage/' . $product->image) }}" alt="Related Product">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h5 class="p-name">BCAA Supplement</h5>
                    <p class="p-description">Essential amino acids for enhanced recovery and performance.</p>
                    <h4 class="p-price">$34.99</h4>
                    <div class="d-flex justify-content-between mt-2">
                        <button class="buy-btn buy-now-btn" data-product="2">Buy Now</button>
                        <a href="#" class="btn btn-outline-secondary details-btn">Details</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product text-center">
                    <img class="img-fluid mb-3" src="{{ asset('storage/' . $product->image) }}" alt="Related Product">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <h5 class="p-name">Pre-Workout Energy</h5>
                    <p class="p-description">Intense energy boost for maximum workout performance.</p>
                    <h4 class="p-price">$39.99</h4>
                    <div class="d-flex justify-content-between mt-2">
                        <button class="buy-btn buy-now-btn" data-product="3">Buy Now</button>
                        <a href="#" class="btn btn-outline-secondary details-btn">Details</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product text-center">
                    <img class="img-fluid mb-3" src="{{ asset('storage/' . $product->image) }}" alt="Related Product">
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="p-name">Omega-3 Fish Oil</h5>
                    <p class="p-description">Essential fatty acids for heart and joint health.</p>
                    <h4 class="p-price">$24.99</h4>
                    <div class="d-flex justify-content-between mt-2">
                        <button class="buy-btn buy-now-btn" data-product="4">Buy Now</button>
                        <a href="#" class="btn btn-outline-secondary details-btn">Details</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>

    <!-- Additional quantity control script -->
    <script>
    // Immediate quantity control script that runs before DOM is fully loaded
    (function() {
        function setupQuantityControls() {
            const quantityInput = document.getElementById('quantity');
            const decreaseBtn = document.getElementById('decrease-quantity');
            const increaseBtn = document.getElementById('increase-quantity');
            
            if (quantityInput && decreaseBtn && increaseBtn) {
                decreaseBtn.onclick = function() {
                    let value = parseInt(quantityInput.value) || 1;
                    if (value > 1) {
                        quantityInput.value = value - 1;
                    }
                    return false;
                };
                
                increaseBtn.onclick = function() {
                    let value = parseInt(quantityInput.value) || 1;
                    let max = quantityInput.hasAttribute('max') ? parseInt(quantityInput.getAttribute('max')) : 999;
                    if (value < max) {
                        quantityInput.value = value + 1;
                    }
                    return false;
                };
            }
        }
        
        // Try to set up controls immediately
        setupQuantityControls();
        
        // Also try again after a small delay
        setTimeout(setupQuantityControls, 500);
    })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Rating selection for review form
            const ratingStars = document.querySelectorAll('.rating-input i');
            
            if (ratingStars.length > 0) {
                ratingStars.forEach((star, index) => {
                    star.addEventListener('click', function() {
                        // Reset all stars
                        ratingStars.forEach(s => s.className = 'far fa-star');
                        
                        // Fill stars up to selected
                        for (let i = 0; i <= index; i++) {
                            ratingStars[i].className = 'fas fa-star';
                        }
                    });
                    
                    star.addEventListener('mouseover', function() {
                        // Reset all stars
                        ratingStars.forEach(s => s.className = 'far fa-star');
                        
                        // Fill stars up to hovered
                        for (let i = 0; i <= index; i++) {
                            ratingStars[i].className = 'fas fa-star';
                        }
                    });
                });
                
                document.querySelector('.rating-input').addEventListener('mouseout', function() {
                    // Reset to selected stars
                    const selectedRating = Array.from(ratingStars).filter(star => star.className === 'fas fa-star').length;
                    
                    // Reset all stars
                    ratingStars.forEach(s => s.className = 'far fa-star');
                    
                    // Fill stars up to selected
                    for (let i = 0; i < selectedRating; i++) {
                        ratingStars[i].className = 'fas fa-star';
                    }
                });
            }
            
            // Related Products Buy Now buttons
            const relatedBuyButtons = document.querySelectorAll('.related-products .buy-btn');
            
            relatedBuyButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const productCard = this.closest('.product');
                    if (!productCard) return;
                    
                    const productName = productCard.querySelector('.p-name').textContent;
                    const productImage = productCard.querySelector('img').src;
                    const productPrice = parseFloat(productCard.querySelector('.p-price').textContent.replace('$', ''));
                    const productId = this.getAttribute('data-product');
                    
                    // Add to cart
                    let cart = JSON.parse(localStorage.getItem('cart')) || [];
                    
                    const product = {
                        id: productId,
                        name: productName,
                        price: productPrice,
                        quantity: 1,
                        image: productImage
                    };
                    
                    cart.push(product);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    
                    // Update cart count
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = cart.length;
                        cartCountElement.style.display = 'flex';
                    }
                    
                    // Show success message
                    alert(productName + ' added to cart!');
                });
            });
        });
    </script>
@endsection