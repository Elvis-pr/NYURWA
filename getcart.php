<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';
require_once '../../includes/auth_functions.php';

$database = new Database();
$db = $database->getConnection();

// Check for JWT token
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['message' => 'Authorization token is required']);
    exit;
}

$jwt = $matches[1];
if (!validateJWT($jwt)) {
    http_response_code(401);
    echo json_encode(['message' => 'Invalid or expired token']);
    exit;
}

$tokenData = getJWTData($jwt);
$userId = $tokenData['user_id'];

try {
    // Get cart with items
    $cartQuery = "SELECT c.* FROM carts c WHERE c.user_id = ?";
    $cartStmt = $db->prepare($cartQuery);
    $cartStmt->execute([$userId]);
    $cart = $cartStmt->fetch();
    
    if (!$cart) {
        // Create empty cart if not exists
        $createCartQuery = "INSERT INTO carts (user_id, total) VALUES (?, 0)";
        $createCartStmt = $db->prepare($createCartQuery);
        $createCartStmt->execute([$userId]);
        $cartId = $db->lastInsertId();
        
        $cart = [
            'id' => $cartId,
            'user_id' => $userId,
            'total' => 0,
            'items' => []
        ];
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $cart
        ]);
        exit;
    }
    
    // Get cart items with product details
    $itemsQuery = "SELECT ci.*, p.name, p.image FROM cart_items ci 
                  JOIN products p ON ci.product_id = p.id 
                  WHERE ci.cart_id = ?";
    $itemsStmt = $db->prepare($itemsQuery);
    $itemsStmt->execute([$cart['id']]);
    $items = $itemsStmt->fetchAll();
    
    $cart['items'] = $items;
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $cart
    ]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch cart'
    ]);
}