<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        $_SESSION['message'] = "❌ Cart is empty.";
        header("Location: cart.php");
        exit();
    }

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['product']['price'] * $item['quantity'];
    }

    // ✅ Check stock first
    foreach ($cart as $item) {
        $pid = (int)$item['product']['id'];
        $size = $item['size'];
        $qty = (int)$item['quantity'];

        $check_stmt = mysqli_prepare($conn, "SELECT quantity FROM product_sizes WHERE product_id = ? AND size = ?");
        mysqli_stmt_bind_param($check_stmt, "is", $pid, $size);
        mysqli_stmt_execute($check_stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($check_stmt));

        if (!$row || $row['quantity'] < $qty) {
            $_SESSION['message'] = "❌ Not enough stock for {$item['product']['name']} (Size: $size).";
            header("Location: cart.php");
            exit();
        }
    }

    // ✅ Save order in `orders`
    $order_stmt = mysqli_prepare($conn, "INSERT INTO orders (username, phone, address, total) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($order_stmt, "sssd", $username, $phone, $address, $total);
    mysqli_stmt_execute($order_stmt);
    $order_id = mysqli_insert_id($conn);

    // ✅ Now insert order items and update stock
    foreach ($cart as $item) {
        $pid = (int)$item['product']['id'];
        $size = $item['size'];
        $qty = (int)$item['quantity'];
        $price = $item['product']['price'];

        // Subtract stock
        $update_stmt = mysqli_prepare($conn, "UPDATE product_sizes SET quantity = quantity - ? WHERE product_id = ? AND size = ?");
        mysqli_stmt_bind_param($update_stmt, "iis", $qty, $pid, $size);
        mysqli_stmt_execute($update_stmt);

        // Save each item in `order_items`
        $item_stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($item_stmt, "iisid", $order_id, $pid, $size, $qty, $price);
        mysqli_stmt_execute($item_stmt);
    }

    unset($_SESSION['cart']);
    $_SESSION['message'] = "✅ Order placed successfully!";
    header("Location: thank_you.php");
    exit();
}
?>
