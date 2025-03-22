@extends('layouts.app')

@section('title', 'Home')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

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
            <input type="text" class="search-input" placeholder="Search..." id="search-input">
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
    
    // Update cart count function - More robust version from navbar.blade.php
    function updateCartCount() {
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            try {
                // Try to get the cart from localStorage
                const cartData = localStorage.getItem('cart');
                let cartItems = [];
                
                if (cartData) {
                    cartItems = JSON.parse(cartData);
                    
                    // Check if cart is an array (handling different cart storage formats)
                    if (!Array.isArray(cartItems) && cartItems.items && Array.isArray(cartItems.items)) {
                        cartItems = cartItems.items;
                    } else if (!Array.isArray(cartItems) && typeof cartItems === 'object') {
                        // If it's an object with product entries
                        cartItems = Object.values(cartItems);
                    }
                }
                
                // Calculate total quantity (some carts store quantities separately)
                let totalItems = 0;
                if (Array.isArray(cartItems)) {
                    totalItems = cartItems.reduce((total, item) => {
                        // Check if item has a quantity property or is a simple item
                        const itemQuantity = item.quantity || 1;
                        return total + itemQuantity;
                    }, 0);
                }
                
                // Update the cart count display
                cartCountElement.textContent = totalItems;
                
                // Show/hide the count badge
                if (totalItems === 0) {
                    cartCountElement.style.display = 'none';
                } else {
                    cartCountElement.style.display = 'flex';
                }
                
            } catch (error) {
                console.error('Error updating cart count:', error);
                cartCountElement.textContent = '0';
                cartCountElement.style.display = 'none';
            }
        }
    }
    
    // Initial cart count update
    updateCartCount();
    
    // Listen for cart changes via localStorage event
    window.addEventListener('storage', function(e) {
        if (e.key === 'cart') {
            updateCartCount();
        }
    });
    
    // Create a custom event to update cart count from other scripts
    window.addEventListener('cartUpdated', function() {
        updateCartCount();
    });
    
    // Check for cart changes every 2 seconds (as backup)
    setInterval(updateCartCount, 2000);
    
    // Additional listeners for cart buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('.add-to-cart, .add-to-cart *')) {
            // Wait a moment for the cart to update in localStorage
            setTimeout(updateCartCount, 100);
        }
    });
    
    // Add hover effect to brand logos
    const brandImages = document.querySelectorAll('#brand img');
    brandImages.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.filter = 'grayscale(0%)';
            this.style.opacity = '1';
            this.style.transform = 'scale(1.05)';
        });
        img.addEventListener('mouseleave', function() {
            this.style.filter = 'grayscale(100%)';
            this.style.opacity = '0.7';
            this.style.transform = 'scale(1)';
        });
    });
});

