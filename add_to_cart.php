<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['product_id'];
    $size = trim($_POST['size']);
    $qty = max(1, (int) $_POST['quantity']); // والـ quantity رح توصل كـ hidden = 1


    // 1. Get product info
    $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $product_result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($product_result);

    if (!$product) {
        $_SESSION['message'] = "❌ Product not found.";
        header("Location: cart.php");
        exit();
    }

    // 2. Get stock for selected size
    $stock_stmt = mysqli_prepare($conn, "SELECT quantity FROM product_sizes WHERE product_id = ? AND size = ?");
    mysqli_stmt_bind_param($stock_stmt, "is", $id, $size);
    mysqli_stmt_execute($stock_stmt);
    $stock_result = mysqli_stmt_get_result($stock_stmt);
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
