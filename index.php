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

<!-- HERO SLIDER -->
<div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="uploads/A1.jpg" class="d-block w-100 hero-img" alt="Slide 1">
    </div>
    <div class="carousel-item">
      <img src="uploads/A2.jpg" class="d-block w-100 hero-img" alt="Slide 2">
    </div>
    <div class="carousel-item">
      <img src="uploads/A3.jpg" class="d-block w-100 hero-img" alt="Slide 3">
    </div>
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
  <div class="d-flex overflow-auto gap-2 px-3">
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
        echo "<p class='card-text text-muted'>\$ " . htmlspecialchars($price, ENT_QUOTES, 'UTF-8') . "</p>";

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
