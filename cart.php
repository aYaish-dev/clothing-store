<?php
session_start();
include 'db.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<?php include 'navbar.php'; ?>
<body class="bg-light">

<div class="container-fluid py-5">
  <div class="row">
    <!-- Cart Items -->
    <div class="col-md-8">
      <h4 class="mb-4 fw-bold">🛒 Your Shopping Cart (<?= count($cart) ?> items)</h4>
      <div id="cart-items">
        <?php if (empty($cart)): ?>
          <div class="alert alert-info text-center">Your cart is empty!</div>
        <?php else: ?>
          <?php foreach ($cart as $key => $item):
              $product = $item['product'];
              $qty = $item['quantity'];
              $size = $item['size'];
              $subtotal = $product['price'] * $qty;
              $total += $subtotal;

              // Get max stock for the selected product and size
              $pid = $product['id'];
              $stock_query = mysqli_query($conn, "SELECT quantity FROM product_sizes WHERE product_id = $pid AND size = '$size'");
              $stock_row = mysqli_fetch_assoc($stock_query);
              $maxStock = $stock_row['quantity'] ?? 0;
          ?>
          <div class="card mb-3 shadow-sm cart-item" data-key="<?= $key ?>" data-stock="<?= $maxStock ?>">
            <div class="row g-0 align-items-center">
              <div class="col-md-3 text-center">
                <img src="uploads/<?= $product['image'] ?>" class="img-fluid rounded-start" style="height: 150px; object-fit: contain;">
              </div>
              <div class="col-md-6">
                <div class="card-body">
                  <h5 class="card-title mb-1"><?= $product['name'] ?></h5>
                  <p class="mb-1 text-muted">Size: <?= $size ?></p>
                  <p class="mb-0 fw-bold text-danger">$<?= number_format($product['price'], 2) ?></p>
                </div>
              </div>
              <div class="col-md-3 text-center">
                <div class="d-flex justify-content-center align-items-center mb-2">
                  <button class="btn btn-outline-secondary btn-sm qty-btn" data-action="decrease">−</button>
                  <span class="mx-2 qty"><?= $qty ?></span>
                  <button class="btn btn-outline-secondary btn-sm qty-btn" data-action="increase">+</button>
                </div>
                <p class="mb-1 text-muted subtotal">Subtotal: <strong>$<?= number_format($subtotal, 2) ?></strong></p>
                <a href="remove_from_cart.php?id=<?= urlencode($key) ?>" class="btn btn-sm btn-outline-danger">🗑️</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Cart Summary -->
    <div class="col-md-4">
      <div class="card shadow-sm p-4">
        <h5 class="fw-bold mb-3">🧾 Order Summary</h5>
        <p class="d-flex justify-content-between">
          <span>Subtotal:</span><span id="summary-subtotal">$<?= number_format($total, 2) ?></span>
        </p>
        <p class="d-flex justify-content-between">
          <span>Shipping:</span><span>$0.00</span>
        </p>
        <hr>
        <p class="d-flex justify-content-between fw-bold">
          <span>Total:</span><span id="summary-total">$<?= number_format($total, 2) ?></span>
        </p>
        <div class="d-grid mt-3">
          <a href="checkout.php" class="btn btn-dark btn-lg">✅ Proceed to Checkout</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ JavaScript -->
<script>
document.querySelectorAll(".qty-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const action = btn.dataset.action;
    const container = btn.closest(".cart-item");
    const key = container.dataset.key;
    const maxStock = parseInt(container.dataset.stock);
    const qtySpan = container.querySelector(".qty");

    let qty = parseInt(qtySpan.innerText);
    if (action === "increase") {
      if (qty < maxStock) {
        qty++;
      } else {
        alert("❌ Reached max stock for this size.");
        return;
      }
    } else if (action === "decrease" && qty > 1) {
      qty--;
    }

    fetch("update_cart_ajax.php", {
      method: "POST",
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `key=${encodeURIComponent(key)}&quantity=${qty}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        qtySpan.innerText = qty;
        container.querySelector(".subtotal").innerHTML = `Subtotal: <strong>$${data.subtotal}</strong>`;
        document.getElementById("summary-subtotal").innerText = `$${data.total}`;
        document.getElementById("summary-total").innerText = `$${data.total}`;
      } else {
        alert(data.message);
      }
    });
  });
});
</script>

</body>
</html>
