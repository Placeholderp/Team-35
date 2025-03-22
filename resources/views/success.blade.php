{{-- 
    ********************************
    Developer: Hussain Alwazan
    University ID: 230049123
    Function: Success page for displaying payment confirmation
    ********************************
    --}}
    
    @extends('layouts.app')

    @section('title', 'Payment Successful | Your Order Confirmation')
    
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/checkoutstyle.css') }}">
        <!-- Add Font Awesome for professional icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @endsection
    
    @section('content')
        <div class="sc-container">
            <!-- Success Icon -->
            <i class="fas fa-check-circle" style="font-size: 80px; color: #e65c00; margin-bottom: 1rem;"></i>
            
            <h1>Payment Successful!</h1>
            <div class="divider"></div>
            
            <p>Thank you for your purchase. Your transaction has been completed successfully.</p>
            
            <div class="order-details">
                <p><strong>Order #:</strong> <span id="orderNumber">
                    @if(isset($order))
                        {{ $order->order_number }}
                    @else
                        ORD-{{ rand(10000, 99999) }}
                    @endif
                </span></p>
                <p><strong>Date:</strong> <span id="orderDate">
                    @if(isset($order))
                        {{ $order->created_at->format('F d, Y') }}
                    @else
                        {{ date('F d, Y') }}
                    @endif
                </span></p>
                <p><strong>Estimated Delivery:</strong> Your order will arrive in 3 business days.</p>
            </div>
            
            <p>A confirmation email has been sent to your registered email address.</p>
            
            <a class="sc-btn" href="{{ route('home') }}">
                <i class="fas fa-home" style="margin-right: 8px;"></i>Back To Homepage
            </a>
        </div>
    @endsection
    
    @section('scripts')
        @if(!isset($order))
        <script>
            // Only use this script if order data isn't passed from the controller
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date();
                const formattedDate = today.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                // If no server-side order data
                if (document.getElementById('orderDate').textContent.trim() === '') {
                    document.getElementById('orderDate').textContent = formattedDate;
                }
                
                if (document.getElementById('orderNumber').textContent.trim() === '') {
                    const randomOrderNum = 'ORD-' + Math.floor(10000 + Math.random() * 90000);
                    document.getElementById('orderNumber').textContent = randomOrderNum;
                }
            });
        </script>
        <script src="{{ asset('js/profile.js') }}"></script>
        @endif
    @endsection