<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Basket Page</title>
<link rel="stylesheet" href="css/Basketpage.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<div class="basket-container">
    <h1>Your Basket</h1>
    <div class="cart-content"></div>
    <div class="basket-summary">
        <h2>Total: <span class="total-price">£0.00</span></h2>
        <a href="Checkout.php" class="confirm-basket-button">Confirm Basket</a>
    </div>
</div>

<footer>
    <div class="contact-details">
        <p>Aston St, Birmingham B4 7ET</p>
        <p>Phone: <a href="tel:0121 204 3000">0121 204 3000</a></p>
        <p>Email: <a href="mailto:info@example.com">Aston35@Fitness.com</a></p>
        <p>© 2024 Aston35Fitness. All rights reserved.</p>
        <a href="PrivacyPolicy.html">Privacy Policy</a> | 
        <a href="TermOfService.html">Terms of Service</a>
    </div>
</footer>

<script src="JS/checkout1.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        loadCartItems();
    });

    document.getElementById("confirm-basket").addEventListener("click", () => {
        const cartItems = localStorage.getItem("cartItems");
        if (!cartItems) {
            alert("Your basket is empty!");
            return;
        }
        fetch("save_cart.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ userId: "123", items: JSON.parse(cartItems) }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Basket confirmed!");
                localStorage.clear();
                window.location.href = "Checkout.php";
            } else {
                console.error("Error saving basket:", data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    });
</script>
</body>
</html>
