<?php
include 'db_connection.php'; // Include the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Store - Product Page</title>
    <link rel="stylesheet" href="css/product_page.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>

<!-- Header Section -->
<header>
    <div class="header-container">
        <img src="Images/team_logo-removebg-preview.png" alt="Team 35 Logo" class="logo">
        <i class="bx bx-menu" id="menu-icon"></i>
        <nav id="nav-menu">
            <a href="user_homepage.php">Home</a>
            <a href="About_Us.php">About Us</a>
            <a href="Calorie_Calculator.php">Calorie Calculator</a>
            <a href="contactUs.php">Contact</a>

            <form action="search_results.php" method="GET" class="search-form">
    <input type="text" id="search-query" name="query" placeholder="Search products..." required>
    <button type="submit"><i class="bx bx-search"></i></button>
</form>




            <button id="cart-button">
                <i id="cart-icon" class="bx bxs-cart" data-quantity="0"></i>
            </button>
        </nav>
    </div>
</header>

<!-- Cart Section -->
<div class="cart">
    <h2 class="cart-title">Your Cart</h2>
    <div class="cart-content">
        
        <!-- Cart Items will be dynamically added here -->
    </div>
    <div class="total">
        <div class="total-title">Total</div>
        <div class="total-price">£0</div>
    </div>
    <button type="button" class="btn-buy" onclick="checkCartBeforeCheckout()">View Basket</button>
    <i id="close-cart" class="bx bx-x"></i>
</div>


<!-- Main Content -->
<main>
    <h1>Explore Our Workouts and Supplements!</h1>

    
   <!-- Workout Plans Section -->
<section id="workouts">
    <h2>Workout Plans</h2>
    <div class="product-grid" id="workout-products">
        <?php
        include 'db_connection.php';
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'Workout'");
        $stmt->execute();
        $workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

 foreach ($workouts as $workout): ?>
            <div class="product-card product-info" 
                 data-name="<?php echo strtolower(htmlspecialchars($workout['productName'])); ?>">
                <img src="Images/<?php echo htmlspecialchars($workout['productName']); ?>.jpg" 
                     alt="<?php echo htmlspecialchars($workout['productName']); ?>" class="product-image">
                <h2 class="product-title"><?php echo htmlspecialchars($workout['productName']); ?></h2>
                <p class="price">£<?php echo htmlspecialchars($workout['price']); ?></p>
                <a href="product_details.php?product=<?php echo htmlspecialchars($workout['ProductID']); ?>">
                    <button>View Details</button>
                </a>
            </div>
        <?php endforeach; ?>
        
    </div>
</section>

<!-- Supplements Section -->
<section id="supplements">
    <h2>Supplements</h2>
    <div class="product-grid" id="supplement-products">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'Supplement'");
        $stmt->execute();
        $supplements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($supplements as $supplement): ?>
            <div class="product-card product-info" 
                 data-name="<?php echo strtolower(htmlspecialchars($supplement['productName'])); ?>">
                <img src="Images/<?php echo htmlspecialchars($supplement['productName']); ?>.jpg" 
                     alt="<?php echo htmlspecialchars($supplement['productName']); ?>" class="product-image">
                <h2 class="product-title"><?php echo htmlspecialchars($supplement['productName']); ?></h2>
                <p class="price">£<?php echo htmlspecialchars($supplement['price']); ?></p>
                <a href="product_details.php?product=<?php echo htmlspecialchars($supplement['ProductID']); ?>">
                    <button>View Details</button>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Vitamins Section -->
<section id="vitamins">
    <h2>Vitamins</h2>
    <div class="product-grid" id="vitamin-products">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'Vitamins'");
        $stmt->execute();
        $vitamins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($vitamins as $vitamin): ?>
            <div class="product-card product-info" 
                 data-name="<?php echo strtolower(htmlspecialchars($vitamin['productName'])); ?>">
                <img src="Images/<?php echo htmlspecialchars($vitamin['productName']); ?>.jpg" 
                     alt="<?php echo htmlspecialchars($vitamin['productName']); ?>" class="product-image">
                <h2 class="product-title"><?php echo htmlspecialchars($vitamin['productName']); ?></h2>
                <p class="price">£<?php echo htmlspecialchars($vitamin['price']); ?></p>
                <a href="product_details.php?product=<?php echo htmlspecialchars($vitamin['ProductID']); ?>">
                    <button>View Details</button>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- PreWorkout Section -->
<section id="PreWorkout" class="tab-content active">
    <h2>PreWorkout</h2>
    <div class="product-grid">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'PreWorkout'");
        $stmt->execute();
        $PreWorkouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($PreWorkouts as $PreWorkout): ?>
            <div class="product-card product-info" 
                 data-name="<?php echo strtolower(htmlspecialchars($PreWorkout['productName'])); ?>">
                <img src="Images/<?php echo htmlspecialchars($PreWorkout['productName']); ?>.jpg" 
                     alt="<?php echo htmlspecialchars($PreWorkout['productName']); ?>" class="product-image">
                <h2 class="product-title"><?php echo htmlspecialchars($PreWorkout['productName']); ?></h2>
                <p class="price">£<?php echo htmlspecialchars($PreWorkout['price']); ?></p>
                <a href="product_details.php?product=<?php echo htmlspecialchars($PreWorkout['ProductID']); ?>">
                    <button>View Details</button>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Protein Food Section -->
<section id="ProteinFoods" class="tab-content active">
    <h2>Protein Food</h2>
    <div class="product-grid">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'ProteinFoods'");
        $stmt->execute();
        $ProteinFoods = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($ProteinFoods as $ProteinFood): ?>
            <div class="product-card product-info" 
                 data-name="<?php echo strtolower(htmlspecialchars($ProteinFood['productName'])); ?>">
                <img src="Images/<?php echo htmlspecialchars($ProteinFood['productName']); ?>.jpg" 
                     alt="<?php echo htmlspecialchars($ProteinFood['productName']); ?>" class="product-image">
                <h2 class="product-title"><?php echo htmlspecialchars($ProteinFood['productName']); ?></h2>
                <p class="price">£<?php echo htmlspecialchars($ProteinFood['price']); ?></p>
                <a href="product_details.php?product=<?php echo htmlspecialchars($ProteinFood['ProductID']); ?>">
                    <button>View Details</button>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

</main>

<!-- Footer Section -->
<footer>
    <div class="contact-details">
        <p>Aston St, Birmingham B4 7ET</p>
        <p>Phone: <a href="tel:0121 204 3000">0121 204 3000</a></p>
        <p>Email: <a href="mailto:Aston35@Fitness.com">Aston35@Fitness.com</a></p>
        <p>© 2024 Aston35Fitness. All rights reserved.</p>
        <a href="PrivacyPolicy.html">Privacy Policy</a> | 
        <a href="TermsOfService.html">Terms of Service</a>
    </div>
</footer>

<!-- JavaScript -->
<script src="JS/checkout.js"></script>
</body>
</html>