// Make updateCartCount available globally
window.updateNavbarCart = function() {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        try {
            const cartData = localStorage.getItem('cart');
            let totalItems = 0;
            
            if (cartData) {
                const cartItems = JSON.parse(cartData);
                
                if (Array.isArray(cartItems)) {
                    totalItems = cartItems.reduce((total, item) => total + (item.quantity || 1), 0);
                } else if (cartItems.items && Array.isArray(cartItems.items)) {
                    totalItems = cartItems.items.reduce((total, item) => total + (item.quantity || 1), 0);
                } else if (typeof cartItems === 'object') {
                    totalItems = Object.values(cartItems).reduce((total, item) => total + (item.quantity || 1), 0);
                }
            }
            
            cartCountElement.textContent = totalItems;
            cartCountElement.style.display = totalItems === 0 ? 'none' : 'flex';
            
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }
};
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
    
    // Update cart count
    function updateCartCount() {
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            cartCountElement.textContent = cart.length;
            
            // Hide count if zero
            if (cart.length === 0) {
                cartCountElement.style.display = 'none';
            } else {
                cartCountElement.style.display = 'flex';
            }
        }
    }
    
    // Initial cart count update
    updateCartCount();
    
    // Listen for cart changes
    window.addEventListener('storage', function(e) {
        if (e.key === 'cart') {
            updateCartCount();
        }
    });
    
    // Add hover effect to brand logos
    const brandImages = document.querySelectorAll('#brand img');
    brandImages.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.filter = 'grayscale(0%)';
            this.style.opacity = '1';
            this.style.transform = 'scale(1.05)';
        });
        img.addEventListener('mouseleave', function() {
            this.style.filter = 'grayscale(100%)';
            this.style.opacity = '0.7';
            this.style.transform = 'scale(1)';
        });
    });
});
    
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
    <section id="home">
        <div class="container">
            <h5>PREMIUM SUPPLEMENTS</h5>
            <h1><span>Fuel Your</span> Fitness Journey</h1>
            <p>Science-backed nutrition to help you reach peak performance and achieve your fitness goals.</p>
            <a href="{{ route('about') }}" class="btn">Learn More</a>
        </div>
    </section>

    <section id="brand" class="container">
        <div class="row m-0 py-5 align-items-center justify-content-center">
            <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                <img class="img-fluid" src="{{ asset('/Images/Index1.png') }}" alt="Brand partner" style="max-height: 60px; filter: grayscale(100%); opacity: 0.7; transition: all 0.3s ease;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                <img class="img-fluid" src="{{ asset('/Images/I2.png') }}" alt="Brand partner" style="max-height: 60px; filter: grayscale(100%); opacity: 0.7; transition: all 0.3s ease;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                <img class="img-fluid" src="{{ asset('/Images/Index3.png') }}" alt="Brand partner" style="max-height: 60px; filter: grayscale(100%); opacity: 0.7; transition: all 0.3s ease;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                <img class="img-fluid" src="{{ asset('/Images/Index4.png') }}" alt="Brand partner" style="max-height: 60px; filter: grayscale(100%); opacity: 0.7; transition: all 0.3s ease;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                <img class="img-fluid" src="{{ asset('/Images/Index5.png') }}" alt="Brand partner" style="max-height: 60px; filter: grayscale(100%); opacity: 0.7; transition: all 0.3s ease;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 text-center mb-4">
                <img class="img-fluid" src="{{ asset('/Images/Index6.png') }}" alt="Brand partner" style="max-height: 60px; filter: grayscale(100%); opacity: 0.7; transition: all 0.3s ease;">
            </div>
        </div>
    </section>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effect to brand logos
        const brandImages = document.querySelectorAll('#brand img');
        brandImages.forEach(img => {
            img.addEventListener('mouseenter', function() {
                this.style.filter = 'grayscale(0%)';
                this.style.opacity = '1';
                this.style.transform = 'scale(1.05)';
            });
            img.addEventListener('mouseleave', function() {
                this.style.filter = 'grayscale(100%)';
                this.style.opacity = '0.7';
                this.style.transform = 'scale(1)';
            });
        });
    });
    </script>

    <section id="new" class="w-100">
        <div class="row p-0 m-0">
            @if(isset($banners) && count($banners) > 0)
                @foreach($banners as $banner)
                <div class="one col-lg-4 col-md-12 col-12 p-0">
                    <img class="img-fluid" src="{{ asset($banner->image) }}" alt="{{ $banner->title }}">
                    <div class="details">
                        <h2>{{ $banner->title }}</h2>
                        <a href="{{ $banner->link_url ?? route('about') }}" class="text-uppercase btn">Learn More</a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="one col-lg-4 col-md-12 col-12 p-0">
                    <img class="img-fluid" src="{{ asset('/Images/stock1.png') }}" alt="">
                    <div class="details">
                        <h2>FITNESS TIPS</h2>
                        <a href="{{ route('blog') }}" class="text-uppercase btn">Read More</a>
                    </div>
                </div>
                <div class="one col-lg-4 col-md-12 col-12 p-0">
                    <img class="img-fluid" src="{{ asset('/Images/stock2.png') }}" alt="">
                    <div class="details">
                        <h2>NUTRITION ADVICE</h2>
                        <a href="{{ route('blog') }}" class="text-uppercase btn">Read More</a>
                    </div>
                </div>
                <div class="one col-lg-4 col-md-12 col-12 p-0">
                    <img class="img-fluid" src="{{ asset('/Images/stock3.png') }}" alt="">
                    <div class="details">
                        <h2>TRAINING RESOURCES</h2>
                        <a href="{{ route('blog') }}" class="text-uppercase btn">Read More</a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Banner Section -->
    <div class="container">
        <section id="banner" class="my-5 py-5" style="background-image: url('{{ asset('/Images/banner-bg.jpg') }}');">
            <div class="container">
                <h4>FITNESS RESOURCES</h4>
                <h1>Achieve Your<br>Fitness Goals</h1>
                <p class="text-white mb-4">Access expert advice and resources to support your fitness journey</p>
                <a href="{{ route('blog') }}" class="btn">LEARN MORE</a>
            </div>
        </section>
    </div>

    <div class="section-divider container"></div>
    
    @include('partials.testimonials')
    
    <div class="section-divider container"></div>
    
    @include('partials.why-choose-us')
    
    <!-- Newsletter Section -->
    <section id="newsletter" class="py-5 my-5" style="background-color: var(--secondary-color);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-white">
                    <h3 class="mb-3" style="font-weight: 700;">Join Our Community</h3>
                    <p class="mb-4">Subscribe to our newsletter for fitness tips and new resource announcements.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle mr-2"></i> Expert fitness and nutrition advice</li>
                        <li class="mb-2"><i class="fas fa-check-circle mr-2"></i> Training guides and workout plans</li>
                        <li class="mb-2"><i class="fas fa-check-circle mr-2"></i> Community support and motivation</li>
                    </ul>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <div class="newsletter-form p-4 bg-white rounded shadow">
                        <h4 class="mb-3 text-center">Sign Up Now</h4>
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control mb-3" placeholder="Your Name">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control mb-3" placeholder="Your Email">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Subscribe</button>
                            <p class="mt-3 text-center small text-muted">We respect your privacy. Unsubscribe at any time.</p>
                        </form>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection