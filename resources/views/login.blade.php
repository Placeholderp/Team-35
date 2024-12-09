<!--!********************************
Developer: Neel Odedra
University ID: 230160794
Function: I have created the login page which will authenticate users and allow them to access the website.
********************************/-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title which is shown on the tab -->
    <title>Aston Fitness - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!--Navigation bar code with links to different pages on the website.-->
    <div class="navbar">
        <a href="#home">Basket</a>
        <a href="#home">Account</a>
        <a href="#home">Product Page</a>
        <a href="#home">Home</a>
        <div class="dropdown">
          <button class="dropbtn">Dropdown
            <i class="fa fa-caret-down"></i>
          </button>
          <!-- Contains all the pages when users hover over drop menu in navigation bar.  -->
          <div class="dropdown-content">
            <a href="#">About us</a>
            <a href="#">Contact us</a>
            <a href="#">Products & Services</a>
            <a href="#">Previous Orders</a>
          </div>
        </div>
      </div>
      <!-- Main container and this is where all of the pages content is shown. -->
    <div class="page-container">
        <div class="login-box">
            <!-- Header for welcoming users on the page -->
            <h1>Welcome to Aston Fitness!</h1>
            <form action="">
                <!-- Input fields for user name and password -->
                <div class="input-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                    <!-- Submit button so that users can proceed to next page when logged in  -->
                <div class="submit-group">
                    <input type="submit" value="Login">
                </div>
            </form>
            <!-- If users have not created an account they can click this link to redirect them to the signup page.  -->
            <div class="signup-prompt">
                <p>Haven't already created an account? <a href="signup.html">Click here to sign up!</a></p>
                </div>
        </div>
    </div>
</body>
</html>