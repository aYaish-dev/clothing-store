<?php
namespace Store\Tests;

use PHPUnit\Framework\TestCase;
use Store\Database;
use Store\Cart;
use PDO;

class CartUpdateTest extends TestCase
{
    private PDO $pdo;
    private int $pid;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        Database::init($this->pdo);
        $this->pid = Database::addProduct($this->pdo, 'Shirt', 20.0);
        Database::setStock($this->pdo, $this->pid, 'M', 5);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        $_SESSION['csrf_token'] = 'token';
        $_SESSION['cart'] = [
            'item1' => [
                'product' => ['id' => $this->pid, 'price' => 20.0],
                'size' => 'M',
                'quantity' => 1
            ]
        ];
    }

    public function testUpdateQuantitySuccess(): void
    {
        $result = Cart::updateQuantity($this->pdo, 'item1', 3, 'token');
        $this->assertTrue($result['success']);
        $this->assertSame(3, $_SESSION['cart']['item1']['quantity']);
        $this->assertSame('60.00', $result['subtotal']);
        $this->assertSame('60.00', $result['total']);
    }

    public function testUpdateQuantityInsufficientStock(): void
    {
        $result = Cart::updateQuantity($this->pdo, 'item1', 10, 'token');
        $this->assertFalse($result['success']);
        $this->assertSame(1, $_SESSION['cart']['item1']['quantity']);
    }
}
