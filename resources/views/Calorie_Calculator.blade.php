@extends('layouts.app')

@section('title', 'Calorie Calculator')

@section('styles')
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
        crossorigin="anonymous">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('content')
  <!-- MAIN CALORIE CALCULATOR SECTION -->
  <section id="calorie-calculator" class="container my-5 py-5">
    <div class="row">
      <div class="col-12 col-md-8 offset-md-2">
        <div class="text-center mb-4">
          <h1>Calorie Calculator</h1>
          <p>Fuel Your Ambition, Transform Your Body.</p>
        </div>
        <form id="calorieForm" class="p-4" style="background-color: #f8f8f8;">
          @csrf
          <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" required>
              <option value="">Select</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>

          <div class="form-group">
            <label for="age">Age (years):</label>
            <input
              type="number"
              class="form-control"
              id="age"
              placeholder="e.g. 25"
              required
              min="1"
            />
          </div>

          <div class="form-group">
            <label for="weight">Weight (kg):</label>
            <input
              type="number"
              class="form-control"
              id="weight"
              placeholder="e.g. 70"
              required
              min="1"
            />
          </div>

          <div class="form-group">
            <label for="height">Height (cm):</label>
            <input
              type="number"
              class="form-control"
              id="height"
              placeholder="e.g. 175"
              required
              min="1"
            />
          </div>

          <div class="form-group">
            <label for="activity">Activity Level:</label>
            <select class="form-control" id="activity" required>
              <option value="">Select</option>
              <option value="low">Low</option>
              <option value="intermediate">Intermediate</option>
              <option value="high">High</option>
            </select>
          </div>

          <div class="form-group">
            <label for="goal">Goal:</label>
            <select class="form-control" id="goal" required>
              <option value="">Select</option>
              <option value="maintain">Maintain Weight</option>
              <option value="lose">Lose Weight</option>
              <option value="gain">Gain Muscle</option>
            </select>
          </div>

          <div class="text-center mt-4">
            <button type="button" id="calculateBtn" class="btn btn-primary">
              Calculate
            </button>
            <button type="reset" id="resetBtn" class="btn btn-secondary">
              Reset
            </button>
          </div>
        </form>

        <div class="mt-3">
          <label for="resultBox"><strong>Result:</strong></label>
          <input
            type="text"
            class="form-control"
            id="resultBox"
            readonly
          />
        </div>
      </div>
    </div>
  </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('scripts')
  <script src="{{ asset('js/Calorie_Calculator.js') }}"></script>
@endsection