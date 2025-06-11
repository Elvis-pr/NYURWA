<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT * FROM products";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $products = $stmt->fetchAll();
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch products'
    ]);
}