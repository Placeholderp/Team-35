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
@endsection