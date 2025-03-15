<!-- resources/views/payment.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment page</title>
    <!-- Use Laravel's asset() helper to correctly load your CSS -->
    <link rel="stylesheet" href="{{ asset('css/Checkout.css') }}">
</head>
<body>
    <div class="container">
        <form id="paymentForm">
            <div class="row">
                <div class="column">
                    <h3 class="title">Billing Address</h3>
                    <div class="input-box">
                        <span>Full Name :</span>
                        <input type="text" placeholder="Hussain Alwazan" required>
                    </div>
                    <div class="input-box">
                        <span>Email :</span>
                        <input type="email" placeholder="example@example.com" required>
                    </div>
                    <div class="input-box">
                        <span>Address :</span>
                        <input type="text" placeholder="Address" required>
                    </div>
                    <div class="input-box">
                        <span>City :</span>
                        <input type="text" placeholder="Birmingham" required>
                    </div>
                    <div class="input-box">
                        <span>PostCode :</span>
                        <input type="text" placeholder="B4 7ET" required>
                    </div>
                </div>
                <div class="column">
                    <h3 class="title">Payment</h3>
                    <div class="input-box">
                        <span>Cards Accepted :</span>
                        <img src="{{ asset('Images/imgcards.png') }}" alt="">
                    </div>
                    <div class="input-box">
                        <span>Name On Card :</span>
                        <input type="text" placeholder="Mr.Hussain Alwazan" required>
                    </div>
                    <div class="input-box">
                        <span>Credit Card Number :</span>
                        <input type="text" id="cardNumber" placeholder="Card Number" maxlength="19" required>
                    </div>
                    <div class="input-box">
                        <span>Expiry Date :</span>
                        <input type="month" id="expiryDate" required>
                    </div>
                    <div class="input-box">
                        <span>CVV :</span>
                        <input type="text" id="CVV" placeholder="123" maxlength="3" required>
                    </div>
                </div>
            </div>
            <!-- Consider adding CSRF token if the form is going to submit data -->
            @csrf
            <button type="button" onclick="submitForm()" class="btn">Pay Now</button>
        </form>
        <p id="result"></p>
    </div>
    <!-- Use Laravel's asset() helper for JS -->
    <script src="{{ asset('JS/paymentjs.js') }}"></script>
</body>
</html>
