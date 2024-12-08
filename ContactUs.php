<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <link rel="stylesheet" href="css/About_Us.css">
</head>
<body>

  <!-- Header -->
  <header>
    <div class="logo">
      
      
    </div>
    <div class="navbar">
    <img src="Images/team_logo-removebg-preview.png" alt="Team 35 Logo" width="70" height="70">
    <a href="user_homepage.php">Home</a>
    
    
    <a href="Calorie_Calculator.php">Calorie Calculator</a>
    <a href="product_page.php">Product Page</a>
    <a href="About_Us.php">About Us</a>
    <a href="user_homepage.php">Home</a>
      <div class="dropdown">
        <button class="dropbtn">Dropdown
          <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
          <a href="#">Products & Services</a>
          <a href="#">Previous Orders</a>
          <a href="#home">Account</a>
        </div>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h1>Get in Touch</h1>
    <p>We're here to help with any questions you have.</p>
  </section>

  <!-- Contact Form -->
  <section class="contact-form">
    <form id="Contact" method="post">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Phone Number (optional)</label>
      <input type="tel" id="phone" name="phone">

      <label for="subject">Subject</label>
      <input type="text" id="subject" name="subject" required>

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="5" required></textarea>

      <button type="button" onclick="submitForm()">Send Message</button>
    </form>
  </section>

  <!-- Footer -->
  <footer>
    <div class="contact-details">
      <p>Aston St, Birmingham B4 7ET</p>
      <p>Phone: <a href="tel:0121 204 3000">0121 204 3000</a></p>
      <p>Email: <a href="mailto:info@example.com">Aston35@Fitness.com</a></p>
      <p>© 2024 Aston35Fitness. All rights reserved.</p>
      <a href="PrivacyPolicy.html">Privacy Policy</a> | <a href="Term Of Service.html">Terms of Service</a>
      </div>
  </footer>

<script src="ContactUs.js"></script>
</body>
</html>
