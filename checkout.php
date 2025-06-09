<?php
session_start();
include 'db.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    $_SESSION['message'] = "❌ Your cart is empty!";
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body class="bg-light">

<div class="container py-5">
    <h2 class="text-center mb-4">🧾 Review Your Order</h2>

    <div class="table-responsive mb-4">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($cart as $item): 
                    $product = $item['product'];
                    $qty = $item['quantity'];
                    $size = $item['size'];
                    $subtotal = $product['price'] * $qty;
                    $total += $subtotal;
                ?>
                <tr>
                    <td><img src="uploads/<?php echo $product['image']; ?>" width="70" class="img-thumbnail"></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $size; ?></td>
                    <td>$<?php echo $product['price']; ?></td>
                    <td><?php echo $qty; ?></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" class="text-end"><strong>Total:</strong></td>
                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h2 class="text-center mb-4">📝 Enter Your Information</h2>

    <div class="card p-4 shadow mx-auto" style="max-width: 600px;">
        <form method="POST" action="process_checkout.php">
            <div class="mb-3">
                <label class="form-label">Full Name:</label>
                <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone:</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address:</label>
                <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success w-100">✅ Confirm Order</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
