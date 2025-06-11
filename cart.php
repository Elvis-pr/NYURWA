<?php
session_start();
header('Content-Type: application/json');

// Initialize cart in session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';
$productId = intval($_POST['productId'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);

switch ($action) {
    case 'add':
        // If product already in cart, increase quantity
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        echo json_encode(['status' => 'success', 'cart' => $_SESSION['cart']]);
        break;

    case 'update':
        if ($quantity > 0) {
            $_SESSION['cart'][$productId] = $quantity;
        } else {
            unset($_SESSION['cart'][$productId]);
        }
        echo json_encode(['status' => 'success', 'cart' => $_SESSION['cart']]);
        break;

    case 'remove':
        unset($_SESSION['cart'][$productId]);
        echo json_encode(['status' => 'success', 'cart' => $_SESSION['cart']]);
        break;

    case 'get':
    default:
        echo json_encode(['cart' => $_SESSION['cart']]);
        break;
}
?>
