<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

$database = new Database();
$db = $database->getConnection();

$productId = $_GET['id'] ?? null;

if (!$productId) {
    http_response_code(400);
    echo json_encode(['message' => 'Product ID is required']);
    exit;
}

try {
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$productId]);
    
    $product = $stmt->fetch();
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['message' => 'Product not found']);
        exit;
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $product
    ]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch product'
    ]);
}