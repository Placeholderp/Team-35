<?php
session_start();
if ($_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit;
}
echo "Welcome, Admin " . htmlspecialchars($_SESSION['username']);
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
    <div class="navbar">
        <a href="#home">Home</a>
        <a href="#profile">Profile</a>
        <a href="#orders">My Orders</a>
        <a href="#settings">Settings</a>
        <a href="logout.php">Logout</a>
    </div>

    
</body>
</html>