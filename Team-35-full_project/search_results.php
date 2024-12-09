<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/style1.css"> 
</head>
<body>
    <div class="container">
        <?php
        include 'db_connection.php';

        if (isset($_GET['query'])) {
            $query = htmlspecialchars($_GET['query']);
            $stmt = $pdo->prepare("SELECT * FROM products WHERE productName LIKE :query");
            $stmt->execute(['query' => "%$query%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                echo "<h2>Search Results for '$query'</h2><div class='results-container'>";
                foreach ($results as $product) {
                    echo "<div class='product-card'>";
                    echo "<img src='Images/" . htmlspecialchars($product['productName']) . ".jpg' alt='" . htmlspecialchars($product['productName']) . "'>";
                    echo "<h2>" . htmlspecialchars($product['productName']) . "</h2>";
                    echo "<p>£" . htmlspecialchars($product['price']) . "</p>";
                    echo "<a href='product_details.php?product=" . htmlspecialchars($product['ProductID']) . "'><button>View Details</button></a>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<h2>No results found for '$query'</h2>";
            }
        }
        ?>
    </div>
</body>
</html>
