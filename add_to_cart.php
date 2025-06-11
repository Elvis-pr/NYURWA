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

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['product_id']) || !isset($data['quantity'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Product ID and quantity are required']);
    exit;
}

$productId = $data['product_id'];
$quantity = $data['quantity'];

try {
    // Check if product exists and has enough stock
    $productQuery = "SELECT * FROM products WHERE id = ?";
    $productStmt = $db->prepare($productQuery);
    $productStmt->execute([$productId]);
    $product = $productStmt->fetch();
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['message' => 'Product not found']);
        exit;
    }
    
    if ($product['stock'] < $quantity) {
        http_response_code(400);
        echo json_encode(['message' => 'Not enough stock available']);
        exit;
    }
    
    // Check if cart exists for user
    $cartQuery = "SELECT * FROM carts WHERE user_id = ?";
    $cartStmt = $db->prepare($cartQuery);
    $cartStmt->execute([$userId]);
    $cart = $cartStmt->fetch();
    
    if (!$cart) {
        // Create new cart
        $createCartQuery = "INSERT INTO carts (user_id, total) VALUES (?, 0)";
        $createCartStmt = $db->prepare($createCartQuery);
        $createCartStmt->execute([$userId]);
        $cartId = $db->lastInsertId();
    } else {
        $cartId = $cart['id'];
    }
    
    // Check if product already in cart
    $cartItemQuery = "SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?";
    $cartItemStmt = $db->prepare($cartItemQuery);
    $cartItemStmt->execute([$cartId, $productId]);
    $cartItem = $cartItemStmt->fetch();
    
    if ($cartItem) {
        // Update quantity
        $updateQuery = "UPDATE cart_items SET quantity = quantity + ? WHERE id = ?";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->execute([$quantity, $cartItem['id']]);
    } else {
        // Add new item
        $insertQuery = "INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->execute([$cartId, $productId, $quantity, $product['price']]);
    }
    
    // Update cart total
    $updateTotalQuery = "UPDATE carts SET total = (
        SELECT SUM(ci.quantity * ci.price) 
        FROM cart_items ci 
        WHERE ci.cart_id = ?
    ) WHERE id = ?";
    $updateTotalStmt = $db->prepare($updateTotalQuery);
    $updateTotalStmt->execute([$cartId, $cartId]);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart'
    ]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to add to cart'
    ]);
}