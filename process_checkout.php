<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

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
        $size = mysqli_real_escape_string($conn, $item['size']);
        $qty = (int)$item['quantity'];

        $check = mysqli_query($conn, "SELECT quantity FROM product_sizes WHERE product_id = $pid AND size = '$size'");
        $row = mysqli_fetch_assoc($check);

        if (!$row || $row['quantity'] < $qty) {
            $_SESSION['message'] = "❌ Not enough stock for {$item['product']['name']} (Size: $size).";
            header("Location: cart.php");
            exit();
        }
    }

    // ✅ Save order in `orders`
    $insert_order = "INSERT INTO orders (username, phone, address, total)
                     VALUES ('$username', '$phone', '$address', '$total')";
    mysqli_query($conn, $insert_order);
    $order_id = mysqli_insert_id($conn);

    // ✅ Now insert order items and update stock
    foreach ($cart as $item) {
        $pid = (int)$item['product']['id'];
        $size = mysqli_real_escape_string($conn, $item['size']);
        $qty = (int)$item['quantity'];
        $price = $item['product']['price'];

        // Subtract stock
        mysqli_query($conn, "UPDATE product_sizes SET quantity = quantity - $qty WHERE product_id = $pid AND size = '$size'");

        // Save each item in `order_items`
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, size, quantity, price)
                             VALUES ($order_id, $pid, '$size', $qty, $price)");
    }

    unset($_SESSION['cart']);
    $_SESSION['message'] = "✅ Order placed successfully!";
    header("Location: thank_you.php");
    exit();
}
?>
