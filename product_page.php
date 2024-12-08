<!--********************************
Developer: Esraa Mubarak
University ID: 230087321
Function: This page is to display all the products/services we are selling
********************************/-->

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Charis+SIL:ital,wght@0,400;0,700;1,400;1,700&family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <!--Header Section-->
    <header>
    <div class="nav container">
            <a class="logo">Fitness</a>
            <nav>
                <a href="user_homepage.php">Home</a>
                <a href="About_Us.php">About Us</a>
                <a href="Calorie_Calculator.php">Calorie Calculator</a>
                <a href="#shop">Shop</a>
                <a href="#workouts">Workout Plans</a>
                <a href="#contact">Contact</a>
                <button id="cart-button" class="cart-button">
                    <i id="cart-icon" class="bx bxs-cart" data-quantity="0"></i>
                </button>
            </nav>
        </div>
    </header>

    
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
    
    <!-- Product Page Content -->
    <main>
        <h1>Explore Our Workouts and Supplements!</h1>

        <!-- Workout Plans Section -->
        <section id="workouts" class="tab-content active">
    <div class="section-header">
        <h2>Workout Plans</h2>
    </div>
    <div class="product-grid">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'Workout'");
        $stmt->execute();
        $workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($workouts as $workout): ?>
            <div class="product-card">
        <img src="Images/<?php echo htmlspecialchars($workout['productName']); ?>.jpg" alt="<?php echo htmlspecialchars($workout['productName']); ?>">
        <p><?php echo htmlspecialchars($workout['description']); ?></p>
        <div class="overlay">

            <h2 class="product-title"><?php echo htmlspecialchars($workout['productName']); ?></h2>   
            <p class="price">£<?php echo htmlspecialchars($workout['price']); ?></p>
            <label for="quantity-<?php echo htmlspecialchars($workout['ProductID']); ?>">Quantity:</label>
            <select class="product-quantity" id="quantity-<?php echo htmlspecialchars($workout['ProductID']); ?>">
                <?php for ($i = 1; $i <= $workout['stock']; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            
            <a href="product_details.php?product=<?php echo htmlspecialchars($workout['ProductID']); ?>">
                <button>View Details</button>
            </a>
        </div>
    </div>
        <?php endforeach; ?>
    </div>
</section>

        <!-- Supplements Section -->
        <section id="supplements" class="tab-content active">
            <h2>Supplements</h2>
            <div class="product-grid">
                <?php
                // Fetch supplements from the database
                $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'Supplement'");
                $stmt->execute();
                $supplements = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($supplements as $supplement): ?>
                    <div class="product-card">
                        <img src="Images/<?php echo htmlspecialchars($supplement['productName']); ?>.jpg" alt="<?php echo htmlspecialchars($supplement['productName']); ?>">
                        <p><?php echo htmlspecialchars($supplement['description']); ?></p>
                        <div class="overlay">
                            <h2 class="product-title"><?php echo htmlspecialchars($supplement['productName']); ?></h2>
                            
                            <p class="price">£<?php echo htmlspecialchars($supplement['price']); ?></p>
                            <label for="quantity-<?php echo htmlspecialchars($supplement['ProductID']); ?>">Quantity:</label>
                            <select class="product-quantity" id="quantity-<?php echo htmlspecialchars($supplement['ProductID']); ?>">
                            <?php for ($i = 1; $i <= $supplement['stock']; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                            
                            <a href="product_details.php?product=<?php echo htmlspecialchars($supplement['ProductID']); ?>">
                                <button>View Details</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>



        <section id="Vitamins" class="tab-content active">
            <h2>Vitamins</h2>
            <div class="product-grid">
                <?php
                // Fetch vitamins from the database
                $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'Vitamins'");
                $stmt->execute();
                $vitamins = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($vitamins as $vitamin): ?>
                    <div class="product-card">
                        <img src="Images/<?php echo htmlspecialchars($vitamin['productName']); ?>.jpg" alt="<?php echo htmlspecialchars($vitamin['productName']); ?>">
                        <p><?php echo htmlspecialchars($vitamin['description']); ?></p>

                        <div class="overlay">

                            <h2 class="product-title"><?php echo htmlspecialchars($vitamin['productName']); ?></h2>
                            <p class="price">£<?php echo htmlspecialchars($vitamin['price']); ?></p>
                            <label for="quantity-<?php echo htmlspecialchars($vitamin['ProductID']); ?>">Quantity:</label>
                            <select class="product-quantity" id="quantity-<?php echo htmlspecialchars($vitamin['ProductID']); ?>">
                            <?php for ($i = 1; $i <= $vitamin['stock']; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                            
                            <a href="product_details.php?product=<?php echo htmlspecialchars($vitamin['ProductID']); ?>">
                                <button>View Details</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>


        <section id="PreWorkout" class="tab-content active">
            <h2>PreWorkout</h2>
            <div class="product-grid">
                <?php
                // Fetch PreWorkout from the database
                $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'PreWorkout'");
                $stmt->execute();
                $PreWorkouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($PreWorkouts as $PreWorkout): ?>
                    <div class="product-card">
                        <img src="Images/<?php echo htmlspecialchars($PreWorkout['productName']); ?>.jpg" alt="<?php echo htmlspecialchars($PreWorkout['productName']); ?>">
                        <p><?php echo htmlspecialchars($PreWorkout['description']); ?></p>

                        <div class="overlay">

                            <h2 class="product-title"><?php echo htmlspecialchars($PreWorkout['productName']); ?></h2>
                            <p class="price">£<?php echo htmlspecialchars($PreWorkout['price']); ?></p>
                            <label for="quantity-<?php echo htmlspecialchars($PreWorkout['ProductID']); ?>">Quantity:</label>
                            <select class="product-quantity" id="quantity-<?php echo htmlspecialchars($PreWorkout['ProductID']); ?>">
                            <?php for ($i = 1; $i <= $PreWorkout['stock']; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                            
                            <a href="product_details.php?product=<?php echo htmlspecialchars($PreWorkout['ProductID']); ?>">
                                <button>View Details</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        
        <section id="ProteinFoods" class="tab-content active">
            <h2>Protein Food</h2>
            <div class="product-grid">
                <?php
                // Fetch vitamins from the database
                $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'ProteinFoods'");
                $stmt->execute();
                $ProteinFoods = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($ProteinFoods as $ProteinFood): ?>
                    <div class="product-card">
                        <img src="Images/<?php echo htmlspecialchars($ProteinFood['productName']); ?>.jpg" alt="<?php echo htmlspecialchars($ProteinFood['productName']); ?>">
                        <p><?php echo htmlspecialchars($ProteinFood['description']); ?></p>
                        
                        <div class="overlay">

                            <h2 class="product-title"><?php echo htmlspecialchars($ProteinFood['productName']); ?></h2>
                            <p class="price">£<?php echo htmlspecialchars($ProteinFood['price']); ?></p>
                            <label for="quantity-<?php echo htmlspecialchars($ProteinFood['ProductID']); ?>">Quantity:</label>
                            <select class="product-quantity" id="quantity-<?php echo htmlspecialchars($ProteinFood['ProductID']); ?>">
                            <?php for ($i = 1; $i <= $ProteinFood['stock']; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                            
                            <a href="product_details.php?product=<?php echo htmlspecialchars($ProteinFood['ProductID']); ?>">
                                <button>View Details</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>




    </main>
    <script src="JS/checkout.js"></script>

<footer>
    <div class="contact-details">
      <p>Aston St, Birmingham B4 7ET</p>
      <p>Phone: <a href="tel:0121 204 3000">0121 204 3000</a></p>
      <p>Email: <a href="mailto:info@example.com">Aston35@Fitness.com</a></p>
      <p>© 2024 Aston35Fitness. All rights reserved.</p>
      <a href="PrivacyPolicy.html">Privacy Policy</a> | <a href="Term Of Service.html">Terms of Service</a>
      </div>
  </footer>

  </section>
</body>
</html>
