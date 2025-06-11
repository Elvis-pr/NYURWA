<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';
require_once '../../includes/auth_functions.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Email and password are required']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Check if user exists
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Invalid email or password']);
    exit;
}

// Generate JWT token
$token = generateJWT([
    'user_id' => $user['id'],
    'email' => $user['email'],
    'role' => $user['role'],
    'exp' => time() + (60 * 60 * 24 * 30) // 30 days
]);

http_response_code(200);
echo json_encode([
    'message' => 'Login successful',
    'token' => $token,
    'user' => [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ]
]);