{{-- 
    ********************************
    Developer: Hussain Alwazan
    University ID: 230049123
    Function: Success page for displaying payment confirmation
    ********************************
    --}}
    
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>Payment Successful | Your Order Confirmation</title>
        
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/checkoutstyle.css') }}">
        <!-- Add Font Awesome for professional icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <style>
            body {
                padding-top: 0 !important;
                margin: 0;
                font-family: sans-serif;
            }
            
            .sc-container {
                max-width: 600px;
                margin: 50px auto;
                padding: 30px;
                text-align: center;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
            
            .divider {
                height: 2px;
                background-color: #e65c00;
                width: 60px;
                margin: 20px auto;
            }
            
            .order-details {
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 8px;
                margin: 20px 0;
                text-align: left;
            }
            
            .sc-btn {
                display: inline-block;
                background-color: #e65c00;
                color: white;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 20px;
                transition: background-color 0.3s;
            }
            
            .sc-btn:hover {
                background-color: #d45500;
                color: white;
            }
        </style>
    </head>
    <body>
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
        
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        
        <!-- Cart clearing script - always runs on successful checkout -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Clear cart data from localStorage
                localStorage.removeItem('cart');
                localStorage.removeItem('orderSummary');
                console.log('Shopping cart cleared successfully');
            });
        </script>
        
        @if(!isset($order))
        <script>
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
        
        @endif
    </body>
    </html>