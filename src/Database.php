<?php
namespace Store;

use PDO;

class Database
{
    public static function init(PDO $pdo): void
    {
        $pdo->exec("CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            price REAL
        );");

        $pdo->exec("CREATE TABLE IF NOT EXISTS product_sizes (
            product_id INTEGER,
            size TEXT,
            quantity INTEGER,
            PRIMARY KEY (product_id, size)
        );");

        $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            username TEXT,
            phone TEXT,
            address TEXT,
            total REAL,
            status TEXT DEFAULT 'pending'
        );");

        $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER,
            product_id INTEGER,
            size TEXT,
            quantity INTEGER,
            price REAL
        );");
    }

    public static function addProduct(PDO $pdo, string $name, float $price): int
    {
        $stmt = $pdo->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
        $stmt->execute([$name, $price]);
        return (int)$pdo->lastInsertId();
    }

    public static function setStock(PDO $pdo, int $productId, string $size, int $qty): void
    {
        $stmt = $pdo->prepare("INSERT OR REPLACE INTO product_sizes (product_id, size, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$productId, $size, $qty]);
    }

    public static function getStock(PDO $pdo, int $productId, string $size): int
    {
        $stmt = $pdo->prepare("SELECT quantity FROM product_sizes WHERE product_id = ? AND size = ?");
        $stmt->execute([$productId, $size]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['quantity'] : 0;
    }

    public static function decreaseStock(PDO $pdo, int $productId, string $size, int $qty): void
    {
        $stmt = $pdo->prepare("UPDATE product_sizes SET quantity = quantity - ? WHERE product_id = ? AND size = ?");
        $stmt->execute([$qty, $productId, $size]);
    }
}
