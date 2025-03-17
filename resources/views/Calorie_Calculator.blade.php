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
            <!-- Check if image exists before rendering -->
            @if(file_exists(public_path('storage/images/team_logo.png')))
                <img src="{{ asset('storage/images/team_logo.png') }}" alt="Team Logo">
            @else
                <span class="navbar-brand">Fitness Calculator</span>
            @endif
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link active" href="{{ route('calorie.calculator') }}">Calorie Calculator</a>
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