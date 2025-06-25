<?php
require_once 'session.php';
include 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT products.*, categories.name AS catname FROM products JOIN categories ON products.category_id = categories.id WHERE products.id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header('Location: index.php');
    exit();
}

$sizes = ['XS','S','M','L','XL','XXL'];
$stock_stmt = mysqli_prepare($conn, "SELECT size, quantity FROM product_sizes WHERE product_id = ?");
mysqli_stmt_bind_param($stock_stmt, 'i', $id);
mysqli_stmt_execute($stock_stmt);
$stock_res = mysqli_stmt_get_result($stock_stmt);
$stock_data = [];
while ($row = mysqli_fetch_assoc($stock_res)) {
    $stock_data[$row['size']] = $row['quantity'];
}
?>
<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<?php include 'navbar.php'; ?>
<body>
<div class="container py-5">
  <div class="row">
    <div class="col-md-6 text-center">
      <img src="uploads/<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid product-detail-img">
    </div>
    <div class="col-md-6">
      <h2><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
      <p class="text-muted">Category: <?php echo htmlspecialchars($product['catname'], ENT_QUOTES, 'UTF-8'); ?></p>
      <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php
  $discount = (float)($product['discount'] ?? 0);
  $price = (float)$product['price'];
  $final = $discount > 0 ? $price - ($price * ($discount / 100)) : $price;
?>
      <?php if ($discount > 0): ?>
        <p class="h4 mb-4"><span class="text-decoration-line-through text-muted">$<?php echo number_format($price, 2); ?></span> <span class="text-success">$<?php echo number_format($final, 2); ?></span></p>
      <?php else: ?>
        <p class="h4 text-success mb-4">$<?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>

      <form action="add_to_cart.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
        <input type="hidden" name="size" id="selected-size-<?php echo $id; ?>" required>
        <input type="hidden" name="quantity" value="1">
        <div class="mb-3 d-flex flex-wrap gap-2">
<?php
foreach ($sizes as $sz) {
    $qty = $stock_data[$sz] ?? 0;
    $disabled = $qty == 0 ? 'disabled' : '';
    $class = $qty == 0 ? 'btn-outline-secondary' : 'btn-outline-dark';
    echo "          <button type='button' class='btn $class size-btn' data-product='$id' data-size='$sz' $disabled>$sz</button>\n";
}
?>
        </div>
        <button type="submit" class="btn btn-dark">Add to Cart 🛒</button>
      </form>
    </div>
  </div>
</div>
<script>
document.querySelectorAll('.size-btn').forEach(button => {
  button.addEventListener('click', () => {
    const productId = button.dataset.product;
    const size = button.dataset.size;
    document.getElementById('selected-size-' + productId).value = size;
    document.querySelectorAll(`.size-btn[data-product='${productId}']`).forEach(btn => {
      btn.classList.remove('btn-dark');
      btn.classList.add('btn-outline-dark');
    });
    button.classList.remove('btn-outline-dark');
    button.classList.add('btn-dark');
  });
});
</script>
</body>
</html>
