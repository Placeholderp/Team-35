{{-- 
********************************
Developer: Hussain Alwazan
University ID: 230049123
Function: This page is to display items and purchase it.
********************************
--}}

@extends('layouts.app')

@section('title', 'Payment Page')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/Checkout.css') }}">
@endsection

@section('navigation')
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
        <div class="container">
            <img src="{{ asset('/images/team_logo.png') }}" alt="">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span><i id="bar" class="fas fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">Home</a>
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
                <i onclick="window.location.href='{{ route('cart.index') }}';" class="fal fa-shopping-bag"></i>

                    </li>

                        <li class="nav-item">
                        <div class="profile">
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile" id="profile-icon">
                            <div class="profile-dropdown" id="profile-dropdown">
                                <p class="profile-name">Name: John Doe</p>
                                <button id="logout-btn">Logout</button>
                            </div>
                            
                        </div>

            </li>
            
                    
                </ul>
            </div>
        </div>
    </nav>
@endsection
@section('content')
    <!--Main container-->
    <div class="container">
        <!--form-->
        <form id="paymentForm">
            @csrf
            <div class="row">
                <!--Left column-->
                <div class="column">
                    <h3 class="title">Billing Address</h3>
                    <div class="input-box" >
                        <span>Full Name :</span>
                        <input type="text" name="full_name"
                        placeholder="Hussain Alwazan" required>
                    </div>
                    <div class="input-box" >
                        <span>Email :</span>
                        <input type="email" name="email"
                        placeholder="example@example.com" required>
                    </div>
                    <div class="input-box">
                        <span>Address :</span>
                        <input type="text" name="address"
                        placeholder="Address" required>
                    </div>
                    <div class="input-box">
                        <span>City :</span>
                        <input type="text" name="city"
                        placeholder="Birmingham" required>
                    </div>
                    <div class="input-box">
                        <span>PostCode :</span>
                        <input type="text" name="postcode"
                        placeholder="B4 7ET" required>
                    </div>
                </div>
                <!--right column-->
                <div class="column">
                    <h3 class="title">Payment</h3>
                    <div class="input-box">
                        <span>Cards Accepted :</span>
                      <img src="{{ asset('img/imgcards.png') }}" alt="Accepted Cards">
                    </div>
                    <div class="input-box">
                        <span>Name On Card :</span>
                        <input type="text" name="card_name"
                        placeholder="Mr.Hussain Alwazan" required>
                    </div>
                     
                    <div class="input-box">
                        <span>Credit Card Number :</span>
                        <input type="text" id="cardNumber" name="card_number"
                        placeholder="Card Number" maxlength="19" required>
                    </div>
                    
                    <div class="input-box">
                        <span>Expiry Date :</span>
                        <input type="month" id="expiryDate" name="expiry_date"
                         required>
                    </div>
    
                    <div class="input-box">
                        <span>CVV :</span>
                        <input type="text" id="CVV" name="cvv"
                        placeholder="123" maxlength="3" required>
                    </div>
                    
                </div>
            </div>
            <button type="button" onclick="submitForm()" class="btn">Pay Now</button>
        
        </form>
        <p id="result"></p>
    </div>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('scripts')
    <script src="{{ asset('js/paymentjs.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection