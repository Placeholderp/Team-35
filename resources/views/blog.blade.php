@extends('layouts.app')

@section('title', 'Fitness Blog | Aston35Fitness')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="description" content="Explore fitness tips, nutrition advice, and workout guides from Aston35Fitness experts.">
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
                        <input type="text" class="search-input" placeholder="Search articles..." id="search-input">
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
                                <span class="profile-dropdown-item">
                                    <i class="fas fa-user"></i> My Account
                                </span>
                                <span class="profile-dropdown-item">
                                    <i class="fas fa-shopping-bag"></i> Shop
                                </span>
                                <span class="profile-dropdown-item">
                                    <i class="fas fa-heart"></i> Saved Articles
                                </span>
                            </div>
                            <div class="profile-dropdown-footer">
                                <div class="logout-button-container">
                                    <button type="button" class="logout-button" disabled>
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="auth-buttons">
                        <button class="btn btn-sm btn-outline-primary" disabled>Login</button>
                        <button class="btn btn-sm btn-primary ml-2" disabled>Register</button>
                    </div>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
:root {
    --primary-color: #e74c3c;
    --primary-dark: #c0392b;
    --secondary-color: #3498db;
    --text-color: #333;
    --text-muted: #777;
    --bg-light: #f9f9f9;
    --border-color: #eee;
    --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

/* Enhanced Navbar Styling */
.navbar {
    box-shadow: var(--shadow);
    transition: var(--transition);
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
    color: var(--text-color) !important;
    font-weight: 500;
    font-size: 15px;
    padding: 10px 15px !important;
    transition: var(--transition);
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
    transition: var(--transition);
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    visibility: hidden;
}

.search-input.active {
    width: 200px;
    border: 1px solid var(--border-color);
    opacity: 1;
    visibility: visible;
    background-color: var(--bg-light);
}

.search-icon {
    cursor: pointer;
    font-size: 16px;
    color: #555;
    transition: var(--transition);
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
    transition: var(--transition);
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
    transition: var(--transition);
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
    border-bottom: 1px solid var(--border-color);
    background-color: var(--bg-light);
}

.profile-dropdown-header .profile-name {
    margin: 0;
    font-weight: 600;
    font-size: 15px;
    color: var(--text-color);
}

.profile-dropdown-header .profile-email {
    margin: 5px 0 0 0;
    font-size: 12px;
    color: var(--text-muted);
}

.profile-dropdown-body {
    padding: 10px 0;
}

.profile-dropdown-item {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: var(--text-color);
    font-size: 14px;
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: default;
    opacity: 0.8;
}

.profile-dropdown-item i {
    margin-right: 10px;
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.profile-dropdown-footer {
    padding: 10px 15px;
    border-top: 1px solid var(--border-color);
}

.profile-dropdown-footer button {
    width: 100%;
    padding: 8px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-light);
    color: #555;
    border: 1px solid var(--border-color);
    border-radius: 5px;
    font-size: 14px;
    transition: var(--transition);
    cursor: default;
    opacity: 0.7;
}

.profile-dropdown-footer button i {
    margin-right: 8px;
}

/* Non-interactive Elements */
.btn:disabled {
    opacity: 0.8;
    cursor: default;
    pointer-events: none;
}

/* Blog Specific Styling */
#blog-home {
    background-color: var(--bg-light);
    padding: 30px 0;
    margin-bottom: 30px;
}

#blog-home h2 {
    color: var(--text-color);
    font-size: 2.2rem;
    position: relative;
}

#blog-home hr {
    border-color: var(--primary-color);
    width: 60px;
    margin-left: 0;
    border-width: 3px;
}

.post {
    transition: var(--transition);
}

.post {
    cursor: default;
}

.post:hover {
    transform: translateY(-5px);
}

.post-img {
    overflow: hidden;
    position: relative;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
}

.post-img img {
    transition: transform 0.7s ease;
    border-radius: 8px;
}

.post:hover .post-img img {
    transform: scale(1.03);
}

.post h3 {
    color: var(--text-color);
    font-weight: 600;
    font-size: 1.4rem;
    margin-top: 15px;
    margin-bottom: 10px;
    line-height: 1.4;
}

