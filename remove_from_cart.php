<?php
require_once 'session.php';

if (isset($_GET['id']) && isset($_GET['csrf_token']) && $_GET['csrf_token'] === $_SESSION['csrf_token'] && isset($_SESSION['cart'][$_GET['id']])) {
    unset($_SESSION['cart'][$_GET['id']]);
} elseif (isset($_GET['id'])) {
    $_SESSION['message'] = '❌ Invalid CSRF token.';
}

header("Location: cart.php");
exit();
