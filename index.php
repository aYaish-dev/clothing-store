<?php
require_once 'session.php';
include 'db.php';

$filter = "";
$result = null;
if (isset($_GET['category'])) {
    $category = trim($_GET['category']);
    $filter = "WHERE categories.name = ?";
    $query = "SELECT products.*, categories.name AS catname FROM products JOIN categories ON products.category_id = categories.id $filter";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $query = "SELECT products.*, categories.name AS catname FROM products JOIN categories ON products.category_id = categories.id";
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<?php include 'navbar.php'; ?>
<body>

<!-- BANNER -->
<div class="hero text-center">
  <h1 class="display-4 fw-bold">DeFacto Fashion</h1>
  <p class="lead text-muted">Latest styles for everyone</p>
  <div class="mt-4">
    <a href="products.php" class="btn btn-dark px-4 me-2">🛍️ Browse Products</a>
    <a href="#" class="btn btn-outline-secondary px-4">Learn More</a>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- FILTER BUTTONS -->
<div class="container-fluid my-3">
  <div class="d-flex justify-content-center overflow-auto gap-2 px-3">
    <a href="index.php" class="btn btn-outline-secondary flex-shrink-0 category-btn">All</a>
    <a href="index.php?category=Men" class="btn btn-outline-dark flex-shrink-0 category-btn">👔 Men</a>
    <a href="index.php?category=Women" class="btn btn-outline-danger flex-shrink-0 category-btn">👗 Women</a>
    <a href="index.php?category=Kids" class="btn btn-outline-primary flex-shrink-0 category-btn">🧒 Kids</a>
  </div>
</div>

<!-- PRODUCTS GRID -->
<div class="container">
  <div class="row">
    <?php 
    $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

    while ($row = mysqli_fetch_assoc($result)):
        $id = $row['id'];
        $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
        $price = $row['price'];
        $discount = $row['discount'];
        $displayPrice = "$" . htmlspecialchars(number_format($price, 2), ENT_QUOTES, 'UTF-8');
        if ($discount > 0) {
            $final = $price - ($price * $discount / 100);
            $displayPrice = "<span class='text-decoration-line-through'>$" . htmlspecialchars(number_format($price, 2), ENT_QUOTES, 'UTF-8') . "</span> <span class='text-danger'>$" . number_format($final, 2) . "</span>";
        }
        $image = htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8');

        // Fetch stock per size
        $stock_sql = "SELECT size, quantity FROM product_sizes WHERE product_id = $id";
        $stock_result = mysqli_query($conn, $stock_sql);
        $stock_data = [];
        while ($stock_row = mysqli_fetch_assoc($stock_result)) {
            $stock_data[$stock_row['size']] = $stock_row['quantity'];
        }

        echo "<div class='col-lg-3 col-md-4 col-sm-6 mb-4'>";
        echo "<div class='card product-card h-100 shadow-sm'>";
        echo "<img src='uploads/$image' alt='$name' class='card-img-top product-img'>";
        echo "<div class='card-body d-flex flex-column'>";
        echo "<h5 class='card-title'>$name</h5>";
        echo "<p class='card-text'>$displayPrice</p>";

        echo "<form action='add_to_cart.php' method='POST'>";
        echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>";
        echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . "'>";
        echo "<input type='hidden' name='size' id='selected-size-$id' required>";
        echo "<input type='hidden' name='quantity' value='1'>";

        // ✅ Size buttons
        echo "<div class='mb-3 d-flex flex-wrap justify-content-center gap-2'>";
        foreach ($sizes as $sz) {
            $qty = $stock_data[$sz] ?? 0;
            $disabled = $qty == 0 ? 'disabled' : '';
            $class = $qty == 0 ? 'btn-outline-secondary' : 'btn-outline-dark';
            echo "<button type='button' class='btn $class size-btn' 
                    data-product='$id' data-size='$sz' $disabled>$sz</button>";
        }
        echo "</div>";

        echo "<button type='submit' class='btn btn-dark mt-auto w-100'>Add to Cart 🛒</button>";
        echo "</form>";

        echo "</div></div></div>";
    endwhile;
    ?>
  </div>
</div>

<!-- FOOTER -->
<footer class="text-center py-4 footer mt-5">
  <p class="mb-0">© <?php echo date("Y"); ?> Abdallah Clothing. All rights reserved.</p>
  <small><a href="#">About</a> · <a href="#">Contact</a> · <a href="#">Instagram</a></small>
</footer>

<!-- JS for selecting sizes -->
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
