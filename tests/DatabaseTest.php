<?php
namespace Store\Tests;

use PHPUnit\Framework\TestCase;
use Store\Database;
use PDO;

class DatabaseTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        Database::init($this->pdo);
    }

    public function testStockFunctions(): void
    {
        $pid = Database::addProduct($this->pdo, 'Shirt', 19.99);
        Database::setStock($this->pdo, $pid, 'M', 10);
        $this->assertSame(10, Database::getStock($this->pdo, $pid, 'M'));
        Database::decreaseStock($this->pdo, $pid, 'M', 3);
        $this->assertSame(7, Database::getStock($this->pdo, $pid, 'M'));
    }
}
