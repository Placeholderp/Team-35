<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <link rel="stylesheet" href="css/About_Us.css">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<header class="navbar">
    <div class="header-container">
        <img src="Images/team_logo-removebg-preview.png" alt="Team 35 Logo" class="logo">
        <i class="bx bx-menu" id="menu-icon"></i>
        <nav id="nav-menu">
            <a href="user_homepage.php">Home</a>
            <a href="product_page.php">Product Page</a>
            <a href="Calorie_Calculator.php">Calorie Calculator</a>
            <a href="About_Us.php">About Us</a>
            <a href="contactUs.php">Contact</a>
        </nav>
    </div>
</header>

<!-- Contact Form Section -->
<section class="contact-form-section">
  <div class="contact-form-container">
    <h1>Get in Touch</h1>
    <p>We’re here to help with any questions you have. Please fill out the form below, and we’ll get back to you as soon as possible.</p>
    <form id="Contact" method="POST" action="submit_contact.php">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your full name" required>

      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" placeholder="Enter your email address" required>

      <label for="phone">Phone Number (optional)</label>
      <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">

      <label for="subject">Subject</label>
      <input type="text" id="subject" name="subject" placeholder="Enter the subject" required>

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="10" placeholder="Enter your message" required></textarea>

      <button type="submit">Send Message</button>
    </form>
  </div>
</section>

<!-- Footer Section -->
<footer>
  <div class="contact-details">
    <p>Aston St, Birmingham B4 7ET</p>
    <p>Phone: <a href="tel:0121 204 3000">0121 204 3000</a></p>
    <p>Email: <a href="mailto:Aston35@Fitness.com">Aston35@Fitness.com</a></p>
  </div>
  <p>© 2024 Aston35Fitness. All rights reserved.</p>
  <div class="footer-links">
    <a href="PrivacyPolicy.html">Privacy Policy</a> | 
    <a href="TermOfService.html">Terms of Service</a>
  </div>
</footer>
<script>
// Select menu icon and navigation menu
const menuIcon = document.getElementById('menu-icon');
const navMenu = document.getElementById('nav-menu');

// Toggle menu visibility on menu icon click
menuIcon.addEventListener('click', () => {
  navMenu.classList.toggle('active');
});

// Close the menu if the user clicks anywhere outside
document.addEventListener('click', (e) => {
  if (!menuIcon.contains(e.target) && !navMenu.contains(e.target)) {
    navMenu.classList.remove('active');
  }
});
</script>
</body>
</html>
