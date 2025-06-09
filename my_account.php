<?php
session_start();

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

</body>
</html>
