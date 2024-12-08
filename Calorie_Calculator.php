<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calorie Calculator</title>
  <link rel="stylesheet" href="CSS/Calorie_Calculator.css">
  
</head>
<body>
  <div class="navbar">
    <a href="#home">Basket</a>
    <a href="About_Us.php">About Us</a>
    <a href="product_page.php">Product Page</a>
    <a href="user_homepage.php">Home</a>
    <div class="dropdown">
      <button class="dropbtn">Dropdown
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

  <div class="calculator">
    <h1>Calorie Calculator</h1>
    <form id="calorieForm">
      <label for="gender">Gender:</label>
      <select id="gender" required>
        <option value="">Select</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
      </select>

      <label for="age">Age:</label>
      <input type="number" id="age" placeholder="Age (years)" required min="1">

      <label for="weight">Weight:</label>
      <input type="number" id="weight" placeholder="Weight (kg)" required min="1">

      <label for="height">Height:</label>
      <input type="number" id="height" placeholder="Height (cm)" required min="1">

      <label for="activity">Activity Level:</label>
      <select id="activity" required>
        <option value="">Select</option>
        <option value="low">Low</option>
        <option value="intermediate">Intermediate</option>
        <option value="high">High</option>
      </select>

      <label for="goal">Goal:</label>
      <select id="goal" required>
        <option value="">Select</option>
        <option value="maintain">Maintain Weight</option>
        <option value="lose">Lose Weight</option>
        <option value="gain">Gain Muscle</option>
      </select>

      <button type="button" id="calculateBtn">Calculate</button>
      <button type="reset" id="resetBtn">Reset</button>
    </form>
    <div>
      <label for="resultBox">Result:</label>
      <input type="text" id="resultBox" readonly>
    </div>
  </div>
  <footer>
  <div class="contact-details">
    <p>Aston St, Birmingham B4 7ET</p>
    <p>Phone: <a href="tel:0121 204 3000">0121 204 3000</a></p>
    <p>Email: <a href="mailto:info@example.com">Aston35@Fitness.com</a></p>
    <p>© 2024 Aston35Fitness. All rights reserved.</p>
    <a href="PrivacyPolicy.html">Privacy Policy</a> | <a href="Term Of Service.html">Terms of Service</a>
    </div>
</footer>
  <script src="JS/Calorie_Calculator.js"></script>
</body>
</html>



