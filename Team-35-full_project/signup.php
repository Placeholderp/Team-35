<?php
include 'db_connection.php';
session_start();

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a 32-character token
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error'] = "Invalid CSRF token.";
        header("Location: signup.php");
        exit;
    }

    // Collect form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $usertype = 'user';

    // Validation
    if (!isset($_POST['age-confirmation'])) {
        $_SESSION['error'] = "You must confirm that you are over the age of 18.";
        header("Location: signup.php");
        exit;
    }

    if (empty($username) || empty($email) || empty($name) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: signup.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
        header("Location: signup.php");
        exit;
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: signup.php");
        exit;
    }

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Username or email already exists.";
        header("Location: signup.php");
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, usertype, name) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $hashedPassword, $usertype, $name])) {
        $_SESSION['success'] = "Registration successful!";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['error'] = "Error: Unable to register.";
        header("Location: signup.php");
        exit;
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aston Fitness - Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="header-container">
        <img src="Images/team_logo-removebg-preview.png" alt="Team 35 Logo" class="logo">
        <i class="bx bx-menu" id="menu-icon"></i>
        <nav id="nav-menu">
            <a href="About_Us.php">About Us</a>
            <a href="Calorie_Calculator.php">Calorie Calculator</a>
            <a href="contactUs.php">Contact</a>
            <button id="cart-button">
                <i id="cart-icon" class="bx bxs-cart" data-quantity="0"></i>
            </button>
        </nav>
    </div>
</header>
    <div class="page-container">
        <div class="signup-box">
            <h1>Sign Up</h1>
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p style='color:red;'>{$_SESSION['error']}</p>";
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo "<p style='color:green;'>{$_SESSION['success']}</p>";
                unset($_SESSION['success']);
            }
            ?>
            <form action="signup.php" method="POST">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="input-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" placeholder="Enter a username" required>
                </div>
                <div class="input-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="input-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>
                <div class="input-group">
                    <label for="confirm-password">Confirm Password <span class="required">*</span></label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
                </div>
                <div class="input-group">
                    <label for="age-confirmation">
                        <input type="checkbox" id="age-confirmation" name="age-confirmation" required>
                        I confirm that I am over the age of 18 <span class="required">*</span>
                    </label>
                </div>
                <div class="submit-group">
                    <input type="submit" value="Sign Up">
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<script>
document.getElementById('confirm-password').addEventListener('input', function () {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;

    if (password !== confirmPassword) {
        this.setCustomValidity("Passwords do not match.");
    } else {
        this.setCustomValidity("");
    }
});
</script>
