@extends('layouts.app')

@section('title', 'Calorie Calculator')

@section('styles')
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
        crossorigin="anonymous">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/calc.css') }}">
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
});
</script>
@endsection

@section('content')
  <!-- HEADER SECTION WITH BETTER CONTRAST -->
  <section class="container my-5 py-5">
    <div class="row">
      <div class="col-12 text-center">
        <h1 class="display-4">Nutrition Calculator</h1>
        <p class="lead">Personalize your nutrition plan to fuel your fitness journey</p>
      </div>
    </div>
  </section>

  <!-- MAIN CALORIE CALCULATOR SECTION -->
  <section id="calorie-calculator" class="container py-3">
    <div class="row">
      <div class="col-12 col-md-8 offset-md-2">
        <div class="calculator-card">
          <div class="calculator-header mb-4">
            <h2 class="text-center">Daily Calorie Calculator</h2>
            <p class="text-center">Calculate your recommended daily calorie intake based on your personal goals</p>
          </div>
          
          <form id="calorieForm" class="p-4">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="gender"><i class="fas fa-user"></i> Gender</label>
                  <select class="form-control" id="gender" required>
                    <option value="">Select your gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                  </select>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="age"><i class="fas fa-birthday-cake"></i> Age (years)</label>
                  <input type="number" class="form-control" id="age" placeholder="Your age" required min="15" max="100">
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="weight"><i class="fas fa-weight"></i> Weight (kg)</label>
                  <input type="number" class="form-control" id="weight" placeholder="Your weight" required min="30" max="170">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="height"><i class="fas fa-ruler-vertical"></i> Height (cm)</label>
                  <input type="number" class="form-control" id="height" placeholder="Your height" required min="100" max="240">
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="activity"><i class="fas fa-running"></i> Activity Level</label>
              <select class="form-control" id="activity" required>
                <option value="">Select your activity level</option>
                <option value="low">Low (little or no exercise)</option>
                <option value="intermediate">Intermediate (moderate exercise 3-5 days/week)</option>
                <option value="high">High (intense exercise 6-7 days/week)</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="goal"><i class="fas fa-bullseye"></i> Fitness Goal</label>
              <select class="form-control" id="goal" required>
                <option value="">Select your goal</option>
                <option value="maintain">Maintain Current Weight</option>
                <option value="lose">Lose Weight</option>
                <option value="gain">Gain Muscle</option>
              </select>
            </div>
            
            <div class="text-center mt-4">
              <button type="button" id="calculateBtn" class="btn btn-primary">
                <i class="fas fa-calculator"></i> Calculate
              </button>
              <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                <i class="fas fa-redo"></i> Reset
              </button>
            </div>
          </form>
          
          <div class="mt-3">
            <label for="resultBox"><strong>Your Daily Calorie Recommendation:</strong></label>
            <input type="text" class="form-control" id="resultBox" readonly>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- RESULTS CONTAINER (Hidden by default, shown after calculation) -->
  <section id="results-container" class="container py-3 d-none">
    <div class="row">
      <div class="col-12 col-md-8 offset-md-2">
        <div class="results-card">
          <div class="results-header mb-4">
            <h3 class="text-center">Your Personalized Nutrition Plan</h3>
          </div>
          
          <div class="calorie-display text-center">
            <span id="calorie-result">0</span>
            <small>calories per day</small>
          </div>
          
          <div class="row mt-4">
            <div class="col-md-4">
              <div class="result-detail text-center">
                <h5>For Weight Loss</h5>
                <p id="weight-loss-cal">0</p>
                <small>calories/day</small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="result-detail text-center">
                <h5>Maintenance</h5>
                <p id="maintenance-cal">0</p>
                <small>calories/day</small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="result-detail text-center">
                <h5>For Muscle Gain</h5>
                <p id="weight-gain-cal">0</p>
                <small>calories/day</small>
              </div>
            </div>
          </div>
          
          <div class="macros-section mt-4">
            <h4 class="text-center mb-3">Recommended Macronutrient Split</h4>
            <div class="row">
              <div class="col-md-4">
                <div class="macro-card protein-card">
                  <h6>Protein</h6>
                  <p id="protein-grams">0g</p>
                  <span id="protein-percent">30%</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="macro-card carbs-card">
                  <h6>Carbohydrates</h6>
                  <p id="carbs-grams">0g</p>
                  <span id="carbs-percent">40%</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="macro-card fat-card">
                  <h6>Fats</h6>
                  <p id="fat-grams">0g</p>
                  <span id="fat-percent">30%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- NUTRITION TIPS SECTION -->
  <section id="nutrition-tips" class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center mb-4">
          <h3>Nutrition Tips to Reach Your Goals</h3>
          <hr class="mx-auto" style="width: 50px;">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="tip-card">
            <div class="tip-icon">
              <i class="fas fa-apple-alt"></i>
            </div>
            <h5>Prioritize Whole Foods</h5>
            <p>Focus on minimally processed foods like fruits, vegetables, lean proteins, whole grains, and healthy fats for optimal nutrition.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="tip-card">
            <div class="tip-icon">
              <i class="fas fa-tint"></i>
            </div>
            <h5>Stay Hydrated</h5>
            <p>Drink adequate water throughout the day. Aim for at least 8 glasses daily to support metabolism and overall health.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="tip-card">
            <div class="tip-icon">
              <i class="fas fa-utensils"></i>
            </div>
            <h5>Portion Control</h5>
            <p>Be mindful of portion sizes. Even healthy foods can contribute to weight gain when consumed in excessive amounts.</p>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
  <script src="{{ asset('js/Calorie_Calculator.js') }}"></script>
  <script src="{{ asset('js/profile.js') }}"></script>
  
  <script>
    // Additional JavaScript to handle the results display
    document.addEventListener("DOMContentLoaded", function() {
      const calculateBtn = document.getElementById("calculateBtn");
      
      if (calculateBtn) {
        calculateBtn.addEventListener("click", function() {
          const resultBox = document.getElementById("resultBox");
          const calorieResult = document.getElementById("calorie-result");
          
          if (resultBox && resultBox.value && calorieResult) {
            // Extract the numeric value
            const calorieValue = parseInt(resultBox.value);
            
            if (!isNaN(calorieValue)) {
              // Update the main result
              calorieResult.textContent = calorieValue;
              
              // Update other calculations
              const weightLossCal = document.getElementById("weight-loss-cal");
              const maintenanceCal = document.getElementById("maintenance-cal");
              const weightGainCal = document.getElementById("weight-gain-cal");
              
              if (weightLossCal && maintenanceCal && weightGainCal) {
                weightLossCal.textContent = calorieValue - 500;
                maintenanceCal.textContent = calorieValue;
                weightGainCal.textContent = calorieValue + 500;
              }
              
              // Calculate macros
              const proteinGrams = document.getElementById("protein-grams");
              const carbsGrams = document.getElementById("carbs-grams");
              const fatGrams = document.getElementById("fat-grams");
              
              if (proteinGrams && carbsGrams && fatGrams) {
                // Calculate grams based on calorie splits
                // Protein: 4 calories per gram, 30% of total
                const proteinCals = calorieValue * 0.3;
                const proteinG = Math.round(proteinCals / 4);
                proteinGrams.textContent = proteinG + "g";
                
                // Carbs: 4 calories per gram, 40% of total
                const carbsCals = calorieValue * 0.4;
                const carbsG = Math.round(carbsCals / 4);
                carbsGrams.textContent = carbsG + "g";
                
                // Fat: 9 calories per gram, 30% of total
                const fatCals = calorieValue * 0.3;
                const fatG = Math.round(fatCals / 9);
                fatGrams.textContent = fatG + "g";
              }
              
              // Show results container
              const resultsContainer = document.getElementById("results-container");
              if (resultsContainer) {
                resultsContainer.classList.remove("d-none");
                resultsContainer.classList.add("show");
              }
            }
          }
        });
      }
      
      // Handle reset button
      const resetBtn = document.getElementById("resetBtn");
      if (resetBtn) {
        resetBtn.addEventListener("click", function() {
          const resultsContainer = document.getElementById("results-container");
          if (resultsContainer) {
            resultsContainer.classList.add("d-none");
            resultsContainer.classList.remove("show");
          }
          
          // Call the reset function from the main JS file
          resetForm();
        });
      }
    });
  </script>
@endsection