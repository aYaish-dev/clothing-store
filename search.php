<?php
include 'db.php';

$searchTerm = trim($_GET['q'] ?? '');
$like = "%{$searchTerm}%";

$stmt = mysqli_prepare($conn, "SELECT products.*, categories.name AS category_name
          FROM products
          LEFT JOIN categories ON products.category_id = categories.id
          WHERE products.name LIKE ? OR products.description LIKE ?");
mysqli_stmt_bind_param($stmt, "ss", $like, $like);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<?php include 'navbar.php'; ?>
<body>
<div class="container py-5">
  <h2 class="mb-4">🔍 Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
  <a href="index.php" class="btn btn-link mb-3">&larr; Back to Home</a>
  <div class="row">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100 product-card">
            <img src="uploads/<?php echo htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" style="height:300px; object-fit:cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
              <p class="text-muted mb-2">Category: <?php echo htmlspecialchars($row['category_name']); ?></p>
              <p class="fw-bold mb-3">$<?php echo htmlspecialchars($row['price']); ?></p>
              <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary mt-auto">View &amp; Select Size</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No products found matching your search.</p>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