.post h4 {
    color: var(--text-color);
    font-weight: 600;
    font-size: 1.2rem;
    margin-top: 15px;
    margin-bottom: 10px;
    line-height: 1.4;
}

.post p {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.post .date-author {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-bottom: 15px;
}

.post .date-author i {
    margin-right: 5px;
    color: var(--primary-color);
}

.post .date-author span {
    margin-right: 15px;
}

.post .category {
    position: absolute;
    top: 15px;
    left: 15px;
    background-color: var(--primary-color);
    color: white;
    padding: 4px 12px;
    font-size: 0.8rem;
    border-radius: 4px;
    z-index: 1;
}

.post .read-more {
    color: var(--primary-color);
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-block;
    margin-top: 10px;
    text-decoration: none;
    position: relative;
    cursor: default;
    opacity: 0.8;
}

.blog-banner {
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    margin: 30px 0;
}

.blog-banner img {
    transition: transform 0.5s ease;
}

.blog-banner:hover img {
    transform: scale(1.02);
}

/* Product Cards Styling */
.product-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    padding: 15px;
    transition: var(--transition);
    height: 100%;
    cursor: default;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.product-img {
    margin-bottom: 12px;
    border-radius: 6px;
    overflow: hidden;
}

.product-img img {
    aspect-ratio: 1/1;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-img img {
    transform: scale(1.05);
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 5px;
}

.product-desc {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-bottom: 0;
}

/* New Product Spotlight Styling */
.product-spotlight {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.product-spotlight:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.product-spotlight .product-img {
    height: 220px;
    overflow: hidden;
    position: relative;
    margin-bottom: 0;
    border-radius: 10px 10px 0 0;
}

.product-spotlight .product-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-spotlight:hover .product-img img {
    transform: scale(1.05);
}

.product-content {
    padding: 20px;
}

.product-content h4 {
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-color);
}

.product-content p {
    color: var(--text-muted);
    margin-bottom: 15px;
    font-size: 0.9rem;
    line-height: 1.5;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 5px;
    position: relative;
    display: inline-block;
    padding-bottom: 8px;
}

.section-title:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 3px;
    background-color: var(--primary-color);
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
    
    .profile-dropdown {
        position: static;
        width: 100%;
        margin-top: 15px;
        box-shadow: none;
        border: 1px solid #eee;
    }
    
    #blog-home h2 {
        font-size: 1.8rem;
    }
    
    .post h3 {
        font-size: 1.2rem;
    }
    
    .post h4 {
        font-size: 1rem;
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
        
        // Search input functionality
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim().toLowerCase();
                if (searchTerm.length > 2) {
                    // Using direct URL instead of named route
                    window.location.href = `/blog?search=${encodeURIComponent(searchTerm)}`;
                }
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
    <!-- Blog Header Section -->
    <section id="blog-home">
        <div class="container pt-5 mt-5">
            <h2 class="font-weight-bold pt-5">Fitness Blog</h2>
            <hr>
            <p class="text-muted">Discover expert fitness tips, workout guides, and nutrition advice</p>
        </div>
    </section>

    <!-- New Spotlight Article Section -->
    <section id="blog-container" class="container pt-4">
        <div class="row mb-5">
            <div class="col-12 mb-4">
                <h3 class="section-title">Spotlight Article</h3>
            </div>
            <div class="col-md-6">
                <div class="post-img">
                    
                    <img class="w-100 img-fluid" src="{{ asset('storage/products/Zumba (Workout Plans).png') }}" alt="Core Training">
                </div>
            </div>
            <div class="col-md-6">
                <div class="date-author">
                    <span><i class="far fa-calendar-alt"></i> Mar 24, 2025</span>
                    <span><i class="far fa-user"></i> Coach Alex</span>
                </div>
                <h3>Core Strength Training: The Foundation of Athletic Performance</h3>
                <p>Discover how developing a strong core can transform your athletic performance, prevent injuries, and improve overall fitness outcomes no matter your training goals.</p>
                <p>Our certified trainers share an optimal core-focused workout routine that takes just 15 minutes per day but delivers exceptional results.</p>
                <span class="read-more">Read More</span>
            </div>
        </div>

        <!-- Product Spotlight Section -->
        <div class="row mb-5">
            <div class="col-12 mb-4">
                <h3 class="section-title">Product Spotlight</h3>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="product-spotlight">
                    <div class="product-img">
                        <img class="img-fluid" src="{{ asset('storage/products/Zero Sugar PreWorkout (PreWorkout).png') }}" alt="Protein Powder">
                    </div>
                    <div class="product-content">
                        <h4>The Science of Protein Timing</h4>
                        <p>Learn how strategic protein consumption can optimize muscle recovery and growth.</p>
                        <span class="read-more">Learn More</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="product-spotlight">
                    <div class="product-img">
                        <img class="img-fluid" src="{{ asset('storage/products/Whey Protein (Supplements).png') }}" alt="ASTON35 Vitamins">
                    </div>
                    <div class="product-content">
                        <h4>Daily Vitamins: Essential or Optional?</h4>
                        <p>Explore the research behind multivitamin supplementation and who benefits most.</p>
                        <span class="read-more">Learn More</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="product-spotlight">
                    <div class="product-img">
                        <img class="img-fluid" src="{{ asset('storage/products/Oat Bakes (Protein Foods).png') }}" alt="Whey Protein">
                    </div>
                    <div class="product-content">
                        <h4>Whey Protein: Nature's Muscle Builder</h4>
                        <p>Understand why whey protein remains the gold standard for muscle recovery and growth.</p>
                        <span class="read-more">Learn More</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Articles Section -->
        <div class="row">
            <div class="col-12 mb-4">
                <h3 class="section-title">Featured Articles</h3>
            </div>
            
            @if(!empty($featuredPosts) && count($featuredPosts) > 0)
                @foreach($featuredPosts as $post)
                <div class="post col-lg-6 col-md-6 col-12 pb-5">
                    <div class="post-img">
                        @if($post->category)
                            <div class="category">{{ $post->category }}</div>
                        @endif
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/Multi Vitamins (Vitamins).png/' . $post->image) }}" alt="{{ $post->title }}">
                    </div>
                    <div class="date-author">
                        <span><i class="far fa-calendar-alt"></i> {{ $post->created_at->format('M d, Y') }}</span>
                        @if($post->author)
                            <span><i class="far fa-user"></i> {{ $post->author }}</span>
                        @endif
                    </div>
                    <h3>{{ $post->title }}</h3>
                    <p>{{ Str::limit($post->excerpt ?? '', 120) }}</p>
                    <span class="read-more">Read More</span>
                </div>
                @endforeach
            @else
                <!-- Fallback static content -->
                <div class="post col-lg-6 col-md-6 col-12 pb-5">
                    <div class="post-img">
                        <div class="category">Workout</div>
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/L-Citrulline Malate (PreWorkout).png') }}" alt="HIIT Workout Guide">
                    </div>
                    <div class="date-author">
                        <span><i class="far fa-calendar-alt"></i> Jan 11, 2023</span>
                        <span><i class="far fa-user"></i> Coach Mike</span>
                    </div>
                    <h3>The Ultimate HIIT Workout Guide for Busy Professionals</h3>
                    <p>Discover how high-intensity interval training can transform your fitness routine and maximize results in minimal time.</p>
                    <span class="read-more">Read More</span>
                </div>
                <div class="post col-lg-6 col-md-6 col-12 pb-5">
                    <div class="post-img">
                        <div class="category">Hydration</div>
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/HMB Tablet (Vitamins).png') }}" alt="Electrolyte Hydration">
                    </div>
                    <div class="date-author">
                        <span><i class="far fa-calendar-alt"></i> Mar 18, 2025</span>
                        <span><i class="far fa-user"></i> Dr. James Wilson</span>
                    </div>
                    <h3>The Science of Hydration: Why Electrolytes Matter For Athletes</h3>
                    <p>Learn how proper electrolyte balance impacts performance, recovery, and overall health during intense training sessions.</p>
                    <span class="read-more">Read More</span>
                </div>
            @endif
        </div>

        <!-- Products Spotlight Section -->
        <div class="row">
            <div class="col-12 mb-4">
                <h3 class="section-title">Featured Products</h3>
                <p class="text-muted">Discover our latest supplements to support your fitness journey</p>
            </div>
            <div class="col-md-3 col-sm-6 col-12 mb-4">
                <div class="product-card">
                    <div class="product-img">
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/Ganorla (Protein Foods).png') }}" alt="Multivitamins">
                    </div>
                    <h5 class="product-title">Multi-Vitamins & Minerals</h5>
                    <p class="product-desc">Daily support for optimal health</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12 mb-4">
                <div class="product-card">
                    <div class="product-img">
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/Full Amino Acid (Vitamins).png') }}" alt="BCAA">
                    </div>
                    <h5 class="product-title">ASTON35 BCAA Recovery</h5>
                    <p class="product-desc">Enhanced muscle recovery</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12 mb-4">
                <div class="product-card">
                    <div class="product-img">
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/Creatine (Supplements).png') }}" alt="Pre-Workout">
                    </div>
                    <h5 class="product-title">Warrior RAGE Pre-Workout</h5>
                    <p class="product-desc">Maximum training intensity</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12 mb-4">
                <div class="product-card">
                    <div class="product-img">
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/Cardio Routine (Workout Plans).png') }}" alt="Electrolytes">
                    </div>
                    <h5 class="product-title">HYROX Electrofuel</h5>
                    <p class="product-desc">Advanced hydration formula</p>
                </div>
            </div>
        </div>
        
        <!-- Banner Ad/Promotion Section -->
        <div class="row">
            <div class="col-12 my-4">
                <div class="blog-banner">
                    <img class="w-100 img-fluid" src="{{ asset('storage/products/Protein Brownie (Protein Foods).png') }}" alt="Fitness Programs">
                </div>
            </div>
        </div>

        <!-- Recent Articles Section -->
        <div class="row">
            <div class="col-12 mb-4">
                <h3 class="section-title">Recent Articles</h3>
            </div>
            
            @if(!empty($regularPosts) && count($regularPosts) > 0)
                @foreach($regularPosts as $post)
                <div class="post col-lg-4 col-md-6 col-12 pb-5">
                    <div class="post-img">
                        @if($post->category)
                            <div class="category">{{ $post->category }}</div>
                        @endif
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/Whey Protein (Supplements).png' . $post->image) }}" alt="{{ $post->title }}">
                    </div>
                    <div class="date-author">
                        <span><i class="far fa-calendar-alt"></i> {{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    <h4>{{ $post->title }}</h4>
                    <span class="read-more">Read More</span>
                </div>
                @endforeach
            @else
                <!-- Product-related blog posts -->
                <div class="post col-lg-4 col-md-6 col-12 pb-5">
                    <div class="post-img">
                        <div class="category">Supplements</div>
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/Calisthenics Routine Workout Plans).png') }}" alt="Daily Vitamins">
                    </div>
                    <div class="date-author">
                        <span><i class="far fa-calendar-alt"></i> Mar 15, 2025</span>
                    </div>
                    <h4>Do You Really Need a Daily Multivitamin? The Science Explained</h4>
                    <span class="read-more">Read More</span>
                </div>
                <div class="post col-lg-4 col-md-6 col-12 pb-5">
                    <div class="post-img">
                        <div class="category">Performance</div>
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/bcaa.png') }}" alt="BCAA Benefits">
                    </div>
                    <div class="date-author">
                        <span><i class="far fa-calendar-alt"></i> Mar 20, 2025</span>
                    </div>
                    <h4>BCAA Supplements: How They Support Muscle Recovery</h4>
                    <span class="read-more">Read More</span>
                </div>
                <div class="post col-lg-4 col-md-6 col-12 pb-5">
                    <div class="post-img">
                        <div class="category">Pre-Workout</div>
                        <img class="w-100 img-fluid" src="{{ asset('storage/products/Vegan Protein Powder (Supplements).png') }}" alt="Pre-Workout Guide">
                    </div>
                    <div class="date-author">
                        <span><i class="far fa-calendar-alt"></i> Mar 22, 2025</span>
                    </div>
                    <h4>Pre-Workout Supplements: Maximizing Your Training Sessions</h4>
                    <span class="read-more">Read More</span>
                </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if(!empty($regularPosts) && method_exists($regularPosts, 'links'))
            <div class="row">
                <div class="col-12 d-flex justify-content-center mt-3 mb-5">
                    {{ $regularPosts->links() }}
                </div>
            </div>
        @endif
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
@endsection