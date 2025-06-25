<?php
require_once 'session.php';
include 'db.php';
require_once __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['message'] = '❌ Invalid CSRF token.';
        header('Location: checkout.php');
        exit();
    }
    // Read user input
    $fullname = trim($_POST['fullname'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');

    if ($fullname === '' || $phone === '' || $address === '') {
        $_SESSION['message'] = "❌ Please fill in all the fields.";
        header("Location: checkout.php");
        exit();
    }

    // ✅ Validate phone number
    if (!preg_match('/^\+?[0-9\s\-]{7,20}$/', $phone)) {
        $_SESSION['message'] = "❌ Invalid phone number format.";
        header("Location: checkout.php");
        exit();
    }

    // ✅ Validate address (allow letters, numbers and basic punctuation)
    if (!preg_match('/^[\p{L}\d\s,.-]{5,200}$/u', $address)) {
        $_SESSION['message'] = "❌ Invalid address.";
        header("Location: checkout.php");
        exit();
    }

    // ✅ Sanitize address for storage
    $address = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');

    $cart = $_SESSION['cart'] ?? [];
    $user_id = $_SESSION['visitor']['id'] ?? 0;

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

    // ✅ Use a transaction for order creation and stock updates
    mysqli_begin_transaction($conn);

   $order_stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, username, phone, address, total, status) VALUES (?, ?, ?, ?, ?, 'pending')");    mysqli_stmt_bind_param($order_stmt, "isssd", $user_id, $fullname, $phone, $address, $total);

    if (!mysqli_stmt_execute($order_stmt)) {
        mysqli_rollback($conn);
        $_SESSION['message'] = "❌ Unable to place order.";
        header("Location: cart.php");
        exit();
    }

    $order_id = mysqli_insert_id($conn);

    foreach ($cart as $item) {
        $pid   = (int)$item['product']['id'];
        $size  = $item['size'];
        $qty   = (int)$item['quantity'];
        $price = $item['product']['price'];

        // Subtract stock
        $update_stmt = mysqli_prepare($conn, "UPDATE product_sizes SET quantity = quantity - ? WHERE product_id = ? AND size = ?");
        mysqli_stmt_bind_param($update_stmt, "iis", $qty, $pid, $size);

        if (!mysqli_stmt_execute($update_stmt)) {
            mysqli_rollback($conn);
            $_SESSION['message'] = "❌ Error updating stock.";
            header("Location: cart.php");
            exit();
        }

        // Save each item in `order_items`
        $item_stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($item_stmt, "iisid", $order_id, $pid, $size, $qty, $price);

        if (!mysqli_stmt_execute($item_stmt)) {
            mysqli_rollback($conn);
            $_SESSION['message'] = "❌ Error saving order items.";
            header("Location: cart.php");
            exit();
        }
    }

    mysqli_commit($conn);

    $message = "New order #$order_id placed by $fullname. Total: $$total";
    $adminEmail = getenv('ADMIN_EMAIL') ?: 'admin@example.com';


    unset($_SESSION['cart']);
    $_SESSION['message'] = "✅ Order placed successfully!";
    header("Location: thank_you.php");
    exit();
}
?>
