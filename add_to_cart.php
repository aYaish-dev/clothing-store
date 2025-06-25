<?php
session_start();
include 'db.php';
include 'csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['token'] ?? '')) {
        $_SESSION['message'] = 'Invalid CSRF token';
        header('Location: cart.php');
        exit();
    }
    $id = (int) $_POST['product_id'];
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $qty = max(1, (int) $_POST['quantity']); // والـ quantity رح توصل كـ hidden = 1
    if ($qty > 1000) {
        $qty = 1000;
    }


    // 1. Get product info
    $product_result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
    $product = mysqli_fetch_assoc($product_result);

    if (!$product) {
        $_SESSION['message'] = "❌ Product not found.";
        header("Location: cart.php");
        exit();
    }

    // 2. Get stock for selected size
    $stock_result = mysqli_query($conn, "SELECT quantity FROM product_sizes WHERE product_id = $id AND size = '$size'");
    $stock_row = mysqli_fetch_assoc($stock_result);
    $stock = $stock_row['quantity'] ?? 0;

    // 3. Unique cart key = product + size
    $key = $id . "_" . $size;
    $currentQty = $_SESSION['cart'][$key]['quantity'] ?? 0;

    if ($stock === 0) {
        $_SESSION['message'] = "❌ Size $size is out of stock.";
    } elseif ($currentQty + $qty > $stock) {
        $_SESSION['message'] = "❌ Only $stock item(s) in stock for size $size.";
    } else {
        $_SESSION['cart'][$key] = [
            'product' => $product,
            'size' => $size,
            'quantity' => $currentQty + $qty
        ];
        $_SESSION['message'] = "✅ Added to cart ($size)!";
    }
}

header("Location: cart.php");
exit();
