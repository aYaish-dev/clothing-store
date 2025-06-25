<?php
include 'db.php';
require_once 'session.php';
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

<div class="container-fluid my-4">
  <div class="d-flex overflow-auto gap-2 px-3">
    <a href="products.php" class="btn category-btn flex-shrink-0">All</a>
    <a href="products.php?category=Men" class="btn category-btn flex-shrink-0">👔 Men</a>
    <a href="products.php?category=Women" class="btn category-btn flex-shrink-0">👗 Women</a>
    <a href="products.php?category=Kids" class="btn category-btn flex-shrink-0">🧒 Kids</a>
  </div>
</div>

<div class="container">
  <div class="row">
    <?php
      $filter = "";
      if (isset($_GET['category'])) {
          $cat = trim($_GET['category']);
          $filter = "WHERE categories.name = ?";
          $sql = "SELECT products.*, categories.name AS catname
                  FROM products
                  JOIN categories ON products.category_id = categories.id
                  $filter";
          $stmt = mysqli_prepare($conn, $sql);
          mysqli_stmt_bind_param($stmt, "s", $cat);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
      } else {
          $sql = "SELECT products.*, categories.name AS catname
                  FROM products
                  JOIN categories ON products.category_id = categories.id";
          $result = mysqli_query($conn, $sql);
      }

      while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        $pname = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
        $pimage = htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8');
        $pprice = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');

        echo "<div class='col-lg-3 col-md-4 col-sm-6 mb-4'>";
        echo "<div class='card product-card border-0 h-100'>";
        echo "<img src='uploads/{$pimage}' alt='{$pname}' class='card-img-top product-img'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title text-success fw-bold'>{$pname}</h5>";
        echo "<p class='card-text'>\$ {$pprice}</p>";

        // Get stock per size
        $stock_sql = "SELECT size, quantity FROM product_sizes WHERE product_id = $id";
        $stock_result = mysqli_query($conn, $stock_sql);
        $stock_data = [];
        while ($srow = mysqli_fetch_assoc($stock_result)) {
            $stock_data[$srow['size']] = $srow['quantity'];
        }

        echo "<form action='add_to_cart.php' method='POST'>";
        echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>";
        echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>";
        echo "<input type='hidden' name='size' id='selected-size-{$id}' required>";
        echo "<input type='hidden' name='quantity' value='1'>";

        // Size Buttons
        echo "<div class='mb-3 d-flex flex-wrap justify-content-center gap-2'>";
        foreach ($sizes as $sz) {
            $qty = $stock_data[$sz] ?? 0;
            $disabled = $qty == 0 ? 'disabled' : '';
            $class = $qty == 0 ? 'btn-outline-secondary' : 'btn-outline-dark';
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

<footer class="text-center py-4 footer mt-5">
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
