<!--!********************************
Developer: Neel Odedra
University ID: 230160794
Function: I have created the login page which will authenticate users to login.
********************************/-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aston Fitness - Sign Up</title>
    <link rel="stylesheet" href="{{asset('css/signupandlogin.css'}}">
</head>
<body>
    <div class="navbar">
        <a href="#home">Basket</a>
        <a href="#home">Account</a>
        <a href="#home">Product Page</a>
        <a href="#home">Home</a>
        <div class="dropdown">
          <button class="dropbtn">Dropdown
            <i class="fa fa-caret-down"></i>
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-content">
            <a href="#">About us</a>
            <a href="#">Contact us</a>
            <a href="#">Products & Services</a>
            <a href="#">Previous Orders</a>
          </div>
          </div>
    </div>
    <div class="page-container">
        <div class="signup-box">
            <h1> Sign Up</h1>
            <form action="">

                <!-- Input field for the username -->
                <div class="input-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" placeholder="Enter a username" required>
                </div>
                <!-- Input field for email -->

                <div class="input-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <!-- Input field for users phone number -->
                <div class="input-group">
                    <label for="phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                </div>
                <!-- Input field for password -->
          
                <div class="input-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>

                <div class="input-group">
                  <!-- Confirm password input field. -->
                  
                    <label for="confirm-password">Confirm Password <span class="required">*</span></label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
                </div>
                <!-- Checkbox to confirm age -->

                <div class="input-group">
                    <label for="age-confirmation">
                        <input type="checkbox" id="age-confirmation" name="age-confirmation" required>
                        I confirm that I am over the age of 18 <span class="required">*</span>
                    </label>
                </div>
                <!-- Submit button -->
                <div class="submit-group">
                    <input type="submit" value="Sign Up">
                </div>
                
                <!-- Direct link to the login page -->

            </form>
            <div class="login-prompt">
                <p>If you already have an account, click this link to redirect you to the login page. <a href="login">Click here to Login!</a></p>
                </div>
        </div>
                <!-- Testimonials section -->

        <div class="testimonial-section">
            <h2>What our customers say about us:</h2>
            <div class="testimonial-box">
                <blockquote>“Very useful and engaging with friends” - R</blockquote>
                <blockquote>“Very motivational with different products and personalized recommendations.” – N</blockquote>
            </div>

        </div>
    </div>
</body>
</html>
