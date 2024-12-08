<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required.";
        header("Location: login.php");
        exit;
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful - set session variables
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['usertype'] = $user['usertype'];

        // Redirect based on user type
        if ($user['usertype'] === 'admin') {
            header("Location: admin_homepage.php");
        } else {
            header("Location: user_homepage.php");
        }
        exit;
    } else {
        // Login failed
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aston Fitness - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="#home">Basket</a>
        <a href="#home">Account</a>
        <a href="#home">Product Page</a>
        <a href="#home">Home</a>
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
    <div class="page-container">
        <div class="login-box">
            <h1>Welcome to Aston Fitness!</h1>
            <!-- Display error messages if any -->
            <?php if (!empty($error_message)): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <form action="login_handler.php" method="POST">
                <div class="input-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="submit-group">
                    <input type="submit" value="Login">
                </div>
            </form>
            <div class="signup-prompt">
                <p>Haven't already created an account? <a href="signup.php">Click here to sign up!</a></p>
            </div>
        </div>
    </div>
</body>
</html>

