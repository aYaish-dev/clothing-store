<?php
include 'db.php';

$searchTerm = $_GET['q'] ?? '';

$query = "SELECT products.*, categories.name AS category_name 
          FROM products 
          LEFT JOIN categories ON products.category_id = categories.id
          WHERE products.name LIKE '%$searchTerm%' OR products.description LIKE '%$searchTerm%'";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>🔍 Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
<a href="index.php">← Back to Home</a>

<div class="product-grid">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="product-card">
                <img src="uploads/<?php echo $row['image']; ?>" alt="Product Image" class="product-img">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p><strong>$<?php echo $row['price']; ?></strong></p>
                <p class="category">Category: <?php echo htmlspecialchars($row['category_name']); ?></p>
                <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn">Add to Cart 🛒</a>
            </div>
        <?php } ?>
    <?php else: ?>
        <p>No products found matching your search.</p>
    <?php endif; ?>
</div>

</body>
</html>
