<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';
require_once '../../includes/auth_functions.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Name, email and password are required']);
    exit;
}

$name = $data['name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_BCRYPT);

// Check if user already exists
$query = "SELECT id FROM users WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->execute([$email]);

if ($stmt->fetch()) {
    http_response_code(400);
    echo json_encode(['message' => 'User already exists with this email']);
    exit;
}

// Create new user
$query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
$stmt = $db->prepare($query);
$stmt->execute([$name, $email, $password]);

$userId = $db->lastInsertId();

// Generate JWT token
$token = generateJWT([
    'user_id' => $userId,
    'email' => $email,
    'role' => 'user',
    'exp' => time() + (60 * 60 * 24 * 30) // 30 days
]);

http_response_code(201);
echo json_encode([
    'message' => 'User registered successfully',
    'token' => $token,
    'user' => [
        'id' => $userId,
        'name' => $name,
        'email' => $email,
        'role' => 'user'
    ]
]);