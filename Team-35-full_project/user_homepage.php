<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'user') {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/user_home.css">
</head>
<body>
    <!-- Navigation Bar -->
    <<div class="navbar">
    <a href="#cart" class="cart-icon">🛒</a>
    <a href="ContactUs.php">Contact</a>
    <a href="Calorie_Calculator.php">Calorie Calculator</a>
    <a href="product_page.php">Product Page</a>
    <a href="About_Us.php">About Us</a>
    <a href="user_homepage.php">Home</a>
    
    <div class="dropdown">
    <img src="Images/team_logo-removebg-preview.png" alt="Team 35 Logo" width="50" height="50">
      </div>
    </div>
  </div>

  <div class="dashboard-sections">
            <!-- Section 1: Basket -->
            <div class="section">
                <h2>Calorie Calculator</h2>
                <p>Check what fitness plan works best for you to achieve your fitness goals </p>
                <a href="Calorie_Calculator.php">Go to Calorie Calculator</a>
            </div>

            <!-- Section 2: Account -->
            <div class="section">
                <h2>Contact</h2>
                <p>Feel free to contact us</p>
                <a href="ContactUs.php">Contact Us</a>
            </div>

            <!-- Section 3: Product Page -->
            <div class="section">
                <h2>Product Page</h2>
                <p>Explore our latest products and add them to your basket.</p>
                <a href="product_page.php">Browse Products</a>
            </div>

            <!-- Section 4: Previous Orders -->
            <div class="section">
                <h2>About Us</h2>
                <p>Get to know more about us and our company</p>
                <a href="About_Us.php">About Us</a>
            </div>
        </div>
    </div>
    </div>
    <footer>
    <div class="contact-details">
      <p>Aston St, Birmingham B4 7ET</p>
      <p>Phone: <a href="tel:0121 204 3000">0121 204 3000</a></p>
      <p>Email: <a href="mailto:info@example.com">Aston35@Fitness.com</a></p>
      <p>© 2024 Aston35Fitness. All rights reserved.</p>
      <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
      </div>
  </footer>
</body>
</html>