<?php
namespace Store\Tests;

use PHPUnit\Framework\TestCase;
use Store\Database;
use Store\Checkout;
use PDO;

class CheckoutTest extends TestCase
{
    private PDO $pdo;
    private int $pid;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        Database::init($this->pdo);
        $this->pid = Database::addProduct($this->pdo, 'Jeans', 30.0);
        Database::setStock($this->pdo, $this->pid, 'L', 5);
    }

    public function testPlaceOrder(): void
    {
        $cart = [
            ['id' => $this->pid, 'size' => 'L', 'qty' => 2, 'price' => 30.0]
        ];
        $orderId = Checkout::placeOrder($this->pdo, 1, 'John Doe', '123', 'Street 1', $cart);
        $this->assertIsInt($orderId);
        $this->assertSame(3, Database::getStock($this->pdo, $this->pid, 'L'));
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM order_items WHERE order_id = $orderId");
        $this->assertSame(1, (int)$stmt->fetchColumn());
    }

    public function testPlaceOrderInsufficientStock(): void
    {
        $cart = [
            ['id' => $this->pid, 'size' => 'L', 'qty' => 6, 'price' => 30.0]
        ];
        $this->expectException(\RuntimeException::class);
        Checkout::placeOrder($this->pdo, 1, 'John Doe', '123', 'Street 1', $cart);
    }
}
