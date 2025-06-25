<?php
require_once 'session.php';
include 'db.php';

if (!isset($_SESSION['visitor'])) {
    header("Location: visitor_login.php");
    exit();
}

$username = $_SESSION['visitor']['name'];  // ✅ التعديل هون
$cart = $_SESSION['cart'] ?? [];
$cart_count = 0;

foreach ($cart as $item) {
    $cart_count += $item['quantity'];
}

// Fetch recent orders for this visitor
$user_id = $_SESSION['visitor']['id'];
$order_stmt = mysqli_prepare($conn, "SELECT id, total, order_date FROM orders WHERE user_id=? ORDER BY order_date DESC");
mysqli_stmt_bind_param($order_stmt, 'i', $user_id);
mysqli_stmt_execute($order_stmt);
$orders_result = mysqli_stmt_get_result($order_stmt);
?>


<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-3">👤 My Account</h3>

        <ul class="list-group list-group-flush mb-3">
            

            <li class="list-group-item"><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></li>
            <li class="list-group-item"><strong>Items in Cart:</strong> <?php echo $cart_count; ?></li>
        </ul>

        <div class="d-grid gap-2">
            <a href="index.php" class="btn btn-outline-primary">🏠 Home</a>
            <a href="cart.php" class="btn btn-outline-success">🛒 View Cart</a>
            <a href="logout.php" class="btn btn-outline-danger">🚪 Logout</a>
        </div>
    </div>
</div>

<div class="container py-5">
    <h4 class="mb-3">🧾 Recent Orders</h4>
    <?php if (mysqli_num_rows($orders_result) > 0): ?>
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td>$<?= number_format($order['total'], 2) ?></td>
                        <td><?= $order['order_date'] ?></td>
                        <td><a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No orders found.</div>
    <?php endif; ?>
</div>

</body>
</html>
