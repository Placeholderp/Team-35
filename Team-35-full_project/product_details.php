
<?php
include 'db_connection.php'; // Include the database connection

// Parse the product ID from the URL
$productID = isset($_GET['product']) ? intval($_GET['product']) : 0;

// Fetch product details from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE ProductID = ?");
$stmt->execute([$productID]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If no product is found, redirect to a 404 page or show an error
if (!$product) {
    die("Product not found.");
}


$imageFolder = "Images/";
$imageFile = $imageFolder . htmlspecialchars($product['productName']) . ".jpg"; // Example: Images/Cardio Routine.jpg
$imageExists = file_exists($imageFile); // Check if the image file exists
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - FitStore</title>
    <link rel="stylesheet" href="css/product_page1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

</head>
<body>
    <!-- Header -->
    <header>
    <div class="header-container">
        <img src="Images/team_logo-removebg-preview.png" alt="Team 35 Logo" class="logo">
        <i class="bx bx-menu" id="menu-icon"></i>
        <nav id="nav-menu">
            <a href="user_homepage.php">Home</a>
            <a href="About_Us.php">About Us</a>
            <a href="Calorie_Calculator.php">Calorie Calculator</a>
            <a href="product_page.php">Product Page</a>
            <a href="contactUs.php">Contact</a>
            <button id="cart-button">
                <i id="cart-icon" class="bx bxs-cart" data-quantity="0"></i>
            </button>
        </nav>
    </div>
</header>

    <!-- Cart Dropdown -->
    <div class="cart">
        <h2 class="cart-title">Your Cart</h2>
        <div class="cart-content"></div>
        <div class="total">
            <div class="total-title">Total</div>
            <div class="total-price">£0</div>
        </div>
        <button type="button" class="btn-buy" onclick="checkCartBeforeCheckout()">View Basket</button>
        <i id="close-cart" class="bx bx-x"></i>
    </div>

    <!-- Main Content -->
    <!-- Product Details -->
<div class="product-details-container">
    <div class="product-image">
        <img id="product-image" src="<?php echo $imageExists ? $imageFile : 'Images/default.jpg'; ?>" alt="Product Image">
    </div>
    <div class="product-info">
        <h2 class="product-title"><?php echo htmlspecialchars($product['productName']); ?></h2>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <p class="price">£<?php echo number_format($product['price'], 2); ?></p>


        <label for="quantity">Quantity:</label>
        <select id="quantity" class="product-quantity">
            <?php for ($i = 1; $i <= $product['stock']; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <p><?php echo $product['stock']; ?> available</p>

        <button class="add-cart">Add to Cart</button>
        
            <button onclick="checkCartBeforeCheckout()">Checkout Now</button>
    </div>
</div>



    <script>
        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            alert(`${quantity} unit(s) of <?php echo htmlspecialchars($product['productName']); ?> added to your bag!`);
        }

        function checkoutNow() {
            const quantity = document.getElementById('quantity').value;
            window.location.href = `checkout.php?product=<?php echo $productID; ?>&quantity=${quantity}`;
        }
    </script>



    
    <footer>
        <div class="contact-details">
          <p>Aston St, Birmingham B4 7ET</p>
          <p>Phone: <a href="tel:0121 204 3000">0121 204 3000</a></p>
          <p>Email: <a href="mailto:info@example.com">Aston35@Fitness.com</a></p>
          <p>© 2024 Aston35Fitness. All rights reserved.</p>
          <a href="PrivacyPolicy.html">Privacy Policy</a> | <a href="Term Of Service.html">Terms of Service</a>
          </div>
      </footer>
      <script src="JS/checkout.js"></script>

</body>
</html>
