<?php
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<?php include 'navbar.php'; ?>
<body class="bg-white text-dark">

<div class="bg-light py-5 text-center">
  <h1 class="display-5 fw-bold text-success">Welcome to Abdallah Clothing</h1>
  <p class="lead text-muted">Explore our fashionable collection for all ages</p>
</div>

<div class="container text-center my-4">
  <a href="products.php" class="btn category-btn me-2">All</a>
  <a href="products.php?category=Men" class="btn category-btn me-2">👔 Men</a>
  <a href="products.php?category=Women" class="btn category-btn me-2">👗 Women</a>
  <a href="products.php?category=Kids" class="btn category-btn">🧒 Kids</a>
</div>

<div class="container">
  <div class="row">
    <?php
      $filter = "";
      if (isset($_GET['category'])) {
          $cat = $_GET['category'];
          $filter = "WHERE categories.name = '$cat'";
      }

      $sql = "SELECT products.*, categories.name AS catname
              FROM products
              JOIN categories ON products.category_id = categories.id
              $filter";
      $result = mysqli_query($conn, $sql);

      while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        echo "<div class='col-md-4 mb-4'>";
        echo "<div class='card shadow-sm border-0 h-100'>";
        echo "<img src='uploads/{$row['image']}' class='card-img-top' style='height: 320px; object-fit: cover;'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title text-success fw-bold'>{$row['name']}</h5>";
        echo "<p class='card-text'>\$ {$row['price']}</p>";

        // Get stock per size
        $stock_sql = "SELECT size, quantity FROM product_sizes WHERE product_id = $id";
        $stock_result = mysqli_query($conn, $stock_sql);
        $stock_data = [];
        while ($srow = mysqli_fetch_assoc($stock_result)) {
            $stock_data[$srow['size']] = $srow['quantity'];
        }

        echo "<form action='add_to_cart.php' method='POST'>";
        echo "<input type='hidden' name='product_id' value='{$row['id']}'>";
        echo "<input type='hidden' name='size' id='selected-size-{$id}' required>";
        echo "<input type='hidden' name='quantity' value='1'>";

        // Size Buttons
        echo "<div class='mb-3 d-flex flex-wrap justify-content-center gap-2'>";
        foreach ($sizes as $sz) {
            $qty = $stock_data[$sz] ?? 0;
            $disabled = $qty == 0 ? 'disabled' : '';
            $class = $qty == 0 ? 'btn-outline-secondary text-decoration-line-through' : 'btn-outline-dark';
            echo "<button type='button' class='btn $class size-btn' 
                        data-product='$id' data-size='$sz' $disabled>$sz</button>";
        }
        echo "</div>";

        echo "<button type='submit' class='btn btn-success w-100'>Add to Cart 🛒</button>";
        echo "</form>";

        echo "</div></div></div>";
      }
    ?>
  </div>
</div>

<footer class="text-center mt-5 py-3 text-muted">
  &copy; <?php echo date("Y"); ?> Abdallah Clothing. All rights reserved.
</footer>

<!-- Size Selection Script -->
<script>
document.querySelectorAll('.size-btn').forEach(button => {
  button.addEventListener('click', () => {
    const productId = button.dataset.product;
    const size = button.dataset.size;
    document.getElementById('selected-size-' + productId).value = size;

    // Unselect all buttons for same product
    document.querySelectorAll(`.size-btn[data-product='${productId}']`).forEach(btn => {
      btn.classList.remove('btn-dark');
      btn.classList.add('btn-outline-dark');
    });

    // Highlight selected
    button.classList.remove('btn-outline-dark');
    button.classList.add('btn-dark');
  });
});
</script>

</body>
</html>
