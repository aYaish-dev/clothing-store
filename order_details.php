<?php
require_once 'session.php';
include 'db.php';

if (!isset($_SESSION['visitor'])) {
    header('Location: visitor_login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: my_account.php');
    exit();
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['visitor']['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id=? AND user_id=?");
mysqli_stmt_bind_param($stmt, 'ii', $order_id, $user_id);
mysqli_stmt_execute($stmt);
$order_res = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($order_res);

if (!$order) {
    header('Location: my_account.php');
    exit();
}

$item_stmt = mysqli_prepare($conn, "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id=?");
mysqli_stmt_bind_param($item_stmt, 'i', $order_id);
mysqli_stmt_execute($item_stmt);
$items_res = mysqli_stmt_get_result($item_stmt);
?>
<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body class="bg-light">
<div class="container py-5">
    <h3 class="mb-4">Order #<?= $order['id'] ?></h3>
    <p><strong>Date:</strong> <?= $order['order_date'] ?></p>
    <p><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Size</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($items_res)): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['size']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="my_account.php" class="btn btn-secondary">&larr; Back to Account</a>
</div>
</body>
</html>
