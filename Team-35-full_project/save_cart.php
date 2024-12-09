<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve cart data from POST request
$cartData = json_decode(file_get_contents("php://input"), true);

if ($cartData && isset($cartData['items'])) {
    $userId = $cartData['userId']; // Replace with actual user ID logic
    foreach ($cartData['items'] as $item) {
        $title = $conn->real_escape_string($item['title']);
        $price = $conn->real_escape_string($item['price']);
        $quantity = $conn->real_escape_string($item['quantity']);
        $productImg = $conn->real_escape_string($item['productImg']);

        $sql = "INSERT INTO cart (user_id, product_name, price, quantity, image) 
                VALUES ('$userId', '$title', '$price', '$quantity', '$productImg')";

        if (!$conn->query($sql)) {
            echo json_encode(["status" => "error", "message" => $conn->error]);
            exit;
        }
    }
    echo json_encode(["status" => "success", "message" => "Cart saved successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid cart data"]);
}

$conn->close();
?>
