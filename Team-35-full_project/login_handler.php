<?php
include 'db_connection.php'; // Include your database connection file
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required.";
        header("Location: login.php"); // Redirect back to login page
        exit;
    }

    try {
        // Check if user exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify user and password
        if ($user && password_verify($password, $user['password'])) {
            // Login successful - set session variables
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];

            // Redirect based on user type
            if ($user['usertype'] === 'admin') {
                header("Location: admin_homepage.php"); // Redirect to admin homepage
            } else {
                header("Location: user_homepage.php"); // Redirect to user homepage
            }
            exit;
        } else {
            // Login failed - incorrect username or password
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.php"); // Redirect back to login page
            exit;
        }
    } catch (PDOException $e) {
        // Handle database errors
        $_SESSION['error'] = "An error occurred while logging in. Please try again.";
        header("Location: login.php");
        exit;
    }
}
?>
