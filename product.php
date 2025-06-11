<?php
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "username", "password", "database");
if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Failed to connect to database']);
    exit();
}

$result = $mysqli->query("SELECT id, name, price, description, image, category FROM products");
$products = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($products);
?>
