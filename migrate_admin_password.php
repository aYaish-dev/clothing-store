<?php
/**
 * Simple migration script to hash the admin password.
 *
 * Usage: php migrate_admin_password.php <plain_password>
 */

include 'db.php';

if ($argc < 2) {
    echo "Usage: php migrate_admin_password.php <plain_password>\n";
    exit(1);
}

$plain = $argv[1];
$hash  = password_hash($plain, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password='" . mysqli_real_escape_string($conn, $hash) . "' WHERE username='admin'";
try {
    mysqli_query($conn, $sql);
    echo "Admin password updated.\n";
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
