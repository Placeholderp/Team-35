<?php
/********************************
Developer: Oliver
University ID: 230163795
Function: Created the user homepage with navigation to other pages
********************************/
session_start();
if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'user') {
    header("Location: login.php");
    exit;
}
echo "Welcome, User " . htmlspecialchars($_SESSION['username']);
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
            <div class="section" style="background-image: url('images/basket.svg');">
                <h2>Basket</h2>
                <p>View items in your basket and proceed to checkout.</p>
                <a href="basket.php">Go to Basket</a>
            </div>

            <!-- Section 2: Account -->
            <div class="section" style="background-image: url('images/account.svg');">
                <h2>Account</h2>
                <p>View and update your account details, including your username and password.</p>
                <a href="account.php">Manage Account</a>
            </div>

            <!-- Section 3: Product Page -->
            <div class="section" style="background-image: url('images/product.svg');">
                <h2>Product Page</h2>
                <p>Explore our latest products and add them to your basket.</p>
                <a href="product_page.php">Browse Products</a>
            </div>

            <!-- Section 4: Previous Orders -->
            <div class="section" style="background-image: url('images/previous.svg');">
                <h2>Previous Orders</h2>
                <p>Track your previous orders and view receipts.</p>
                <a href="previous_orders.php">View Orders</a>
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

