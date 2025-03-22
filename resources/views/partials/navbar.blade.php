<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
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
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
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
                        <form action="{{ route('shop') }}" method="GET" id="search-form">
                            <input type="text" class="search-input" name="query" placeholder="Search products..." id="search-input">
                            <i class="fal fa-search search-icon" id="search-icon"></i>
                        </form>
                    </div>
                </li>
                
                <!-- Cart Icon -->
                <li class="nav-item d-flex align-items-center cart-icon-container">
                    <a href="{{ route('cart.index') }}" class="cart-icon">
                        <i class="fal fa-shopping-bag"></i>
                        <span class="cart-count" id="cart-count">0</span>
                    </a>
                </li>
                
                <!-- Profile Dropdown - Real Authentication -->
                <li class="nav-item d-flex align-items-center ml-3">
                    @auth
                    <div class="profile-container">
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile" id="profile-icon" onclick="toggleProfileMenu()">
                        
                        <div id="profile-menu" class="profile-menu">
                            <div class="profile-menu-header">
                                <p class="profile-menu-name">{{ Auth::user()->name }}</p>
                                <p class="profile-menu-email">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <div class="profile-menu-body">
                                <a href="{{ route('profile.show') }}" class="profile-menu-link">
                                    <i class="fas fa-user"></i> My Profile
                                </a>
                                <a href="{{ route('orders.index') }}" class="profile-menu-link">
                                    <i class="fas fa-shopping-bag"></i> My Orders
                                </a>
                                <a href="{{ route('profile.settings') }}" class="profile-menu-link">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                            </div>
                            
                            <div class="profile-menu-footer">
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

.search-container form {
    margin: 0;
    padding: 0;
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

/* NEW PROFILE DROPDOWN STYLES */
.profile-container {
    position: relative;
    display: inline-block;
}

#profile-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer !important;
    border: 2px solid white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

#profile-icon:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.profile-menu {
    display: none;
    position: absolute;
    top: 45px;
    right: 0;
    width: 240px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    z-index: 9999 !important;
    border: 1px solid #eaeaea;
}

.profile-menu-header {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    background-color: #f9f9f9;
}

.profile-menu-name {
    margin: 0;
    font-weight: 600;
    font-size: 15px;
    color: #333;
}

.profile-menu-email {
    margin: 5px 0 0;
    font-size: 12px;
    color: #777;
}

.profile-menu-body {
    padding: 10px 0;
}

.profile-menu-link {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: #444;
    text-decoration: none;
    transition: background-color 0.2s;
}

.profile-menu-link:hover {
    background-color: #f5f5f5;
    color: var(--primary-color);
}

.profile-menu-link i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.profile-menu-footer {
    padding: 10px 15px;
    border-top: 1px solid #f0f0f0;
}

.logout-button {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px 12px;
    background-color: #f5f5f5;
    color: #e74c3c;
    border: none;
    border-radius: 4px;
    cursor: pointer !important;
    font-size: 14px;
    transition: background-color 0.2s;
}

.logout-button:hover {
    background-color: #fee2e2;
}

.logout-button i {
    margin-right: 8px;
}

/* Auth buttons styling */
.auth-buttons {
    display: flex;
    align-items: center;
}

.auth-buttons .btn {
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 4px;
}

.auth-buttons .btn-outline-primary {
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.auth-buttons .btn-outline-primary:hover {
    background-color: var(--primary-color);
    color: white;
}

.auth-buttons .btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.auth-buttons .ml-2 {
    margin-left: 8px;
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
    
    .profile-container {
        margin-bottom: 15px;
    }
    
    .profile-menu {
        position: static;
        width: 100%;
        margin-top: 15px;
        box-shadow: none;
        border: 1px solid #eee;
    }
    
    .auth-buttons {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchIcon = document.getElementById('search-icon');
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    
    if (searchIcon && searchInput && searchForm) {
        // Toggle search input visibility
        searchIcon.addEventListener('click', function(e) {
            e.preventDefault();
            searchInput.classList.toggle('active');
            
            if (searchInput.classList.contains('active')) {
                searchInput.focus();
            } else {
                searchInput.value = '';
            }
        });
        
        // Submit search on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && searchInput.value.trim() !== '') {
                searchForm.submit();
            }
        });
        
        // Submit search when clicking the icon while input is visible and has text
        searchIcon.addEventListener('click', function(e) {
            if (searchInput.classList.contains('active') && searchInput.value.trim() !== '') {
                searchForm.submit();
            }
        });
    }
      
    // Update cart count function
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

// Profile dropdown toggle function
function toggleProfileMenu() {
    const menu = document.getElementById('profile-menu');
    if (menu.style.display === 'block') {
        menu.style.display = 'none';
    } else {
        menu.style.display = 'block';
    }
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('profile-menu');
    const icon = document.getElementById('profile-icon');
    
    if (menu && icon && event.target !== icon && !menu.contains(event.target)) {
        menu.style.display = 'none';
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const logoutForm = document.getElementById('logout-form');
    const logoutButton = document.querySelector('.logout-button');
    
    if (logoutForm && logoutButton) {
        logoutButton.addEventListener('click', function(e) {
            e.preventDefault();
            logoutForm.submit();
        });
    }
});
</script>